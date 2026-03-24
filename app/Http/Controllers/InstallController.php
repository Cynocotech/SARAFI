<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class InstallController extends Controller
{
    /**
     * Check if application is already installed.
     */
    public static function isInstalled(): bool
    {
        if (File::exists(storage_path('installed'))) {
            return true;
        }

        // APP_KEY from .env (local) or container/orchestrator environment (Docker, Dokploy, etc.)
        return trim((string) env('APP_KEY', '')) !== '';
    }

    /**
     * Step 1: Requirements check.
     */
    public function requirements()
    {
        // Ensure .env exists with file sessions for install (DB not ready yet)
        $envPath = base_path('.env');
        if (! File::exists($envPath) && File::exists(base_path('.env.example'))) {
            File::copy(base_path('.env.example'), $envPath);
            $c = File::get($envPath);
            $c = preg_replace('/^SESSION_DRIVER=.*/m', 'SESSION_DRIVER=file', $c);
            File::put($envPath, $c);
        }
        $requirements = $this->checkRequirements();

        return view('install.requirements', [
            'requirements' => $requirements,
            'passed' => ! collect($requirements)->contains(fn ($r) => ! $r['passed']),
        ]);
    }

    /**
     * Step 2: Database configuration.
     */
    public function database()
    {
        return view('install.database');
    }

    /**
     * Process installation.
     */
    public function process(Request $request)
    {
        $request->validate([
            'db_host' => ['required', 'string', 'max:255'],
            'db_port' => ['required', 'string', 'max:10'],
            'db_database' => ['required', 'string', 'max:255'],
            'db_username' => ['required', 'string', 'max:255'],
            'db_password' => ['nullable', 'string', 'max:255'],
            'app_name' => ['required', 'string', 'max:255'],
            'app_url' => ['required', 'url', 'max:255'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email'],
            'admin_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $dbHost = $request->input('db_host');
        $dbPort = $request->input('db_port');
        $dbDatabase = $request->input('db_database');
        $dbUsername = $request->input('db_username');
        $dbPassword = $request->input('db_password');

        // Test database connection
        try {
            $pdo = new \PDO(
                "mysql:host={$dbHost};port={$dbPort};dbname={$dbDatabase};charset=utf8mb4",
                $dbUsername,
                $dbPassword,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        } catch (\PDOException $e) {
            return back()->withInput()->withErrors([
                'db_database' => 'Database connection failed: '.$e->getMessage(),
            ]);
        }

        // Ensure .env exists
        $envPath = base_path('.env');
        if (! File::exists($envPath)) {
            $example = base_path('.env.example');
            if (File::exists($example)) {
                File::copy($example, $envPath);
            }
        }

        // Update .env (handles commented lines)
        $envContent = File::get($envPath);
        $sets = [
            'DB_CONNECTION' => 'mysql',
            'SESSION_DRIVER' => 'database',
            'DB_HOST' => $dbHost,
            'DB_PORT' => $dbPort,
            'DB_DATABASE' => $dbDatabase,
            'DB_USERNAME' => $dbUsername,
            'DB_PASSWORD' => $dbPassword,
            'APP_NAME' => addslashes($request->input('app_name')),
            'APP_URL' => rtrim($request->input('app_url'), '/'),
        ];
        foreach ($sets as $key => $value) {
            $escaped = $key === 'DB_PASSWORD' && (str_contains($value, '$') || str_contains($value, '"'))
                ? '"'.addslashes($value).'"'
                : $value;
            $pattern = '/^(#\s*)?'.preg_quote($key, '/').'=.*/m';
            $replacement = "{$key}={$escaped}";
            $envContent = preg_replace($pattern, $replacement, $envContent, 1);
        }
        if (! preg_match('/^APP_KEY=base64:[A-Za-z0-9+\/=]{40,}/m', $envContent)) {
            $key = 'base64:'.base64_encode(Str::random(32));
            $envContent = preg_replace('/^APP_KEY=.*/m', "APP_KEY={$key}", $envContent, 1);
            if (! str_contains($envContent, "APP_KEY={$key}")) {
                $envContent .= "\nAPP_KEY={$key}\n";
            }
        }

        File::put($envPath, $envContent);

        // Clear config cache and reload
        Artisan::call('config:clear');
        config()->set('database.connections.mysql.host', $dbHost);
        config()->set('database.connections.mysql.port', $dbPort);
        config()->set('database.connections.mysql.database', $dbDatabase);
        config()->set('database.connections.mysql.username', $dbUsername);
        config()->set('database.connections.mysql.password', $dbPassword);

        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors([
                'db_database' => 'Migration failed: '.$e->getMessage(),
            ]);
        }

        // Seed essential data (plans, onboarding fields)
        try {
            Artisan::call('db:seed', [
                '--class' => \Database\Seeders\PlanSeeder::class,
                '--force' => true,
            ]);
            Artisan::call('db:seed', [
                '--class' => \Database\Seeders\OnboardingFieldSeeder::class,
                '--force' => true,
            ]);
        } catch (\Throwable $e) {
            // Non-critical, continue
        }

        // Create admin user
        try {
            User::create([
                'name' => $request->input('admin_name'),
                'email' => $request->input('admin_email'),
                'password' => $request->input('admin_password'),
                'role' => 'admin',
            ]);
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors([
                'admin_email' => 'Failed to create admin: '.$e->getMessage(),
            ]);
        }

        // Storage link
        try {
            if (! File::exists(public_path('storage'))) {
                Artisan::call('storage:link');
            }
        } catch (\Throwable $e) {
            // Non-critical
        }

        // Mark as installed
        File::put(storage_path('installed'), date('Y-m-d H:i:s'));

        Artisan::call('config:clear');

        return redirect()->route('install.complete');
    }

    /**
     * Installation complete.
     */
    public function complete()
    {
        return view('install.complete');
    }

    /**
     * Check system requirements.
     */
    private function checkRequirements(): array
    {
        $requirements = [];

        $requirements[] = [
            'name' => 'PHP Version (>= 8.2)',
            'passed' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'current' => PHP_VERSION,
        ];

        $requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'json', 'curl', 'fileinfo'];
        foreach ($requiredExtensions as $ext) {
            $requirements[] = [
                'name' => "PHP Extension: {$ext}",
                'passed' => extension_loaded($ext),
                'current' => extension_loaded($ext) ? 'Loaded' : 'Missing',
            ];
        }

        $writablePaths = [
            base_path('.env') => 'Parent writable for .env',
            base_path('storage') => 'storage/',
            base_path('storage/framework') => 'storage/framework/',
            base_path('storage/logs') => 'storage/logs/',
            base_path('bootstrap/cache') => 'bootstrap/cache/',
        ];

        foreach ($writablePaths as $path => $label) {
            $exists = File::exists($path);
            $writable = $exists ? is_writable($path) : (File::exists(dirname($path)) && is_writable(dirname($path)));
            if (! $exists && $path !== base_path('.env')) {
                try {
                    File::makeDirectory($path, 0755, true);
                    $writable = is_writable($path);
                } catch (\Throwable $e) {
                    $writable = false;
                }
            }
            $requirements[] = [
                'name' => "Writable: {$label}",
                'passed' => $writable,
                'current' => $writable ? 'OK' : 'Not writable',
            ];
        }

        return $requirements;
    }
}

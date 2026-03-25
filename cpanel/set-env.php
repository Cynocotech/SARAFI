<?php

/**
 * Merge deployment keys into .env (safe quoting for special characters).
 * Usage: php cpanel/set-env.php /path/to/config.json [--db-password-file=/path]
 */

declare(strict_types=1);

function dotenvQuote(string $value): string
{
    if ($value === '') {
        return '""';
    }
    if (preg_match('/^[A-Za-z0-9_.@^!%+-]+$/', $value)) {
        return $value;
    }

    return '"'.str_replace(["\r\n", "\n", "\r", '\\', '"'], ['\n', '\n', '\n', '\\\\', '\"'], $value).'"';
}

function mergeEnv(string $path, array $pairs): void
{
    $content = is_file($path) ? file_get_contents($path) : '';
    $lines = $content === '' || $content === false ? [] : preg_split('/\r\n|\n|\r/', $content);
    $out = [];
    $seen = [];

    foreach ($lines as $line) {
        $trim = ltrim($line);
        if ($trim === '' || str_starts_with($trim, '#')) {
            $out[] = $line;

            continue;
        }
        if (! preg_match('/^([A-Za-z_][A-Za-z0-9_]*)\s*=\s*(.*)$/', $line, $m)) {
            $out[] = $line;

            continue;
        }
        $key = $m[1];
        if (array_key_exists($key, $pairs)) {
            $out[] = $key.'='.dotenvQuote($pairs[$key]);
            $seen[$key] = true;
        } else {
            $out[] = $line;
        }
    }

    foreach ($pairs as $key => $val) {
        if (! isset($seen[$key])) {
            $out[] = $key.'='.dotenvQuote($val);
        }
    }

    file_put_contents($path, implode("\n", $out)."\n");
}

$root = dirname(__DIR__);
$envFile = $root.'/.env';

$jsonPath = $argv[1] ?? null;
if ($jsonPath === null || ! is_readable($jsonPath)) {
    fwrite(STDERR, "Usage: php cpanel/set-env.php config.json [--db-password-file=/path]\n");

    exit(1);
}

$raw = file_get_contents($jsonPath);
$data = json_decode($raw, true);
if (! is_array($data)) {
    fwrite(STDERR, "Invalid JSON in {$jsonPath}\n");

    exit(1);
}

$dbPasswordFile = null;
foreach (array_slice($argv, 2) as $arg) {
    if (str_starts_with($arg, '--db-password-file=')) {
        $dbPasswordFile = substr($arg, strlen('--db-password-file='));
    }
}

if ($dbPasswordFile !== null) {
    if (! is_readable($dbPasswordFile)) {
        fwrite(STDERR, "Cannot read password file.\n");

        exit(1);
    }
    $data['DB_PASSWORD'] = file_get_contents($dbPasswordFile) ?: '';
}

$allowed = [
    'APP_NAME', 'APP_ENV', 'APP_DEBUG', 'APP_URL',
    'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD',
];

$pairs = [];
foreach ($allowed as $key) {
    if (array_key_exists($key, $data)) {
        $pairs[$key] = (string) $data[$key];
    }
}

if ($pairs === []) {
    fwrite(STDERR, "No keys to write.\n");

    exit(1);
}

if (! is_file($envFile)) {
    fwrite(STDERR, "Missing .env at {$envFile}. Copy .env.example to .env first.\n");

    exit(1);
}

mergeEnv($envFile, $pairs);
echo "Updated {$envFile}\n";

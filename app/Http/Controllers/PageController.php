<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;

class PageController extends Controller
{
    public function guide(): View
    {
        $content = $this->getDecodedSetting('guide_content', '');
        $title = Setting::get('guide_title') ?: 'راهنما';

        return view('guide', [
            'guide_title' => $title,
            'guide_content' => $content,
        ]);
    }

    public function contact(): View
    {
        $content = $this->getDecodedSetting('contact_content', '');
        $title = Setting::get('contact_title') ?: 'تماس با ما';
        $phone = Setting::get('contact_phone', '');
        $email = Setting::get('contact_email', '');

        return view('contact', [
            'contact_title' => $title,
            'contact_content' => $content,
            'contact_phone' => $phone,
            'contact_email' => $email,
        ]);
    }

    private function getDecodedSetting(string $key, mixed $default): mixed
    {
        $value = Setting::get($key, $default);
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : $value;
        }
        return $value;
    }
}

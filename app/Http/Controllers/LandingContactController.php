<?php

namespace App\Http\Controllers;

use App\Mail\LandingContactMail;
use App\Models\ExchangeOffice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class LandingContactController extends Controller
{
    public function store(Request $request, ExchangeOffice $exchangeOffice): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:2000'],
        ], [
            'name.required' => 'نام الزامی است.',
            'email.required' => 'ایمیل الزامی است.',
            'email.email' => 'ایمیل معتبر نیست.',
            'message.required' => 'متن پیام الزامی است.',
        ]);

        $toEmail = $exchangeOffice->email;
        if (! $toEmail || ! filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages(['email' => ['امکان ارسال ایمیل برای این صرافی وجود ندارد.']]);
        }

        try {
            Mail::to($toEmail)->send(new LandingContactMail(
                $exchangeOffice,
                $validated['name'],
                $validated['email'],
                $validated['message'],
                $validated['phone'] ?? null
            ));
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('contact_error', 'ارسال پیام با خطا مواجه شد. لطفاً بعداً تلاش کنید یا با تلفن تماس بگیرید.');
        }

        $this->sendToTelegram($exchangeOffice, $validated);

        $redirect = back()->with('contact_success', 'پیام شما با موفقیت ارسال شد. به زودی با شما تماس گرفته می‌شود.');
        $redirect->setTargetUrl($redirect->getTargetUrl() . '#location');
        return $redirect;
    }

    protected function sendToTelegram(ExchangeOffice $exchangeOffice, array $data): void
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');
        if (! $token || ! $chatId) {
            return;
        }

        $officeName = $exchangeOffice->name;
        $text = "📩 پیام تماس از سایت\n\n";
        $text .= "صرافی: {$officeName}\n";
        $text .= "نام: {$data['name']}\n";
        $text .= "ایمیل: {$data['email']}\n";
        if (! empty($data['phone'])) {
            $text .= "تلفن: {$data['phone']}\n";
        }
        $text .= "\nپیام:\n{$data['message']}";

        try {
            Http::timeout(10)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}

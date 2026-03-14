<?php

namespace Database\Seeders;

use App\Models\OnboardingField;
use Illuminate\Database\Seeder;

class OnboardingFieldSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'name', 'label' => 'نام کسب‌وکار', 'type' => 'text', 'placeholder' => 'نام صرافی', 'required' => true, 'sort_order' => 0],
            ['key' => 'fca_number', 'label' => 'شماره FCA (اختیاری)', 'type' => 'text', 'placeholder' => 'FCA Firm Reference Number', 'required' => false, 'sort_order' => 1],
            ['key' => 'company_house_id', 'label' => 'Company House ID (اختیاری)', 'type' => 'text', 'placeholder' => 'Company House ID', 'required' => false, 'sort_order' => 2],
            ['key' => 'address_line_1', 'label' => 'آدرس خط ۱', 'type' => 'text', 'placeholder' => 'آدرس کامل', 'required' => true, 'sort_order' => 3],
            ['key' => 'city', 'label' => 'شهر', 'type' => 'text', 'placeholder' => 'London', 'required' => true, 'sort_order' => 4],
            ['key' => 'postcode', 'label' => 'کد پستی UK', 'type' => 'text', 'placeholder' => 'SW1A 1AA', 'required' => true, 'sort_order' => 5],
            ['key' => 'phone', 'label' => 'تلفن', 'type' => 'tel', 'placeholder' => '+44 20 1234 5678', 'required' => false, 'sort_order' => 6],
            ['key' => 'email', 'label' => 'ایمیل', 'type' => 'email', 'placeholder' => 'info@exchange.com', 'required' => false, 'sort_order' => 7],
        ];

        foreach ($defaults as $row) {
            OnboardingField::updateOrCreate(
                ['key' => $row['key']],
                $row
            );
        }
    }
}

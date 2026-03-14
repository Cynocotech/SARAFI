<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'name_fa' => 'پایه',
                'price' => 9.99,
                'interval' => Plan::INTERVAL_1_MONTH,
                'description' => 'لیست در دایرکتوری • به‌روزرسانی نرخ',
                'features' => [Plan::FEATURE_BASIC],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Pro',
                'name_fa' => 'حرفه‌ای',
                'price' => 19.99,
                'interval' => Plan::INTERVAL_3_MONTHS,
                'description' => 'همه امکانات پایه • هایلایت در نتایج • پشتیبانی اختصاصی',
                'features' => [Plan::FEATURE_BASIC, Plan::FEATURE_HIGHLIGHT, Plan::FEATURE_DEDICATED_SUPPORT, Plan::FEATURE_DIGITAL_SIGNAGE],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'name_fa' => 'ویژه',
                'price' => 39.99,
                'interval' => Plan::INTERVAL_6_MONTHS,
                'description' => 'همه امکانات • بنر تبلیغاتی • اولویت در جستجو',
                'features' => [Plan::FEATURE_BASIC, Plan::FEATURE_HIGHLIGHT, Plan::FEATURE_DEDICATED_SUPPORT, Plan::FEATURE_DIGITAL_SIGNAGE],
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $data) {
            Plan::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}

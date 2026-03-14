<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    public const INTERVAL_MONTHLY = 'monthly';

    /** Billing interval in months: 1, 3, or 6 */
    public const INTERVAL_1_MONTH = '1';
    public const INTERVAL_3_MONTHS = '3';
    public const INTERVAL_6_MONTHS = '6';

    /** Feature keys for plan capabilities */
    public const FEATURE_BASIC = 'basic';
    public const FEATURE_HIGHLIGHT = 'highlight';
    public const FEATURE_DEDICATED_SUPPORT = 'dedicated_support';
    public const FEATURE_DIGITAL_SIGNAGE = 'digital_signage';

    protected $fillable = [
        'name',
        'name_fa',
        'price',
        'interval',
        'description',
        'features',
        'sort_order',
        'is_active',
        'stripe_price_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'features' => 'array',
    ];

    /**
     * All available feature keys and their Persian labels.
     */
    public static function featureOptions(): array
    {
        return [
            self::FEATURE_BASIC => 'همه امکانات پایه',
            self::FEATURE_HIGHLIGHT => 'هایلایت در نتایج',
            self::FEATURE_DEDICATED_SUPPORT => 'پشتیبانی اختصاصی',
            self::FEATURE_DIGITAL_SIGNAGE => 'صفحه نمایش دیجیتال (نرخ روی تلویزیون)',
        ];
    }

    /**
     * Get Persian labels for this plan's features (for display).
     */
    public function getFeatureLabels(): array
    {
        $opts = self::featureOptions();
        $features = $this->features ?? [];

        return array_values(array_filter(array_map(
            fn ($key) => $opts[$key] ?? null,
            $features
        )));
    }

    public function hasFeature(string $key): bool
    {
        return in_array($key, $this->features ?? [], true);
    }

    /**
     * Available interval options (months) and labels for display.
     */
    public static function intervalOptions(): array
    {
        return [
            self::INTERVAL_1_MONTH => '1 month',
            self::INTERVAL_3_MONTHS => '3 months',
            self::INTERVAL_6_MONTHS => '6 months',
        ];
    }

    /**
     * Label for this plan's interval (e.g. "۱ ماه", "۳ ماه", "۶ ماه" in Persian).
     */
    public function getIntervalLabel(string $locale = 'en'): string
    {
        $months = (int) $this->interval;
        if ($months <= 0) {
            $months = 1;
        }
        if ($locale === 'fa' || $locale === 'fa_IR') {
            $persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            $num = (string) $months;
            foreach (range(0, 9) as $d) {
                $num = str_replace((string) $d, $persianDigits[$d], $num);
            }
            return $num . ' ماه';
        }
        return $months === 1 ? '1 month' : $months . ' months';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}

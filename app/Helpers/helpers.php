<?php

if (! function_exists('farsi_num')) {
    /**
     * Convert English digits (0-9) in a string to Farsi/Persian digits (۰-۹).
     */
    function farsi_num(string|int|float|null $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }
        $value = (string) $value;
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($english, $persian, $value);
    }
}

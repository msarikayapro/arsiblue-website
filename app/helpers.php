<?php

if (! function_exists('setting')) {
    /**
     * Settings tablosundan değer alır. Cache'lenir.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return app(\App\Services\SettingService::class)->get($key, $default);
    }
}

if (! function_exists('whatsappLink')) {
    /**
     * Settings'teki phone_whatsapp'tan wa.me linki üretir.
     */
    function whatsappLink(string $message = ''): string
    {
        $number = preg_replace('/[^0-9]/', '', (string) setting('phone_whatsapp', ''));
        if ($number === '') {
            return '#';
        }
        if (! str_starts_with($number, '90')) {
            $number = '90' . ltrim($number, '0');
        }

        return 'https://wa.me/' . $number . ($message !== '' ? '?text=' . urlencode($message) : '');
    }
}

if (! function_exists('phoneLink')) {
    function phoneLink(?string $number): string
    {
        return 'tel:' . preg_replace('/[^0-9+]/', '', (string) $number);
    }
}

if (! function_exists('priceFormat')) {
    /**
     * Türkçe locale ile fiyat: 16250 → "16.250 TL"
     */
    function priceFormat(int|float|null $amount, string $currency = 'TL'): string
    {
        if ($amount === null) {
            return '';
        }

        return number_format((float) $amount, 0, ',', '.') . ' ' . $currency;
    }
}

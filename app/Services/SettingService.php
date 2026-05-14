<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class SettingService
{
    private const CACHE_KEY = 'settings.all';
    private const CACHE_TTL = 3600; // 1 saat

    /**
     * Tüm settings'i cache'li olarak döndürür.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Setting::all()->mapWithKeys(function (Setting $s) {
                $value = $s->castedValue();

                if (in_array($s->key, Setting::ENCRYPTED_KEYS, true) && $value !== null && $value !== '') {
                    try {
                        $value = Crypt::decryptString($value);
                    } catch (\Throwable $e) {
                        // Encrypted değil, plain bırak (migration sırasında olabilir).
                    }
                }

                return [$s->key => $value];
            })->all();
        });
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();

        return $all[$key] ?? $default;
    }

    public function set(string $key, mixed $value, ?string $type = null, ?string $group = null): Setting
    {
        $setting = Setting::firstOrNew(['key' => $key]);

        if ($type !== null) {
            $setting->type = $type;
        }
        if ($group !== null) {
            $setting->group = $group;
        }

        $stored = match (true) {
            $value === null => null,
            ($setting->type ?? 'text') === 'json' => json_encode($value, JSON_UNESCAPED_UNICODE),
            ($setting->type ?? 'text') === 'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };

        if (in_array($key, Setting::ENCRYPTED_KEYS, true) && $stored !== null && $stored !== '') {
            $stored = Crypt::encryptString($stored);
        }

        $setting->value = $stored;
        $setting->save();

        $this->forget();

        return $setting;
    }

    public function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}

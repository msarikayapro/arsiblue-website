<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value', 'type', 'group'])]
class Setting extends Model
{
    /**
     * Hassas alanların DB'de encrypted tutulması için.
     * Bkz. spec § 10 "Sensitive Data".
     */
    public const ENCRYPTED_KEYS = [
        'meta_capi_token',
        'tiktok_capi_token',
    ];

    /**
     * Type'a göre uygun PHP değerine çevirir.
     */
    public function castedValue(): mixed
    {
        $value = $this->value;

        if ($value === null || $value === '') {
            return null;
        }

        return match ($this->type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'number' => is_numeric($value) ? $value + 0 : null,
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}

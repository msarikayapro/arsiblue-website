<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'page_slug',
    'title', 'description', 'keywords',
    'og_title', 'og_description', 'og_image',
    'canonical_url', 'robots',
    'schema_json',
])]
class SeoMeta extends Model
{
    public static function forPage(string $slug): ?self
    {
        return static::where('page_slug', $slug)->first();
    }
}

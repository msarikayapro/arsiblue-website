<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'name', 'phone', 'message',
    'landing_page', 'utm_source', 'utm_medium', 'utm_campaign',
    'user_ip', 'user_agent',
    'status', 'notes',
    'event_log_id',
])]
class Lead extends Model
{
    public const STATUSES = ['yeni', 'aranildi', 'sonuclandi', 'iptal'];

    public function eventLog(): BelongsTo
    {
        return $this->belongsTo(EventLog::class);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function createdAtFormatted(): string
    {
        return $this->created_at->diffForHumans();
    }
}

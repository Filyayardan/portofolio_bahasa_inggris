<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $guarded = [];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function getIsUnreadAttribute(): bool
    {
        return $this->read_at === null;
    }

    public function getInitialsAttribute(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->name)) ?: [];

        $initials = '';

        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        }

        return $initials !== '' ? $initials : '?';
    }

    public function getSubjectOrFallbackAttribute(): string
    {
        return $this->subject ?: trans('contact.subject_placeholder');
    }

    public function getExcerptAttribute(): string
    {
        return str()->limit(strip_tags((string) $this->message), 120);
    }
}

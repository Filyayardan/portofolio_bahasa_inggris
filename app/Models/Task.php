<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Task extends Model
{
    protected $guarded = [];

    protected $casts = [
        'featured' => 'boolean',
        'published_at' => 'date',
        'file_size' => 'integer',
        'sort_order' => 'integer',
    ];

    public const TYPES = ['video', 'document', 'text'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at')->orderByDesc('id');
    }

    public function scopePublished($query)
    {
        return $query->where(function ($query) {
            $query->whereNull('published_at')->orWhereDate('published_at', '<=', now());
        });
    }

    public function scopeOfType($query, ?string $type)
    {
        return $type && in_array($type, self::TYPES, true)
            ? $query->where('type', $type)
            : $query;
    }

    public function getTypeLabelAttribute(): string
    {
        return trans('tasks.type_'.$this->type);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail ? asset($this->thumbnail) : null;
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset($this->file_path) : null;
    }

    public function getVideoUrlAttribute(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        if ($this->video_kind === 'file' && ! Str::startsWith($this->video_url, ['http://', 'https://'])) {
            return asset($this->video_url);
        }

        return $this->video_url;
    }

    public function getDateLabelAttribute(): ?string
    {
        return $this->published_at?->translatedFormat('d F Y');
    }

    public function getExcerptAttribute(): string
    {
        return Str::limit((string) $this->description, 150);
    }

    public function getFileInfoAttribute(): ?string
    {
        $parts = array_filter([
            $this->file_extension ? mb_strtoupper($this->file_extension) : null,
            \App\Support\Portfolio::formatBytes($this->file_size),
        ]);

        return $parts ? implode(' • ', $parts) : null;
    }
}

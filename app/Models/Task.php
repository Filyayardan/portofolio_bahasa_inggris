<?php

namespace App\Models;

use App\Support\Portfolio;
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

    public function localized(string $attribute): mixed
    {
        $englishAttribute = $attribute.'_en';

        return app()->getLocale() === 'en' && filled($this->{$englishAttribute} ?? null)
            ? $this->{$englishAttribute}
            : $this->{$attribute};
    }

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
        $videoUrl = $this->attributes['video_url'] ?? null;

        if (! $videoUrl) {
            return null;
        }

        if ($this->video_kind === 'file' && ! Str::startsWith($videoUrl, ['http://', 'https://'])) {
            return asset($videoUrl);
        }

        return $videoUrl;
    }

    public function getDateLabelAttribute(): ?string
    {
        return $this->published_at?->translatedFormat('d F Y');
    }

    public function getExcerptAttribute(): string
    {
        return Str::limit((string) $this->localized('description'), 150);
    }

    public function getFileInfoAttribute(): ?string
    {
        $parts = array_filter([
            $this->file_extension ? mb_strtoupper($this->file_extension) : null,
            Portfolio::formatBytes($this->file_size),
        ]);

        return $parts ? implode(' • ', $parts) : null;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = [];

    protected $casts = [
        'socials' => 'array',
        'years_experience' => 'integer',
        'projects_count' => 'integer',
        'clients_count' => 'integer',
        'works_count' => 'integer',
    ];

    public function localized(string $attribute): mixed
    {
        $englishAttribute = $attribute.'_en';

        return app()->getLocale() === 'en' && filled($this->{$englishAttribute} ?? null)
            ? $this->{$englishAttribute}
            : $this->{$attribute};
    }

    public function photoUrl(): ?string
    {
        return $this->photo ? asset($this->photo) : null;
    }

    public function cvUrl(): ?string
    {
        return $this->cv_path ? asset($this->cv_path) : null;
    }

    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->name)) ?: [];

        $initials = '';

        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        }

        return $initials !== '' ? $initials : 'YD';
    }

    public function heroName(): array
    {
        $parts = preg_split('/\s+/', trim((string) $this->name)) ?: [];
        $first = $parts[0] ?? 'Yardan';
        $rest = trim(implode(' ', array_slice($parts, 1)));

        return [$first, $rest !== '' ? $rest : 'Danz'];
    }

    public function storyParagraphs(): array
    {
        return collect(preg_split('/\n+/', (string) $this->localized('about_story')))
            ->map(fn ($paragraph) => trim($paragraph))
            ->filter()
            ->values()
            ->all();
    }
}

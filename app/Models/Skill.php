<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $guarded = [];

    protected $casts = [
        'level' => 'integer',
        'sort_order' => 'integer',
    ];

    public function localized(string $attribute): mixed
    {
        $englishAttribute = $attribute.'_en';

        return app()->getLocale() === 'en' && filled($this->{$englishAttribute} ?? null)
            ? $this->{$englishAttribute}
            : $this->{$attribute};
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}

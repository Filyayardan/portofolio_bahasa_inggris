<?php

namespace App\Support;

use App\Models\Profile;
use App\Models\Task;

class Portfolio
{
    public static function profile(): Profile
    {
        return Profile::query()->firstOrCreate([], [
            'name' => 'Yardan Danz',
            'role' => 'Web Developer & Visual Designer',
            'tagline' => 'Merancang pengalaman digital yang tenang, terukur, dan berkarakter.',
            'location' => 'Jakarta, Indonesia',
            'email' => 'hello@yardandanz.com',
            'phone' => '+62 812 0000 0000',
            'availability' => 'Terbuka untuk proyek baru',
            'about_lead' => 'Saya percaya karya yang baik lahir dari rasa ingin tahu yang panjang dan perhatian pada detail.',
            'about_story' => "Saya menghabiskan beberapa tahun terakhir membantu brand, institusi, dan individu menerjemahkan ide mereka menjadi pengalaman digital yang terasa utuh.\nDi luar layar, saya suka mengamati tipografi, arsitektur, dan cara orang berinteraksi dengan benda sehari-hari.",
            'focus_text' => 'Membangun pengalaman digital yang bermakna, dari konsep hingga baris kode terakhir.',
            'signature' => 'Yardan Danz',
            'years_experience' => 5,
            'projects_count' => 48,
            'clients_count' => 27,
            'works_count' => 76,
        ]);
    }

    public static function taskPayload(Task $task): array
    {
        $payload = [
            'type' => $task->type,
            'title' => $task->title,
            'description' => $task->description,
            'content' => $task->content,
            'category' => $task->category,
            'date' => $task->date_label,
            'thumbnail' => $task->thumbnail_url,
            'labels' => [
                'type' => $task->type_label,
                'open_file' => trans('tasks.open_file'),
                'download' => trans('tasks.download_file'),
                'file_info' => trans('tasks.file_info'),
                'no_file' => trans('tasks.no_file'),
                'no_media' => trans('tasks.no_media'),
            ],
        ];

        if ($task->type === 'video') {
            $payload['video'] = [
                'kind' => $task->video_kind,
                'url' => $task->video_url,
            ];
        }

        if ($task->type === 'document') {
            $payload['file'] = $task->file_url ? [
                'url' => $task->file_url,
                'name' => $task->file_name ?: basename($task->file_path),
                'extension' => $task->file_extension,
                'size' => self::formatBytes($task->file_size),
            ] : null;
        }

        return $payload;
    }

    public static function formatBytes(?int $bytes): ?string
    {
        if (! $bytes || $bytes <= 0) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $index = min((int) floor(log($bytes, 1024)), count($units) - 1);
        $value = $bytes / (1024 ** $index);

        return number_format($value, $value >= 10 || $index === 0 ? 0 : 1).' '.$units[$index];
    }

    public static function socials(): array
    {
        $socials = self::profile()->socials ?: [];

        return collect($socials)
            ->map(function ($entry, $key) {
                if (is_string($entry)) {
                    return [
                        'network' => strtolower($key),
                        'label' => ucfirst($key),
                        'url' => $entry,
                    ];
                }

                return [
                    'network' => strtolower($entry['network'] ?? $entry['label'] ?? $key),
                    'label' => $entry['label'] ?? ucfirst($entry['network'] ?? $key),
                    'url' => $entry['url'] ?? '#',
                ];
            })
            ->filter(fn ($entry) => ! empty($entry['url']) && $entry['url'] !== '#')
            ->values()
            ->all();
    }

    public static function marquee(): array
    {
        return [
            'Web Development',
            'Visual Design',
            'Creative Direction',
            'Motion & Video',
            'Editorial Layout',
            'Digital Storytelling',
        ];
    }

    public static function allSocialNetworks(): array
    {
        return [
            'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn',
            'github' => 'GitHub',
            'dribbble' => 'Dribbble',
            'behance' => 'Behance',
            'youtube' => 'YouTube',
            'twitter' => 'X / Twitter',
            'tiktok' => 'TikTok',
            'whatsapp' => 'WhatsApp',
            'telegram' => 'Telegram',
            'facebook' => 'Facebook',
        ];
    }

    public static function socialIcon(string $network): string
    {
        $network = strtolower($network);

        return Icons::svg(in_array($network, array_keys(self::allSocialNetworks()), true) ? $network : 'link');
    }
}

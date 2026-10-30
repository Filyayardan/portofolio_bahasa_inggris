<?php

namespace App\Support;

class Icons
{
    public static function svg(string $name, string $class = ''): string
    {
        $paths = self::paths()[$name] ?? self::paths()['spark'];
        $classAttribute = $class !== '' ? ' class="'.e($class).'"' : '';

        return '<svg'.$classAttribute.' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$paths.'</svg>';
    }

    private static function paths(): array
    {
        return [
            'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
            'close' => '<path d="M18 6 6 18M6 6l12 12"/>',
            'arrow-right' => '<path d="M4 12h16M14 6l6 6-6 6"/>',
            'arrow-left' => '<path d="M20 12H4M10 18l-6-6 6-6"/>',
            'arrow-up' => '<path d="M12 20V4M6 10l6-6 6 6"/>',
            'arrow-up-right' => '<path d="M7 17 17 7M8 7h9v9"/>',
            'external' => '<path d="M15 3h6v6M10 14 21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
            'play' => '<path d="M8 5.5v13l11-6.5-11-6.5Z" fill="currentColor" stroke="none"/>',
            'spark' => '<path d="M12 3l2.1 6.4L20 12l-5.9 2.6L12 21l-2.1-6.4L4 12l5.9-2.6z"/>',
            'star' => '<path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1L3.2 9.5l6.1-.9z"/>',
            'check' => '<path d="m5 12 5 5L20 7"/>',
            'check-circle' => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/>',
            'alert' => '<path d="M12 9v4M12 17h.01"/><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/>',
            'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/>',
            'location' => '<path d="M20 10c0 5-8 12-8 12s-8-7-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="2.8"/>',
            'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
            'phone' => '<path d="M5 3h3l2 5-2.5 1.5a12 12 0 0 0 6 6L15 13l5 2v3a2 2 0 0 1-2 2A16 16 0 0 1 3 5a2 2 0 0 1 2-2Z"/>',
            'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
            'globe' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.6 3.8 5.7 3.8 9S14.5 18.4 12 21c-2.5-2.6-3.8-5.7-3.8-9S9.5 5.6 12 3Z"/>',
            'file' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h5"/>',
            'download' => '<path d="M12 3v12M7 10l5 5 5-5M4 21h16"/>',
            'upload' => '<path d="M12 21V9M7 14l5-5 5 5M4 3h16"/>',
            'pen' => '<path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
            'film' => '<rect x="3" y="4" width="18" height="16" rx="1"/><path d="M7 4v16M17 4v16M3 9h4M17 9h4M3 15h4M17 15h4"/>',
            'video' => '<rect x="3" y="5" width="13" height="14" rx="2"/><path d="m16 10 5-3v10l-5-3"/>',
            'text' => '<path d="M4 5h16M4 10h16M4 15h10M4 20h7"/>',
            'image' => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="9" r="1.5"/><path d="m21 15-5-5L5 21"/>',
            'grid' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
            'layers' => '<path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 13 9 5 9-5M3 17l9 5 9-5"/>',
            'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
            'logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5M21 12H9"/>',
            'plus' => '<path d="M12 5v14M5 12h14"/>',
            'edit' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
            'trash' => '<path d="M3 6h18M8 6V4h8v2M6 6l1 15h10l1-15M10 11v6M14 11v6"/>',
            'eye' => '<path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2.6"/>',
            'settings' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.9.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.9l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.9.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.9-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.9V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z"/>',
            'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/>',
            'tag' => '<path d="M20 13 13 20a2 2 0 0 1-2.8 0L3 12.8V3h9.8L20 10.2a2 2 0 0 1 0 2.8Z"/><circle cx="7.5" cy="7.5" r="1.2"/>',
            'chart' => '<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>',
            'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>',
            'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/>',
            'linkedin' => '<path d="M4 9h4v12H4zM6 3a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM12 21V9h4v1.7A4.5 4.5 0 0 1 20 9c2.5 0 4 1.6 4 4.8V21h-4v-6.5c0-1.7-.6-2.7-2-2.7s-2 1-2 2.7V21Z"/>',
            'github' => '<path d="M9 19c-4 1.3-4-2.2-6-2.7M15 21v-3.6a3.1 3.1 0 0 0-.9-2.5c3-.3 6.1-1.5 6.1-6.6a5.2 5.2 0 0 0-1.4-3.6 4.8 4.8 0 0 0-.1-3.6s-1.1-.3-3.7 1.4a12.7 12.7 0 0 0-6.6 0C5.8 1 4.7 1.3 4.7 1.3a4.8 4.8 0 0 0-.1 3.6A5.2 5.2 0 0 0 3.2 8.5c0 5 3.1 6.3 6.1 6.6a3.1 3.1 0 0 0-.9 2.4V21"/>',
            'dribbble' => '<circle cx="12" cy="12" r="9"/><path d="M5 6.5c3.5 4 8 6.5 14 7.5M3.5 14c5-1 9-4 11.5-9M9 21c2-6 6-9 11-9.5"/>',
            'behance' => '<path d="M3 6h5.5a3 3 0 0 1 0 6H3zM3 12h6a3 3 0 0 1 0 6H3zM15 8h6M14 14h8a4 4 0 0 0-8 0 4 4 0 0 0 7.5 2"/>',
            'youtube' => '<rect x="2" y="5" width="20" height="14" rx="4"/><path d="m10 9 5 3-5 3z"/>',
            'twitter' => '<path d="M4 4l7 9-7 7h2.5l5.5-5.7L17 20h3l-7.2-9.3L19.5 4H17l-5 5.2L8 4Z"/>',
            'whatsapp' => '<path d="M12 3a9 9 0 0 0-7.7 13.6L3 21l4.5-1.2A9 9 0 1 0 12 3Z"/><path d="M8.5 8.5c.3 2 2 3.8 4 4.1.7.1 1.4-.4 1.4-1.1 0-.3-.2-.5-.4-.6l-1.1-.5c-.3-.1-.5 0-.7.2l-.4.4a5.8 5.8 0 0 1-2.2-2.2l.4-.4c.2-.2.3-.4.2-.7l-.5-1.1c-.1-.2-.3-.4-.6-.4-.7 0-1.2.7-1.1 1.4Z"/>',
            'telegram' => '<path d="m21 4-3 16-6-4-3 3v-5L18 6l-9 7-5-2z"/>',
            'facebook' => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8.5c0-.3.2-.5.5-.5Z"/>',
            'tiktok' => '<path d="M15 3v2.2a5.2 5.2 0 0 0 5 4V13a8.4 8.4 0 0 1-5-1.6V16a5.5 5.5 0 1 1-5.5-5.5c.3 0 .7 0 1 .1v3.7a2 2 0 1 0 1.5 1.9V3Z"/>',
            'link' => '<path d="M10 13a5 5 0 0 0 7.5.5l2-2A5 5 0 0 0 12.5 4l-1.2 1.2"/><path d="M14 11a5 5 0 0 0-7.5-.5l-2 2A5 5 0 0 0 11.5 20l1.2-1.2"/>',
        ];
    }
}

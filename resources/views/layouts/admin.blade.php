@php
    $profile = \App\Support\Portfolio::profile();
    $profilePhoto = $profile->photoUrl() ?: asset('assets/images/portrait-placeholder.svg');
    $unread = \App\Models\ContactMessage::unread()->count();
    $nav = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'grid'],
        ['route' => 'admin.tasks.index', 'label' => 'Tugas', 'icon' => 'layers'],
        ['route' => 'admin.skills.index', 'label' => 'Keahlian', 'icon' => 'spark'],
        ['route' => 'admin.messages.index', 'label' => 'Pesan Masuk', 'icon' => 'mail', 'badge' => $unread],
        ['route' => 'admin.profile.edit', 'label' => 'Profil Situs', 'icon' => 'user'],
    ];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') — Admin {{ $profile->name }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-sidebar" id="adminSidebar">
            <a class="admin-brand" href="{{ route('admin.dashboard') }}">
                <span class="admin-brand__mark">
                    <img src="{{ $profilePhoto }}" alt="" aria-hidden="true">
                </span>
                <span class="admin-brand__text">
                    <strong>{{ $profile->name }}</strong>
                    <small>Studio Panel</small>
                </span>
            </a>

            <div class="admin-sidebar__workspace">
                <span class="admin-nav-label">Workspace</span>
                <nav class="admin-nav" aria-label="Navigasi admin">
                    @foreach ($nav as $item)
                        <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'is-active' : '' }}">
                            {!! \App\Support\Icons::svg($item['icon']) !!}
                            <span>{{ $item['label'] }}</span>
                            @if (! empty($item['badge']))
                                <span class="admin-nav__badge">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="admin-sidebar__bottom">
                <a class="admin-sidebar__link" href="{{ route('home') }}" target="_blank" rel="noopener">
                    {!! \App\Support\Icons::svg('external') !!}
                    Lihat situs
                </a>

                <div class="admin-mini-profile">
                    <span class="admin-avatar admin-avatar--profile">
                        <img src="{{ $profilePhoto }}" alt="" aria-hidden="true">
                    </span>
                    <div>
                        <strong>{{ $profile->name }}</strong>
                        <small>{{ auth()->user()->email }}</small>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="admin-logout" type="submit" aria-label="Keluar">
                            {!! \App\Support\Icons::svg('logout') !!}
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="admin-overlay" id="adminOverlay" hidden></div>

        <div class="admin-main">
            <header class="admin-topbar">
                <button class="admin-menu-toggle" id="adminMenuToggle" type="button" aria-expanded="false" aria-label="Buka menu">
                    {!! \App\Support\Icons::svg('menu') !!}
                </button>

                <div class="admin-title">
                    <span>@yield('kicker', 'Panel Admin')</span>
                    <h1>@yield('title', 'Dashboard')</h1>
                </div>

                <div class="admin-topbar__actions">
                    <a class="admin-topbar__view" href="{{ route('home') }}" target="_blank" rel="noopener">
                        Lihat situs
                        {!! \App\Support\Icons::svg('arrow-up-right') !!}
                    </a>
                </div>
            </header>

            <main class="admin-content">
                @include('admin.partials.flashes')
                @yield('content')
            </main>

            <footer class="admin-footer">
                <span>&copy; {{ now()->year }} {{ $profile->name }}</span>
                <span>Dibangun dengan Laravel</span>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/js/admin.js') }}" defer></script>
</body>
</html>

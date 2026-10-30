@php $profile = \App\Support\Portfolio::profile(); @endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Masuk — Admin {{ $profile->name }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body class="login-body">
    <main class="login-shell">
        <section class="login-visual">
            <div class="login-visual__grid" aria-hidden="true"></div>
            <a class="login-brand" href="{{ route('home') }}">{{ $profile->initials() }}</a>

            <div class="login-visual__content">
                <span class="admin-kicker">Studio Panel</span>
                <h1>
                    Ruang kerja
                    <em>{{ $profile->name }}</em>
                </h1>
                <p>Kelola karya, keahlian, pesan masuk, dan seluruh isi portofolio dari satu tempat.</p>
            </div>

            <div class="login-visual__footer">
                <span>{{ now()->year }} — {{ $profile->name }}</span>
                <span>Portofolio Kreatif</span>
            </div>
        </section>

        <section class="login-panel">
            <div class="login-panel__inner">
                <a class="login-back" href="{{ route('home') }}">
                    {!! \App\Support\Icons::svg('arrow-left') !!}
                    Kembali ke beranda
                </a>

                <div class="login-head">
                    <span class="admin-kicker">Area Admin</span>
                    <h2>Selamat datang</h2>
                    <p>Masuk untuk melanjutkan mengelola portofolio.</p>
                </div>

                @if ($errors->any())
                    <div class="admin-alert admin-alert--error">
                        {!! \App\Support\Icons::svg('alert') !!}
                        <div>{{ $errors->first() }}</div>
                    </div>
                @endif

                <form class="admin-form login-form" method="POST" action="{{ route('admin.login.attempt') }}">
                    @csrf

                    <div class="admin-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus autocomplete="username">
                    </div>

                    <div class="admin-field">
                        <label for="password">Kata Sandi</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                    </div>

                    <label class="admin-checkbox" style="margin-bottom: 6px">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                        Ingat saya
                    </label>

                    <button class="admin-button admin-button--gold admin-button--full" type="submit">
                        Masuk ke panel
                        {!! \App\Support\Icons::svg('arrow-right') !!}
                    </button>
                </form>

                <p class="login-note">
                    <span class="status-dot" aria-hidden="true"></span>
                    Ruang kerja privat — hanya untuk pemilik situs.
                </p>
            </div>
        </section>
    </main>
</body>
</html>

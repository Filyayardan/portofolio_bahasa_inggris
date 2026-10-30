@php
    $navItems = [
        ['href' => '#about', 'label' => trans('nav.about')],
        ['href' => '#skills', 'label' => trans('nav.skills')],
        ['href' => '#tasks', 'label' => trans('nav.tasks')],
        ['href' => '#contact', 'label' => trans('nav.contact')],
    ];
@endphp

<footer class="site-footer">
    <div class="container">
        <div class="site-footer__top">
            <div class="site-footer__brand">
                <span class="site-footer__mark">{{ $profile->initials() }}</span>
                <div>
                    <strong>{{ $profile->name }}</strong>
                    <span>{{ trans('footer.brand_line') }}</span>
                    <span>{{ $profile->role }}</span>
                </div>
            </div>

            <nav class="site-footer__nav" aria-label="{{ trans('footer.nav') }}">
                <span class="mini-label">{{ trans('footer.nav') }}</span>
                @foreach ($navItems as $item)
                    <a href="{{ $item['href'] }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="site-footer__contact">
                <span class="mini-label">{{ trans('footer.contact') }}</span>
                @if ($profile->email)
                    <a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a>
                @endif
                @if ($profile->phone)
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone) }}">{{ $profile->phone }}</a>
                @endif
                <span>{{ $profile->location }}</span>
            </div>

            <div class="site-footer__clock">
                <span class="mini-label">{{ trans('footer.clock') }}</span>
                <span data-clock>--:--:--</span>
                <span>{{ trans('footer.timezone') }}</span>
            </div>
        </div>

        <div class="site-footer__bottom">
            <span>&copy; {{ now()->year }} {{ $profile->name }}. {{ trans('footer.rights') }}</span>
            <span class="site-footer__crafted">{{ trans('footer.crafted') }}</span>
            <div class="site-footer__bottom-actions">
                <a href="{{ route('admin.dashboard') }}">{{ trans('footer.admin') }}</a>
                <a class="back-to-top" href="#home">
                    {{ trans('footer.top') }}
                    {!! \App\Support\Icons::svg('arrow-up') !!}
                </a>
            </div>
        </div>
    </div>
</footer>

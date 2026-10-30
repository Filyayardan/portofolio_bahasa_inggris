@php
    $locale = app()->getLocale();
    $navItems = [
        ['href' => '#about', 'number' => '01', 'label' => trans('nav.about')],
        ['href' => '#skills', 'number' => '02', 'label' => trans('nav.skills')],
        ['href' => '#tasks', 'number' => '03', 'label' => trans('nav.tasks')],
        ['href' => '#contact', 'number' => '04', 'label' => trans('nav.contact')],
    ];
@endphp

<header class="site-header" id="siteHeader">
    <a class="brand" href="{{ route('home') }}" aria-label="{{ $profile->name }}">
        <span class="brand__mark">
            <img src="{{ $profile->photoUrl() ?: asset('assets/images/portrait-placeholder.svg') }}" alt="" aria-hidden="true">
        </span>
        <span class="brand__text">
            <strong>{{ $profile->name }}</strong>
            <small>{{ trans('brand.small') }}</small>
        </span>
    </a>

    <nav class="desktop-nav" aria-label="{{ trans('footer.nav') }}">
        @foreach ($navItems as $item)
            <a href="{{ $item['href'] }}"><span>{{ $item['number'] }}</span>{{ $item['label'] }}</a>
        @endforeach
    </nav>

    <div class="header__actions">
        <div class="lang-switch" aria-label="Language">
            <a href="{{ route('locale', 'id') }}" class="{{ $locale === 'id' ? 'is-active' : '' }}" hreflang="id">ID</a>
            <span aria-hidden="true"></span>
            <a href="{{ route('locale', 'en') }}" class="{{ $locale === 'en' ? 'is-active' : '' }}" hreflang="en">EN</a>
        </div>

        <a class="header__cta" href="#contact">
            {{ trans('nav.talk') }}
            {!! \App\Support\Icons::svg('arrow-up-right') !!}
        </a>

        <button class="menu-toggle" id="menuToggle" type="button" aria-expanded="false" aria-controls="mobileMenu" aria-label="{{ trans('nav.menu') }}">
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<div class="mobile-menu" id="mobileMenu" aria-hidden="true">
    <div class="mobile-menu__top">
        <button class="mobile-menu__close" id="menuClose" type="button" aria-label="{{ trans('nav.close') }}">
            {!! \App\Support\Icons::svg('close') !!}
        </button>
    </div>

    <nav class="mobile-menu__nav" aria-label="{{ trans('footer.nav') }}">
        @foreach ($navItems as $item)
            <a href="{{ $item['href'] }}"><span>{{ $item['number'] }}</span>{{ $item['label'] }}</a>
        @endforeach
    </nav>

    <div class="mobile-menu__bottom">
        <div class="lang-switch lang-switch--large">
            <a href="{{ route('locale', 'id') }}" class="{{ $locale === 'id' ? 'is-active' : '' }}">ID</a>
            <span aria-hidden="true"></span>
            <a href="{{ route('locale', 'en') }}" class="{{ $locale === 'en' ? 'is-active' : '' }}">EN</a>
        </div>

        @if ($profile->email)
            <a class="mobile-menu__email" href="mailto:{{ $profile->email }}">{{ $profile->email }}</a>
        @endif
    </div>
</div>

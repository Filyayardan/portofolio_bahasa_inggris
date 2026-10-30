@php
    [$firstName, $lastName] = $profile->heroName();
    $photo = $profile->photoUrl() ?: asset('assets/images/portrait-placeholder.svg');
    $badgeText = mb_strtoupper($profile->name.' • '.trans('hero.badge_label').' • ');
    $startYear = now()->year - max(1, (int) $profile->years_experience);
@endphp

<section class="hero" id="home">
    <div class="hero__grid-lines" aria-hidden="true"></div>
    <div class="hero__glow" aria-hidden="true"></div>

    <div class="container hero__inner">
        <div class="hero__copy">
            <div class="eyebrow">
                <span class="eyebrow__line" aria-hidden="true"></span>
                {{ trans('hero.eyebrow') }}
            </div>

            <h1 class="hero__title">
                <span class="hero__title-line">{{ $firstName }}</span>
                <span class="hero__title-line hero__title-line--italic">{{ $lastName }}<span class="hero__title-dot">.</span></span>
            </h1>

            <div class="hero__details">
                <span class="hero__role">{{ $profile->role }}</span>
                <p class="hero__tagline">{{ $profile->tagline }}</p>

                <div class="hero__actions">
                    <a class="button button--gold" href="#tasks">
                        {{ trans('hero.explore') }}
                        {!! \App\Support\Icons::svg('arrow-right') !!}
                    </a>
                    <a class="text-link" href="#about">
                        {{ trans('hero.about') }}
                        {!! \App\Support\Icons::svg('arrow-right') !!}
                    </a>
                </div>

                <div class="hero__availability">
                    <span class="status-dot" aria-hidden="true"></span>
                    <span>{{ $profile->availability ?: trans('hero.available') }}</span>
                    @if ($profile->location)
                        <span class="hero__availability-sep" aria-hidden="true"></span>
                        <span>{{ $profile->location }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="hero__visual">
            <figure class="portrait">
                <span class="portrait__corner portrait__corner--tl" aria-hidden="true"></span>
                <span class="portrait__corner portrait__corner--tr" aria-hidden="true"></span>
                <span class="portrait__corner portrait__corner--bl" aria-hidden="true"></span>
                <span class="portrait__corner portrait__corner--br" aria-hidden="true"></span>

                <span class="portrait__index">{{ $profile->initials() }} — {{ now()->year }}</span>

                <div class="portrait__image">
                    <img src="{{ $photo }}" alt="{{ trans('hero.photo_alt') }}" fetchpriority="high">
                </div>

                <figcaption class="portrait__caption">
                    <span>{{ $profile->role }}</span>
                    <span>{{ trans('hero.badge') }}</span>
                </figcaption>

                <div class="rotating-badge" aria-hidden="true">
                    <svg viewBox="0 0 142 142">
                        <defs>
                            <path id="badgeCircle" d="M71,71 m-54,0 a54,54 0 1,1 108,0 a54,54 0 1,1 -108,0"></path>
                        </defs>
                        <text>
                            <textPath href="#badgeCircle" startOffset="0">{{ $badgeText }}</textPath>
                        </text>
                    </svg>
                    <span class="rotating-badge__center">
                        {!! \App\Support\Icons::svg('spark') !!}
                    </span>
                </div>
            </figure>

            <div class="hero__visual-note">
                <span>{{ trans('hero.since') }} {{ $startYear }}</span>
                <span>{{ trans('hero.index') }}</span>
            </div>
        </div>
    </div>

    <div class="container hero__footer">
        <a class="scroll-cue" href="#about">
            <span class="scroll-cue__line" aria-hidden="true"><span></span></span>
            {{ trans('hero.scroll') }}
        </a>

        <div class="hero__footer-meta">
            <span>{{ trans('hero.location_line') }}</span>
            <span class="hero__footer-sep">•</span>
            <span>{{ now()->year }} {{ trans('brand.small') }}</span>
        </div>
    </div>
</section>

<div class="marquee" aria-hidden="true">
    <div class="marquee__track">
        @for ($i = 0; $i < 2; $i++)
            @foreach ($marquee as $item)
                <span>{{ $item }}</span>
                <i>{!! \App\Support\Icons::svg('spark') !!}</i>
            @endforeach
        @endfor
    </div>
</div>

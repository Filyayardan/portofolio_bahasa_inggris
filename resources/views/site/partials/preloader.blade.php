<div class="preloader" id="preloader" aria-hidden="true">
    <div class="preloader__inner">
        <div class="preloader__mark">
            <img src="{{ $profile->photoUrl() ?: asset('assets/images/portrait-placeholder.svg') }}" alt="" aria-hidden="true">
        </div>
        <div class="preloader__line"></div>
        <span class="preloader__label">{{ trans('brand.small') }}</span>
    </div>
</div>

@php
    $errorsBag = $errors ?? new \Illuminate\Support\ViewErrorBag;
@endphp

<section class="section contact" id="contact">
    <div class="contact__glow" aria-hidden="true"></div>

    <div class="container">
        <div class="contact__grid">
            <div class="contact__text-wrap reveal">
                <span class="section-label">{{ trans('contact.label') }}</span>
                <h2 class="section-title">{{ trans('contact.heading') }}</h2>
                <p class="contact__text">{{ trans('contact.intro') }}</p>

                <div class="contact__details">
                    <div class="contact__detail">
                        <span class="mini-label">{{ trans('contact.direct') }}</span>
                        @if ($profile->email)
                            <a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a>
                        @endif
                        @if ($profile->phone)
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone) }}">{{ $profile->phone }}</a>
                        @endif
                    </div>

                    <div class="contact__detail">
                        <span class="mini-label">{{ trans('contact.social') }}</span>
                        <div class="contact__socials">
                            @forelse ($socials as $social)
                                <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer">
                                    {!! \App\Support\Portfolio::socialIcon($social['network']) !!}
                                    {{ $social['label'] }}
                                </a>
                            @empty
                                <span>{{ trans('contact.response') }}</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="contact__availability">
                    <span class="status-dot" aria-hidden="true"></span>
                    {{ $profile->availability ?: trans('hero.available') }}
                </div>
            </div>

            <div class="contact__form-wrap reveal" style="--delay: 100ms">
                <div class="contact__form-head">
                    <span>{{ trans('contact.form_title') }}</span>
                    <span>{{ trans('contact.form_note') }}</span>
                </div>

                <form class="contact-form" method="POST" action="{{ route('contact.store') }}" novalidate>
                    @csrf
                    <div class="sr-only" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-row">
                        <div class="field {{ $errorsBag->has('name') ? 'has-error' : '' }}">
                            <label for="name">{{ trans('contact.name') }}</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="{{ trans('contact.name_placeholder') }}" required autocomplete="name">
                            @error('name')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="field {{ $errorsBag->has('email') ? 'has-error' : '' }}">
                            <label for="email">{{ trans('contact.email') }}</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="{{ trans('contact.email_placeholder') }}" required autocomplete="email">
                            @error('email')<small>{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="field {{ $errorsBag->has('subject') ? 'has-error' : '' }}">
                        <label for="subject">{{ trans('contact.subject') }}</label>
                        <input type="text" id="subject" name="subject" value="{{ old('subject') }}" placeholder="{{ trans('contact.subject_placeholder') }}">
                        @error('subject')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="field {{ $errorsBag->has('message') ? 'has-error' : '' }}">
                        <label for="message">{{ trans('contact.message') }}</label>
                        <textarea id="message" name="message" rows="5" placeholder="{{ trans('contact.message_placeholder') }}" required>{{ old('message') }}</textarea>
                        @error('message')<small>{{ $message }}</small>@enderror
                    </div>

                    <button class="button button--gold button--full" type="submit">
                        {{ trans('contact.send') }}
                        {!! \App\Support\Icons::svg('arrow-up-right') !!}
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

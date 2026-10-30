@php
    $photo = $profile->photoUrl() ?: asset('assets/images/portrait-placeholder.svg');
    $story = $profile->storyParagraphs();
@endphp

<section class="section about" id="about">
    <div class="container">
        <div class="section-head reveal">
            <span class="section-label">{{ trans('about.label') }}</span>
            <h2 class="section-title">{{ trans('about.heading') }}</h2>
        </div>

        <div class="about__grid">
            <div class="about__visual reveal">
                <figure class="about__figure">
                    <img src="{{ $photo }}" alt="{{ $profile->name }}" loading="lazy">
                    <figcaption>
                        <span>{{ $profile->name }}</span>
                        <span>{{ $profile->location }}</span>
                    </figcaption>
                </figure>

                <div class="about__stamp" aria-hidden="true">
                    <span>{{ $profile->years_experience }}</span>
                    <small>{{ trans('about.years') }}</small>
                </div>
            </div>

            <div class="about__content reveal" style="--delay: 90ms">
                <div class="about__story">
                    <span class="mini-label">{{ trans('about.story') }}</span>
                    <p class="about__lead">{{ $profile->about_lead }}</p>

                    @forelse ($story as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @empty
                        <p>{{ trans('about.focus_text') }}</p>
                    @endforelse
                </div>

                <div class="about__focus">
                    <span class="about__focus-icon">
                        {!! \App\Support\Icons::svg('spark') !!}
                    </span>
                    <div>
                        <span class="mini-label">{{ trans('about.focus') }}</span>
                        <p>{{ $profile->focus_text ?: trans('about.focus_text') }}</p>
                    </div>
                </div>

                <div class="about__meta">
                    <div>
                        <span class="mini-label">{{ trans('about.location') }}</span>
                        <p>{{ $profile->location ?: '—' }}</p>
                    </div>

                    <div>
                        <span class="mini-label">{{ trans('about.email') }}</span>
                        <p>
                            @if ($profile->email)
                                <a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a>
                            @else
                                —
                            @endif
                        </p>
                    </div>

                    <div>
                        <span class="mini-label">{{ trans('about.experience') }}</span>
                        <p>
                            {{ trans('about.years_experience', ['years' => $profile->years_experience]) }}
                            @if ($profile->cvUrl())
                                <br>
                                <a class="inline-link" href="{{ $profile->cvUrl() }}" download>
                                    {{ trans('about.cv') }}
                                    {!! \App\Support\Icons::svg('download') !!}
                                </a>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="about__stats">
                    <div class="stat">
                        <span class="stat__number" data-counter="{{ $profile->years_experience }}">0</span>
                        <span class="stat__label">{{ trans('about.years') }}</span>
                    </div>
                    <div class="stat">
                        <span class="stat__number" data-counter="{{ $profile->projects_count }}">0</span>
                        <span class="stat__label">{{ trans('about.projects') }}</span>
                    </div>
                    <div class="stat">
                        <span class="stat__number" data-counter="{{ $profile->clients_count }}">0</span>
                        <span class="stat__label">{{ trans('about.clients') }}</span>
                    </div>
                </div>

                <div class="about__signature">
                    <div>
                        <span class="mini-label">{{ trans('about.signature') }}</span>
                        <span class="signature">{{ $profile->signature ?: $profile->name }}</span>
                    </div>

                    @if ($socials)
                        <div class="about__socials">
                            @foreach ($socials as $social)
                                <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}">
                                    {!! \App\Support\Portfolio::socialIcon($social['network']) !!}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

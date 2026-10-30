@php $number = 0; @endphp

<section class="section skills" id="skills">
    <div class="container">
        <div class="skills__layout">
            <div class="skills__intro reveal">
                <span class="section-label">{{ trans('skills.label') }}</span>
                <h2 class="section-title">{{ trans('skills.heading') }}</h2>
                <p class="skills__text">{{ trans('skills.intro') }}</p>

                <div class="skills__note">
                    <span class="skills__note-line" aria-hidden="true"></span>
                    {{ trans('skills.note') }}
                </div>
            </div>

            <div class="skill-list" aria-label="{{ trans('skills.list_label') }}">
                @forelse ($skills as $skill)
                    @php $number++; @endphp
                    <article class="skill-row reveal" style="--level: {{ $skill->level }}%; --delay: {{ $loop->index * 55 }}ms">
                        <span class="skill-row__index">{{ str_pad((string) $number, 2, '0', STR_PAD_LEFT) }}</span>

                        <div class="skill-row__content">
                            <div class="skill-row__top">
                                <div>
                                    <h3>{{ $skill->name }}</h3>
                                    <span>{{ $skill->category ?: trans('skills.category') }}</span>
                                </div>
                                <span class="skill-row__percent">{{ $skill->level }}<small>%</small></span>
                            </div>

                            <div class="skill-row__bar">
                                <span></span>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="tasks__empty">
                        {!! \App\Support\Icons::svg('spark') !!}
                        <p>{{ trans('skills.note') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

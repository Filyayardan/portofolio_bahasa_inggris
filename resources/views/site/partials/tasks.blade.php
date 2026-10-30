@php
    $typeIcons = ['video' => 'film', 'document' => 'file', 'text' => 'pen'];
    $filters = [
        ['key' => 'all', 'label' => trans('tasks.all')],
        ['key' => 'video', 'label' => trans('tasks.video')],
        ['key' => 'document', 'label' => trans('tasks.document')],
        ['key' => 'text', 'label' => trans('tasks.text')],
    ];
@endphp

<section class="section tasks" id="tasks">
    <div class="container">
        <div class="tasks__head">
            <div class="reveal">
                <span class="section-label">{{ trans('tasks.label') }}</span>
                <h2 class="section-title">{{ trans('tasks.heading') }}</h2>
            </div>
            <p class="tasks__intro reveal" style="--delay: 90ms">{{ trans('tasks.intro') }}</p>
        </div>

        <div class="tasks__toolbar reveal">
            <div class="filters" role="group" aria-label="{{ trans('tasks.heading') }}">
                @foreach ($filters as $filter)
                    <button
                        class="filter {{ $loop->first ? 'is-active' : '' }}"
                        type="button"
                        data-filter="{{ $filter['key'] }}"
                        aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                    >
                        {{ $filter['label'] }}
                        <span>{{ $counts[$filter['key']] ?? 0 }}</span>
                    </button>
                @endforeach
            </div>

            <div class="tasks__count">
                <span data-task-count>{{ $counts['all'] }}</span>
                {{ trans('tasks.showing') }}
            </div>
        </div>

        <div class="tasks__grid">
            @forelse ($tasks as $task)
                @php
                    $payload = \App\Support\Portfolio::taskPayload($task);
                    $number = str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT);
                    $localizedTitle = $task->localized('title');
                    $localizedCategory = $task->localized('category');
                    $initial = mb_strtoupper(mb_substr($localizedTitle, 0, 1));
                @endphp

                <article class="task-card reveal" data-type="{{ $task->type }}" style="--delay: {{ ($loop->index % 2) * 80 }}ms">
                    <button
                        class="task-card__button"
                        type="button"
                        data-task="{{ json_encode($payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}"
                        aria-label="{{ trans('tasks.open') }}: {{ $localizedTitle }}"
                    >
                        <div class="task-card__media {{ $task->thumbnail ? '' : 'task-card__media--empty' }}">
                            @if ($task->thumbnail)
                                <img src="{{ $task->thumbnail_url }}" alt="{{ $localizedTitle }}" loading="lazy">
                            @else
                                <div class="task-card__abstract"><span>{{ $initial }}</span></div>
                            @endif

                            <span class="task-card__type">
                                {!! \App\Support\Icons::svg($typeIcons[$task->type] ?? 'spark') !!}
                                {{ $task->type_label }}
                            </span>

                            @if ($task->featured)
                                <span class="task-card__featured">
                                    {!! \App\Support\Icons::svg('star') !!}
                                    {{ trans('tasks.featured') }}
                                </span>
                            @endif

                            @if ($task->type === 'video')
                                <span class="task-card__play" aria-hidden="true">
                                    {!! \App\Support\Icons::svg('play') !!}
                                </span>
                            @endif

                            <span class="task-card__number">{{ $number }}</span>
                        </div>

                        <div class="task-card__body">
                            <div class="task-card__meta">
                                <span>{{ $localizedCategory ?: $task->type_label }}</span>
                                <span>{{ $task->date_label }}</span>
                            </div>

                            <h3>{{ $localizedTitle }}</h3>
                            <p>{{ $task->excerpt }}</p>

                            <span class="task-card__link">
                                {{ trans('tasks.view') }}
                                {!! \App\Support\Icons::svg('arrow-up-right') !!}
                            </span>
                        </div>
                    </button>
                </article>
            @empty
                <div class="tasks__empty" style="grid-column: 1 / -1">
                    {!! \App\Support\Icons::svg('layers') !!}
                    <p>{{ trans('tasks.empty') }}</p>
                </div>
            @endforelse
        </div>

        <div class="tasks__empty" id="tasksEmpty" hidden style="margin-top: 28px">
            {!! \App\Support\Icons::svg('layers') !!}
            <p>{{ trans('tasks.empty') }}</p>
        </div>
    </div>
</section>

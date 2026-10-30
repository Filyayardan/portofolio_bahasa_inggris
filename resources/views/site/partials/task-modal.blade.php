<dialog class="task-modal" id="taskModal">
    <div class="task-modal__backdrop" data-close-modal></div>

    <div class="task-modal__panel">
        <button class="task-modal__close" type="button" data-close-modal aria-label="{{ trans('tasks.close') }}">
            {!! \App\Support\Icons::svg('close') !!}
        </button>

        <div class="task-modal__media" id="modalMedia"></div>

        <div class="task-modal__body">
            <div class="task-modal__meta" id="modalMeta"></div>
            <h2 id="modalTitle"></h2>
            <p class="task-modal__description" id="modalDescription"></p>
            <div class="task-modal__extra" id="modalExtra"></div>
        </div>
    </div>
</dialog>

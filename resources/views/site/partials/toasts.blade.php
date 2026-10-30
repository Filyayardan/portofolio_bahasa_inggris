<div class="toast-stack" aria-live="polite">
    @if (session('contact_success'))
        <div class="toast">
            {!! \App\Support\Icons::svg('check-circle') !!}
            <span>{{ session('contact_success') }}</span>
            <button type="button" data-dismiss-toast aria-label="{{ trans('common.close') }}">
                {!! \App\Support\Icons::svg('close') !!}
            </button>
        </div>
    @endif
</div>

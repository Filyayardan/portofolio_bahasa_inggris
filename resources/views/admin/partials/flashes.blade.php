@if (session('success'))
    <div class="admin-alert admin-alert--success">
        {!! \App\Support\Icons::svg('check-circle') !!}
        <div>{{ session('success') }}</div>
        <button type="button" data-dismiss-alert aria-label="Tutup">
            {!! \App\Support\Icons::svg('close') !!}
        </button>
    </div>
@endif

@if (session('error'))
    <div class="admin-alert admin-alert--error">
        {!! \App\Support\Icons::svg('alert') !!}
        <div>{{ session('error') }}</div>
        <button type="button" data-dismiss-alert aria-label="Tutup">
            {!! \App\Support\Icons::svg('close') !!}
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="admin-alert admin-alert--error">
        {!! \App\Support\Icons::svg('alert') !!}
        <div>
            <strong>Periksa kembali data yang dimasukkan.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" data-dismiss-alert aria-label="Tutup">
            {!! \App\Support\Icons::svg('close') !!}
        </button>
    </div>
@endif

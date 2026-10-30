@extends('layouts.admin')

@section('title', 'Pesan Masuk')
@section('kicker', 'Kotak Masuk')

@section('content')
    <div class="admin-page-head">
        <div>
            <span class="admin-page-kicker">Komunikasi</span>
            <h2>Pesan masuk</h2>
            <p>Semua pesan yang dikirim melalui formulir kontak situs publik muncul di sini.</p>
        </div>
        <div class="admin-page-actions">
            <a class="admin-button admin-button--ghost" href="{{ route('admin.messages.index', ['filter' => 'unread']) }}">
                {!! \App\Support\Icons::svg('mail') !!}
                Belum dibaca ({{ $unreadCount }})
            </a>
        </div>
    </div>

    <div class="admin-toolbar">
        <div class="admin-filter-tabs">
            <a href="{{ route('admin.messages.index') }}" class="{{ $filter === 'all' ? 'is-active' : '' }}">
                Semua <span>{{ $total }}</span>
            </a>
            <a href="{{ route('admin.messages.index', ['filter' => 'unread']) }}" class="{{ $filter === 'unread' ? 'is-active' : '' }}">
                Belum dibaca <span>{{ $unreadCount }}</span>
            </a>
        </div>

        <span class="admin-muted">{{ $messages->count() }} pesan ditampilkan</span>
    </div>

    <div class="admin-card admin-inbox-card">
        @if ($messages->isEmpty())
            <div class="admin-empty">
                {!! \App\Support\Icons::svg('mail') !!}
                <h4>Belum ada pesan</h4>
                <p>
                    @if ($filter === 'unread')
                        Semua pesan sudah dibaca. Kerja bagus.
                    @else
                        Pesan dari pengunjung situs akan tampil di sini setelah formulir kontak dikirim.
                    @endif
                </p>
                @if ($filter === 'unread' && $total > 0)
                    <a class="admin-button admin-button--gold admin-button--small" href="{{ route('admin.messages.index') }}">Lihat semua pesan</a>
                @endif
            </div>
        @else
            <div class="admin-inbox">
                @foreach ($messages as $message)
                    <article class="admin-message-row {{ $message->is_unread ? 'is-unread' : '' }}">
                        <a class="admin-message-row__link" href="{{ route('admin.messages.show', $message) }}">
                            <span class="admin-avatar">{{ $message->initials }}</span>

                            <div class="admin-message-row__body">
                                <div class="admin-message-row__top">
                                    <strong>{{ $message->name }}</strong>
                                    <time datetime="{{ $message->created_at->toIso8601String() }}">
                                        {{ $message->created_at->locale(app()->getLocale())->diffForHumans() }}
                                    </time>
                                </div>

                                <span class="admin-message-row__subject">{{ $message->subject_or_fallback }}</span>
                                <p>{{ $message->excerpt }}</p>
                            </div>

                            @if ($message->is_unread)
                                <span class="admin-unread-dot" aria-label="Belum dibaca"></span>
                            @endif
                        </a>

                        <form class="admin-message-row__delete" method="POST" action="{{ route('admin.messages.destroy', $message) }}" data-confirm="Hapus pesan dari “{{ $message->name }}”? Tindakan ini tidak dapat dibatalkan.">
                            @csrf
                            @method('DELETE')
                            <button class="admin-icon-button admin-icon-button--danger" type="submit" aria-label="Hapus pesan dari {{ $message->name }}">
                                {!! \App\Support\Icons::svg('trash') !!}
                            </button>
                        </form>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
@endsection

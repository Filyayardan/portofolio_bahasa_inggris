@extends('layouts.admin')

@section('title', 'Detail Pesan')
@section('kicker', 'Kotak Masuk')

@section('content')
    <div class="admin-page-head">
        <div>
            <a class="admin-back-link" href="{{ route('admin.messages.index') }}">
                {!! \App\Support\Icons::svg('arrow-left') !!}
                Kembali ke kotak masuk
            </a>
            <h2>{{ $message->subject_or_fallback }}</h2>
            <p>Dari {{ $message->name }} — {{ $message->created_at->translatedFormat('d F Y') }}</p>
        </div>
        <div class="admin-page-actions">
            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" data-confirm="Hapus pesan dari “{{ $message->name }}”? Tindakan ini tidak dapat dibatalkan.">
                @csrf
                @method('DELETE')
                <button class="admin-button admin-button--danger" type="submit">
                    {!! \App\Support\Icons::svg('trash') !!}
                    Hapus pesan
                </button>
            </form>
        </div>
    </div>

    <div class="admin-message-layout">
        <article class="admin-card admin-message-detail">
            <div class="admin-message-detail__head">
                <span class="admin-avatar admin-message-avatar">{{ $message->initials }}</span>

                <div class="admin-message-detail__meta">
                    <strong>{{ $message->name }}</strong>
                    <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                </div>

                <time datetime="{{ $message->created_at->toIso8601String() }}">
                    {{ $message->created_at->translatedFormat('d F Y') }}
                    <span>{{ $message->created_at->format('H:i') }} WIB</span>
                </time>
            </div>

            <div class="admin-message-detail__subject">
                <span>Subjek</span>
                <h3>{{ $message->subject_or_fallback }}</h3>
            </div>

            <div class="admin-message-detail__body">{!! nl2br(e($message->message)) !!}</div>
        </article>

        <aside class="admin-message-side">
            <section class="admin-card">
                <div class="admin-card__head">
                    <div>
                        <span class="admin-card__kicker">Tindakan</span>
                        <h3>Kelola pesan</h3>
                    </div>
                </div>

                <div class="admin-message-actions">
                    <a class="admin-button admin-button--gold admin-button--full" href="mailto:{{ $message->email }}?subject={{ urlencode('Re: '.$message->subject_or_fallback) }}">
                        {!! \App\Support\Icons::svg('arrow-up-right') !!}
                        Balas via email
                    </a>

                    @if ($message->is_unread)
                        <form method="POST" action="{{ route('admin.messages.read', $message) }}">
                            @csrf
                            <button class="admin-button admin-button--ghost admin-button--full" type="submit">
                                {!! \App\Support\Icons::svg('check-circle') !!}
                                Tandai sudah dibaca
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.messages.unread', $message) }}">
                            @csrf
                            <button class="admin-button admin-button--ghost admin-button--full" type="submit">
                                {!! \App\Support\Icons::svg('mail') !!}
                                Tandai belum dibaca
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" data-confirm="Hapus pesan dari “{{ $message->name }}”? Tindakan ini tidak dapat dibatalkan.">
                        @csrf
                        @method('DELETE')
                        <button class="admin-button admin-button--danger admin-button--full" type="submit">
                            {!! \App\Support\Icons::svg('trash') !!}
                            Hapus pesan
                        </button>
                    </form>

                    <a class="admin-button admin-button--ghost admin-button--full" href="{{ route('admin.messages.index') }}">
                        {!! \App\Support\Icons::svg('arrow-left') !!}
                        Kembali ke kotak masuk
                    </a>
                </div>
            </section>

            <section class="admin-card admin-message-summary">
                <span class="admin-card__kicker">Ringkasan</span>
                <strong>{{ $unreadCount }}</strong>
                <p>pesan belum dibaca dari {{ $total }} total</p>

                <div class="admin-account-meta">
                    <div>
                        <span>Status</span>
                        <strong>{{ $message->is_unread ? 'Belum dibaca' : 'Sudah dibaca' }}</strong>
                    </div>
                    <div>
                        <span>Diterima</span>
                        <strong>
                            {{ $message->created_at->translatedFormat('d M Y') }}
                            <small>{{ $message->created_at->format('H:i') }} WIB</small>
                        </strong>
                    </div>
                    <div>
                        <span>Email</span>
                        <strong>
                            <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                        </strong>
                    </div>
                </div>
            </section>
        </aside>
    </div>
@endsection

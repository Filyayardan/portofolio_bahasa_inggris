@extends('layouts.admin')

@section('title', 'Keahlian')
@section('kicker', 'Manajemen Konten')

@section('content')
    <div class="admin-page-head">
        <div>
            <span class="admin-page-kicker">Daftar kemampuan</span>
            <h2>Keahlian</h2>
            <p>Atur kemampuan yang tampil pada bagian keahlian di situs publik, lengkap dengan tingkat penguasaannya.</p>
        </div>
        <div class="admin-page-actions">
            <a class="admin-button admin-button--gold" href="{{ route('admin.skills.create') }}">
                {!! \App\Support\Icons::svg('plus') !!}
                Tambah keahlian
            </a>
        </div>
    </div>

    <div class="admin-skill-summary">
        <div>
            <span>Total keahlian</span>
            <strong>{{ $skills->count() }}</strong>
        </div>
        <div>
            <span>Rata-rata</span>
            <strong>{{ $avgLevel }}%</strong>
        </div>
        <div>
            <span>Kategori</span>
            <strong>{{ $categories }}</strong>
        </div>
        <div class="admin-skill-summary__note">
            {!! \App\Support\Icons::svg('spark') !!}
            <p>
                @if ($topSkill)
                    Keahlian tertinggi saat ini adalah <strong>{{ $topSkill->name }}</strong> dengan penguasaan {{ $topSkill->level }}%.
                @else
                    Belum ada keahlian. Tambahkan kemampuan pertama untuk mengisi bagian keahlian pada situs.
                @endif
            </p>
        </div>
    </div>

    <div class="admin-card" style="padding: 4px 26px">
        @if ($skills->isEmpty())
            <div class="admin-empty">
                {!! \App\Support\Icons::svg('spark') !!}
                <h4>Belum ada keahlian</h4>
                <p>Tambahkan kemampuan seperti analisis data, desain, atau pengembangan web.</p>
                <a class="admin-button admin-button--gold admin-button--small" href="{{ route('admin.skills.create') }}">Tambah keahlian</a>
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Keahlian</th>
                            <th>Kategori</th>
                            <th>Tingkat</th>
                            <th>Urutan</th>
                            <th class="admin-table__actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skills as $skill)
                            <tr>
                                <td>
                                    <div class="admin-skill-name">
                                        <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                        <strong>{{ $skill->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $skill->category ?: '—' }}</td>
                                <td class="admin-skill-level-cell">
                                    <div class="admin-skill-level">
                                        <span class="admin-skill-level__bar"><i style="width: {{ $skill->level }}%"></i></span>
                                        <b>{{ $skill->level }}%</b>
                                    </div>
                                </td>
                                <td>{{ $skill->sort_order }}</td>
                                <td class="admin-table__actions">
                                    <div class="admin-row-actions">
                                        <a class="admin-icon-button" href="{{ route('admin.skills.edit', $skill) }}" aria-label="Ubah {{ $skill->name }}">
                                            {!! \App\Support\Icons::svg('edit') !!}
                                        </a>
                                        <form method="POST" action="{{ route('admin.skills.destroy', $skill) }}" data-confirm="Hapus keahlian “{{ $skill->name }}”?">
                                            @csrf
                                            @method('DELETE')
                                            <button class="admin-icon-button admin-icon-button--danger" type="submit" aria-label="Hapus {{ $skill->name }}">
                                                {!! \App\Support\Icons::svg('trash') !!}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

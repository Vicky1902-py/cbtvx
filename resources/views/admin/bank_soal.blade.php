@extends('layouts.admin')

@section('title', 'Bank Soal')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Bank Soal Asesmen</h2>
        <p class="page-subtitle">Daftar paket soal berstandar TKA / Pusmendik (Pilihan Ganda, Benar/Salah, dan Uraian).</p>
    </div>
</div>

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 40px;">No.</th>
                <th>Kode Bank</th>
                <th>Mata Pelajaran</th>
                <th>Tingkat</th>
                <th>Jumlah Butir Soal</th>
                <th>Bobot Maksimal</th>
                <th>KKM</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bankList as $idx => $b)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td><strong>{{ $b->kode_bank }}</strong></td>
                <td>{{ $b->mataPelajaran->nama_mapel ?? '-' }}</td>
                <td><span class="badge badge-neutral">{{ $b->tingkat }}</span></td>
                <td>
                    <strong>{{ $b->butirSoal->count() }} Butir</strong>
                    <span style="font-size: 11px; color: #64748b;">(PG: {{ $b->jumlah_soal_pg }})</span>
                </td>
                <td><strong>{{ number_format($b->bobot_pg + $b->bobot_uraian, 0) }} Poin</strong></td>
                <td>{{ number_format($b->kkm, 0) }}</td>
                <td>
                    @if ($b->status === 'aktif')
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-neutral">{{ ucfirst($b->status) }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 24px; color: #64748b;">
                    Belum ada bank soal.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

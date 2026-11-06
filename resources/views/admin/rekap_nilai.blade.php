@extends('layouts.admin')

@section('title', 'Rekap Nilai - ' . $jadwal->nama_ujian)

@section('content')
<div class="page-header">
    <div>
        <div style="font-size: 11px; color: var(--accent); font-weight: 700; text-transform: uppercase;">Rekapitulasi Nilai Asesmen</div>
        <h2 class="page-title">{{ $jadwal->nama_ujian }}</h2>
        <p class="page-subtitle">Mata Pelajaran: {{ $jadwal->bankSoal->mataPelajaran->nama_mapel ?? '-' }} &bull; Standar KKM: {{ $jadwal->bankSoal->kkm ?? 75 }} Poin</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.jadwal') }}" class="btn btn-secondary btn-sm">&larr; Kembali ke Jadwal</a>
        <a href="{{ route('admin.export_nilai', $jadwal->id) }}" class="btn btn-primary btn-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
            Ekspor Rekap ke XLSX
        </a>
    </div>
</div>

@php
    $kkm = $jadwal->bankSoal->kkm ?? 75;
    $totalPeserta = $pesertaList->count();
    $scores = $pesertaList->pluck('total_nilai')->filter();
    $avgScore = $scores->count() > 0 ? round($scores->avg(), 1) : 0;
    $maxScore = $scores->count() > 0 ? $scores->max() : 0;
    $minScore = $scores->count() > 0 ? $scores->min() : 0;
    $tuntasCount = $pesertaList->where('total_nilai', '>=', $kkm)->count();
    $tuntasPercent = $totalPeserta > 0 ? round(($tuntasCount / $totalPeserta) * 100) : 0;
@endphp

<!-- Ringkasan Statistik Nilai Realtime -->
<div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 20px;">
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px;">
        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Peserta Selesai</div>
        <div style="font-size: 22px; font-weight: 700; color: var(--primary); margin-top: 2px;">{{ $totalPeserta }}</div>
        <div style="font-size: 11px; color: #64748b;">Orang Siswa</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px;">
        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Rata-Rata Nilai</div>
        <div style="font-size: 22px; font-weight: 700; color: #0284c7; margin-top: 2px;">{{ $avgScore }}</div>
        <div style="font-size: 11px; color: #64748b;">Standar KKM: {{ $kkm }}</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px;">
        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Nilai Tertinggi</div>
        <div style="font-size: 22px; font-weight: 700; color: var(--success); margin-top: 2px;">{{ $maxScore }}</div>
        <div style="font-size: 11px; color: #64748b;">Peringkat 1</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px;">
        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Nilai Terendah</div>
        <div style="font-size: 22px; font-weight: 700; color: var(--danger); margin-top: 2px;">{{ $minScore }}</div>
        <div style="font-size: 11px; color: #64748b;">Perlu Remedial</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px;">
        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Ketuntasan KKM</div>
        <div style="font-size: 22px; font-weight: 700; color: #0f2b48; margin-top: 2px;">{{ $tuntasPercent }}%</div>
        <div style="font-size: 11px; color: #64748b;">{{ $tuntasCount }} dari {{ $totalPeserta }} Tuntas</div>
    </div>
</div>

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 40px;">Rank</th>
                <th>Nomor Peserta</th>
                <th>NISN</th>
                <th>Nama Lengkap Peserta</th>
                <th>Kelas / Rombel</th>
                <th>Benar</th>
                <th>Salah</th>
                <th>Kosong</th>
                <th>Skor PG/BS</th>
                <th>Skor Uraian</th>
                <th>Nilai Akhir</th>
                <th>Keterangan KKM</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesertaList as $idx => $p)
            @php
                $isTuntas = $p->total_nilai >= $kkm;
            @endphp
            <tr>
                <td><strong>#{{ $idx + 1 }}</strong></td>
                <td><code>{{ $p->siswa->nomor_peserta ?? '-' }}</code></td>
                <td>{{ $p->siswa->nisn ?? '-' }}</td>
                <td><strong style="color: var(--text-main);">{{ $p->siswa->nama_lengkap ?? '-' }}</strong></td>
                <td><span class="badge badge-neutral">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</span></td>
                <td><span style="color: var(--success); font-weight: 600;">{{ $p->jawaban_benar }}</span></td>
                <td><span style="color: var(--danger); font-weight: 600;">{{ $p->jawaban_salah }}</span></td>
                <td><span style="color: #64748b;">{{ $p->jawaban_kosong }}</span></td>
                <td>{{ number_format($p->nilai_pg, 1) }}</td>
                <td>{{ number_format($p->nilai_uraian, 1) }}</td>
                <td>
                    <strong style="font-size: 14px; color: {{ $isTuntas ? 'var(--success)' : 'var(--danger)' }}">
                        {{ number_format($p->total_nilai, 1) }}
                    </strong>
                    <span style="font-size: 11px; color: #64748b;">/ 100</span>
                </td>
                <td>
                    @if ($isTuntas)
                        <span class="badge badge-success">TUNTAS KKM</span>
                    @else
                        <span class="badge badge-danger">BELUM TUNTAS</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12" style="text-align: center; padding: 32px; color: #64748b;">
                    Belum ada nilai peserta ujian yang tersimpan pada jadwal ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

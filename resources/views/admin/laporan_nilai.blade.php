@extends('layouts.admin')

@section('title', 'Laporan Hasil Ujian & Rekap Nilai')

@section('content')
<div class="page-header">
    <div>
        <div style="font-size: 11px; color: var(--accent); font-weight: 700; text-transform: uppercase;">Modul Laporan & Penilaian Terpadu</div>
        <h2 class="page-title">Laporan Hasil Ujian & Rekap Nilai</h2>
        <p class="page-subtitle">Rekapitulasi lengkap nilai peserta asesmen, analisis ketuntasan KKM, cetak dokumen resmi A4, dan ekspor XLSX.</p>
    </div>
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        @if ($selectedJadwalId)
            <a href="{{ route('admin.laporan.nilai.cetak', ['jadwal_id' => $selectedJadwalId, 'kelas_id' => $selectedKelasId, 'status_kelulusan' => $statusKelulusan]) }}" target="_blank" class="btn btn-primary btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Rekap Nilai A4
            </a>
            <a href="{{ route('admin.laporan.nilai.export_xlsx', ['jadwal_id' => $selectedJadwalId, 'kelas_id' => $selectedKelasId, 'status_kelulusan' => $statusKelulusan]) }}" class="btn btn-secondary btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                Ekspor Excel (XLSX)
            </a>
        @endif
    </div>
</div>

<!-- Filter Panel -->
<div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 16px; margin-bottom: 20px;">
    <form action="{{ route('admin.laporan.nilai') }}" method="GET" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 2; min-width: 250px;">
            <label style="display: block; font-size: 11.5px; font-weight: 700; color: #475569; margin-bottom: 5px; text-transform: uppercase;">Pilih Jadwal Ujian / Mata Pelajaran *</label>
            <select name="jadwal_id" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;">
                <option value="">-- Pilih Jadwal Asesmen --</option>
                @foreach ($jadwalList as $j)
                    <option value="{{ $j->id }}" {{ $selectedJadwalId == $j->id ? 'selected' : '' }}>
                        {{ $j->nama_ujian }} &bull; {{ $j->bankSoal->mataPelajaran->nama_mapel ?? 'Mapel Umum' }} (KKM: {{ $j->bankSoal->kkm ?? 75 }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="flex: 1; min-width: 180px;">
            <label style="display: block; font-size: 11.5px; font-weight: 700; color: #475569; margin-bottom: 5px; text-transform: uppercase;">Filter Kelas / Rombel</label>
            <select name="kelas_id" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                <option value="">-- Semua Kelas --</option>
                @foreach ($kelasList as $k)
                    <option value="{{ $k->id }}" {{ $selectedKelasId == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kelas }} ({{ $k->jurusan }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="flex: 1; min-width: 160px;">
            <label style="display: block; font-size: 11.5px; font-weight: 700; color: #475569; margin-bottom: 5px; text-transform: uppercase;">Status Kelulusan</label>
            <select name="status_kelulusan" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                <option value="">-- Semua Status --</option>
                <option value="tuntas" {{ $statusKelulusan == 'tuntas' ? 'selected' : '' }}>Tuntas (&ge; KKM)</option>
                <option value="belum_tuntas" {{ $statusKelulusan == 'belum_tuntas' ? 'selected' : '' }}>Belum Tuntas (&lt; KKM)</option>
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-secondary" style="padding: 8px 14px;">Terapkan Filter</button>
        </div>
    </form>
</div>

@if ($selectedJadwal)
<!-- Statistik Ringkas Panel -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 20px;">
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px 18px; border-left: 4px solid var(--primary);">
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Total Peserta</div>
        <div style="font-size: 24px; font-weight: 800; color: var(--primary); margin-top: 4px;">{{ $stats['total'] }} <span style="font-size: 12px; font-weight: 500; color: #64748b;">Siswa</span></div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px 18px; border-left: 4px solid #16a34a;">
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Nilai Tertinggi</div>
        <div style="font-size: 24px; font-weight: 800; color: #16a34a; margin-top: 4px;">{{ $stats['tertinggi'] }}</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px 18px; border-left: 4px solid #dc2626;">
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Nilai Terendah</div>
        <div style="font-size: 24px; font-weight: 800; color: #dc2626; margin-top: 4px;">{{ $stats['terendah'] }}</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px 18px; border-left: 4px solid var(--accent);">
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Rata-Rata Nilai</div>
        <div style="font-size: 24px; font-weight: 800; color: var(--accent); margin-top: 4px;">{{ $stats['rata_rata'] }}</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px 18px; border-left: 4px solid #0284c7;">
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Ketuntasan Klasikal (KKM: {{ $stats['kkm'] }})</div>
        <div style="font-size: 24px; font-weight: 800; color: #0284c7; margin-top: 4px;">{{ $stats['persen_tuntas'] }}% <span style="font-size: 12px; font-weight: 500; color: #64748b;">({{ $stats['tuntas'] }}/{{ $stats['total'] }})</span></div>
    </div>
</div>

<!-- Tabel Laporan Nilai -->
<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 45px; text-align: center;">Peringkat</th>
                <th>Nomor Peserta</th>
                <th>NISN</th>
                <th>Nama Lengkap Siswa</th>
                <th>Kelas / Rombel</th>
                <th style="text-align: center;">Benar</th>
                <th style="text-align: center;">Salah</th>
                <th style="text-align: center;">Kosong</th>
                <th style="text-align: right;">Skor PG</th>
                <th style="text-align: right;">Skor Uraian</th>
                <th style="text-align: right;">Nilai Akhir</th>
                <th style="text-align: center;">Status KKM</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesertaList as $idx => $p)
            @php
                $isTuntas = $p->total_nilai >= $stats['kkm'];
            @endphp
            <tr>
                <td style="text-align: center; font-weight: 700; color: {{ $idx < 3 ? 'var(--accent)' : 'inherit' }};">
                    {{ $idx + 1 }}
                </td>
                <td><code>{{ $p->siswa->nomor_peserta ?? '-' }}</code></td>
                <td>{{ $p->siswa->nisn }}</td>
                <td>
                    <strong style="color: var(--primary);">{{ $p->siswa->nama_lengkap ?? '-' }}</strong>
                </td>
                <td>
                    <span class="badge badge-neutral">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</span>
                </td>
                <td style="text-align: center; color: #16a34a; font-weight: 600;">{{ $p->jawaban_benar }}</td>
                <td style="text-align: center; color: #dc2626; font-weight: 600;">{{ $p->jawaban_salah }}</td>
                <td style="text-align: center; color: #64748b;">{{ $p->jawaban_kosong }}</td>
                <td style="text-align: right; font-family: monospace;">{{ number_format($p->nilai_pg, 2) }}</td>
                <td style="text-align: right; font-family: monospace;">{{ number_format($p->nilai_uraian, 2) }}</td>
                <td style="text-align: right;">
                    <span style="font-size: 14px; font-weight: 800; color: {{ $isTuntas ? '#16a34a' : '#dc2626' }};">
                        {{ number_format($p->total_nilai, 2) }}
                    </span>
                </td>
                <td style="text-align: center;">
                    @if ($isTuntas)
                        <span class="badge badge-success" style="font-weight: 700;">TUNTAS</span>
                    @else
                        <span class="badge badge-danger" style="font-weight: 700;">BELUM TUNTAS</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12" style="text-align: center; padding: 32px; color: #64748b;">
                    Tidak ada data peserta ujian yang memenuhi kriteria filter.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@else
<div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 40px; text-align: center; color: #64748b;">
    <p style="font-size: 15px; font-weight: 600;">Silakan pilih Jadwal Ujian di atas untuk melihat laporan nilai.</p>
</div>
@endif

@endsection

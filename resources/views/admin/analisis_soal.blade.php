@extends('layouts.admin')

@section('title', 'Analisis Butir Soal Asesmen')

@section('content')
<div class="page-header">
    <div>
        <div style="font-size: 11px; color: var(--accent); font-weight: 700; text-transform: uppercase;">Evaluasi & Validasi Instrumen Asesmen</div>
        <h2 class="page-title">Analisis Butir Soal (Item Analysis)</h2>
        <p class="page-subtitle">Uji kualitas butir soal: Tingkat Kesukaran (P), Daya Pembeda (D), efektivitas opsi pengecoh, serta rekomendasi perbaikan instrumen.</p>
    </div>
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        @if ($selectedJadwalId && count($analisisItems) > 0)
            <a href="{{ route('admin.laporan.analisis_soal.cetak', ['jadwal_id' => $selectedJadwalId]) }}" target="_blank" class="btn btn-primary btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Analisis A4
            </a>
            <a href="{{ route('admin.laporan.analisis_soal.export_xlsx', ['jadwal_id' => $selectedJadwalId]) }}" class="btn btn-secondary btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                Ekspor Excel (XLSX)
            </a>
        @endif
    </div>
</div>

<!-- Filter Form -->
<div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 16px; margin-bottom: 20px;">
    <form action="{{ route('admin.laporan.analisis_soal') }}" method="GET" style="display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <label style="display: block; font-size: 11.5px; font-weight: 700; color: #475569; margin-bottom: 5px; text-transform: uppercase;">Pilih Paket Jadwal Ujian *</label>
            <select name="jadwal_id" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;">
                <option value="">-- Pilih Jadwal Asesmen --</option>
                @foreach ($jadwalList as $j)
                    <option value="{{ $j->id }}" {{ $selectedJadwalId == $j->id ? 'selected' : '' }}>
                        {{ $j->nama_ujian }} &bull; {{ $j->bankSoal->mataPelajaran->nama_mapel ?? 'Mapel' }} ({{ $j->bankSoal->kode_bank ?? '-' }})
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-secondary" style="padding: 8px 14px;">Muat Analisis</button>
        </div>
    </form>
</div>

@if ($selectedJadwal)
<!-- Parameter Penjelasan -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px;">
    <div style="background: #f8fafc; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 12px 16px; font-size: 12px; line-height: 1.5;">
        <strong style="color: var(--primary);">Standar Tingkat Kesukaran (P):</strong><br>
        &bull; <code>P &ge; 0.70</code> : <span class="badge badge-success" style="font-size: 10px;">Mudah</span><br>
        &bull; <code>0.30 &le; P &lt; 0.70</code> : <span class="badge badge-info" style="font-size: 10px;">Sedang (Ideal)</span><br>
        &bull; <code>P &lt; 0.30</code> : <span class="badge badge-danger" style="font-size: 10px;">Sukar</span>
    </div>
    <div style="background: #f8fafc; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 12px 16px; font-size: 12px; line-height: 1.5;">
        <strong style="color: var(--primary);">Standar Daya Pembeda (D):</strong><br>
        &bull; <code>D &ge; 0.40</code> : <span class="badge badge-success" style="font-size: 10px;">Sangat Baik</span> (Diterima)<br>
        &bull; <code>0.30 &le; D &lt; 0.40</code> : <span class="badge badge-info" style="font-size: 10px;">Baik</span> (Diterima)<br>
        &bull; <code>0.20 &le; D &lt; 0.30</code> : <span class="badge badge-warning" style="font-size: 10px;">Cukup</span> (Perlu Revisi)<br>
        &bull; <code>D &lt; 0.20</code> : <span class="badge badge-danger" style="font-size: 10px;">Kurang</span> (Ditolak / Ganti)
    </div>
</div>

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No.</th>
                <th style="width: 65px; text-align: center;">Tipe</th>
                <th style="width: 50px; text-align: center;">Kunci</th>
                <th style="text-align: center;">Benar</th>
                <th style="text-align: center;">Salah</th>
                <th style="text-align: center;">Kosong</th>
                <th style="text-align: center;">Distribusi Opsi (A / B / C / D / E)</th>
                <th style="text-align: center;">Tingkat Kesukaran (P)</th>
                <th style="text-align: center;">Daya Pembeda (D)</th>
                <th style="text-align: center;">Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($analisisItems as $item)
            <tr>
                <td style="text-align: center; font-weight: 700;">{{ $item['soal']->nomor_urut }}</td>
                <td style="text-align: center;">
                    <span class="badge badge-neutral" style="font-size: 10px;">{{ strtoupper($item['soal']->tipe_soal) }}</span>
                </td>
                <td style="text-align: center;">
                    <strong style="color: var(--primary); font-size: 13px;">{{ $item['soal']->kunci_jawaban }}</strong>
                </td>
                <td style="text-align: center; color: #16a34a; font-weight: 700;">{{ $item['benar'] }}</td>
                <td style="text-align: center; color: #dc2626; font-weight: 700;">{{ $item['salah'] }}</td>
                <td style="text-align: center; color: #64748b;">{{ $item['kosong'] }}</td>
                <td style="text-align: center; font-family: monospace; font-size: 11px;">
                    <span style="{{ $item['soal']->kunci_jawaban == 'A' ? 'font-weight: 800; color: #16a34a;' : '' }}">A: {{ $item['opsi']['A'] }}</span> |
                    <span style="{{ $item['soal']->kunci_jawaban == 'B' ? 'font-weight: 800; color: #16a34a;' : '' }}">B: {{ $item['opsi']['B'] }}</span> |
                    <span style="{{ $item['soal']->kunci_jawaban == 'C' ? 'font-weight: 800; color: #16a34a;' : '' }}">C: {{ $item['opsi']['C'] }}</span> |
                    <span style="{{ $item['soal']->kunci_jawaban == 'D' ? 'font-weight: 800; color: #16a34a;' : '' }}">D: {{ $item['opsi']['D'] }}</span> |
                    <span style="{{ $item['soal']->kunci_jawaban == 'E' ? 'font-weight: 800; color: #16a34a;' : '' }}">E: {{ $item['opsi']['E'] }}</span>
                </td>
                <td style="text-align: center;">
                    <div style="font-weight: 700;">{{ $item['p_index'] }}</div>
                    <span class="badge {{ $item['badge_kesukaran'] }}" style="font-size: 10px;">{{ $item['kategori_kesukaran'] }}</span>
                </td>
                <td style="text-align: center;">
                    <div style="font-weight: 700;">{{ $item['d_index'] }}</div>
                    <span class="badge {{ $item['badge_pembeda'] }}" style="font-size: 10px;">{{ $item['kategori_pembeda'] }}</span>
                </td>
                <td style="text-align: center;">
                    <strong style="color: {{ str_contains($item['rekomendasi'], 'Diterima') ? '#16a34a' : (str_contains($item['rekomendasi'], 'Revisi') ? '#d97706' : '#dc2626') }}; font-size: 12px;">
                        {{ $item['rekomendasi'] }}
                    </strong>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 30px; color: #64748b;">
                    Belum ada butir soal atau peserta yang menyelesaikan tes pada jadwal ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@else
<div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 40px; text-align: center; color: #64748b;">
    <p style="font-size: 15px; font-weight: 600;">Silakan pilih Jadwal Ujian di atas untuk memulai analisis butir soal.</p>
</div>
@endif

@endsection

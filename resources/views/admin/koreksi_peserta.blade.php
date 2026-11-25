@extends('layouts.admin')

@section('title', 'Koreksi & Detail Jawaban - ' . ($peserta->siswa->nama_lengkap ?? 'Peserta'))

@section('content')
<div class="page-header">
    <div>
        <div style="font-size: 11px; color: var(--accent); font-weight: 700; text-transform: uppercase;">Koreksi Jawaban &amp; Nilai Uraian</div>
        <h2 class="page-title">{{ $peserta->siswa->nama_lengkap ?? '-' }} ({{ $peserta->siswa->kelas->nama_kelas ?? '-' }})</h2>
        <p class="page-subtitle">
            Ujian: <strong>{{ $peserta->jadwalUjian->nama_ujian ?? '-' }}</strong> &bull;
            NISN: <code>{{ $peserta->siswa->nisn ?? '-' }}</code> &bull;
            Status: <span class="badge badge-info">{{ strtoupper($peserta->status) }}</span>
        </p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.rekap_nilai', $peserta->jadwal_ujian_id) }}" class="btn btn-secondary btn-sm">&larr; Kembali ke Rekap Nilai</a>
    </div>
</div>

<!-- Ringkasan Skor Peserta -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin-bottom: 20px;">
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px;">
        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Skor PG / Benar-Salah</div>
        <div style="font-size: 22px; font-weight: 800; color: #0284c7; margin-top: 2px;">{{ number_format($peserta->nilai_pg, 1) }}</div>
        <div style="font-size: 11px; color: #64748b;">Benar: {{ $peserta->jawaban_benar }} | Salah: {{ $peserta->jawaban_salah }} | Kosong: {{ $peserta->jawaban_kosong }}</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px;">
        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Skor Uraian Manual</div>
        <div style="font-size: 22px; font-weight: 800; color: #d97706; margin-top: 2px;">{{ number_format($peserta->nilai_uraian, 1) }}</div>
        <div style="font-size: 11px; color: #64748b;">Hasil koreksi penguji / admin</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px;">
        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Total Nilai Akhir</div>
        <div style="font-size: 22px; font-weight: 800; color: #16a34a; margin-top: 2px;">{{ number_format($peserta->total_nilai, 1) }}</div>
        <div style="font-size: 11px; color: #64748b;">Maksimal 100.0</div>
    </div>
</div>

<form action="{{ route('admin.koreksi_peserta.simpan', $peserta->id) }}" method="POST">
    @csrf

    <div class="table-container" style="margin-bottom: 20px;">
        <table class="table-compact">
            <thead>
                <tr>
                    <th style="width: 45px; text-align: center;">No.</th>
                    <th style="width: 105px;">Tipe</th>
                    <th>Pertanyaan &amp; Kunci / Rubrik</th>
                    <th style="width: 28%;">Jawaban Siswa</th>
                    <th style="width: 130px; text-align: center;">Skor Butir</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarSoal as $idx => $soal)
                @php
                    $jwb = $jawabanMap->get($soal->id);
                    $ansText = $jwb ? trim((string) $jwb->jawaban_siswa) : '';
                @endphp
                <tr>
                    <td style="text-align: center;"><strong>{{ $idx + 1 }}</strong></td>
                    <td>
                        @if ($soal->jenis_soal === 'uraian')
                            <span class="badge badge-warning">URAIAN</span>
                        @elseif ($soal->jenis_soal === 'benar_salah')
                            <span class="badge badge-info">B / S</span>
                        @else
                            <span class="badge badge-neutral">PG</span>
                        @endif
                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">Maks: {{ number_format((float) $soal->bobot, 1) }}</div>
                    </td>
                    <td>
                        <div style="font-size: 13px; color: var(--text-main); line-height: 1.5;">
                            {!! nl2br(e($soal->konten_soal)) !!}
                        </div>
                        @if ($soal->jenis_soal === 'uraian')
                            @if ($soal->rubrik_uraian || $soal->kunci_jawaban)
                                <div style="margin-top: 6px; padding: 6px 10px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 4px; font-size: 11.5px; color: #92400e;">
                                    <strong>Pedoman / Kunci:</strong> {{ $soal->rubrik_uraian ?: $soal->kunci_jawaban }}
                                </div>
                            @endif
                        @else
                            <div style="margin-top: 4px; font-size: 11.5px; color: #15803d;">
                                <strong>Kunci:</strong> <code>{{ $soal->kunci_jawaban ?: '-' }}</code>
                            </div>
                        @endif
                    </td>
                    <td>
                        @if ($ansText === '')
                            <span style="color: #94a3b8; font-style: italic;">(Tidak dijawab)</span>
                        @elseif ($soal->jenis_soal === 'uraian')
                            <div style="background: #f8fafc; border: 1px solid var(--border-light); padding: 8px 10px; border-radius: 4px; font-size: 12.5px; white-space: pre-wrap;">{{ $ansText }}</div>
                        @else
                            <span style="font-weight: 700; color: {{ $jwb && $jwb->is_benar ? '#16a34a' : '#dc2626' }};">
                                {{ $ansText }}
                            </span>
                            @if ($jwb && $jwb->is_benar)
                                <span class="badge badge-success" style="margin-left: 4px;">Benar</span>
                            @else
                                <span class="badge badge-danger" style="margin-left: 4px;">Salah</span>
                            @endif
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if ($soal->jenis_soal === 'uraian')
                            <input type="number" step="0.5" min="0" max="100" name="skor_uraian[{{ $soal->id }}]" value="{{ $jwb ? (float) $jwb->nilai_diperoleh : 0 }}" style="width: 90px; padding: 6px 8px; border: 1.5px solid #f59e0b; border-radius: 4px; text-align: center; font-weight: 700;">
                        @else
                            <strong>{{ number_format($jwb ? (float) $jwb->nilai_diperoleh : 0, 1) }}</strong>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 24px; color: #64748b;">Tidak ada butir soal pada bank soal ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Panel Penyesuaian Nilai Akhir oleh Admin -->
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; gap: 18px; align-items: center; flex-wrap: wrap;">
            <div>
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #475569; margin-bottom: 4px;">Penyesuaian Total Skor Uraian Manual (Opsional)</label>
                <input type="number" step="0.5" min="0" max="100" name="override_nilai_uraian" placeholder="Otomatis dari penjumlahan butir" style="width: 240px; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Kosongkan bila ingin dijumlah otomatis dari kolom Skor Butir Uraian di atas.</div>
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.rekap_nilai', $peserta->jadwal_ujian_id) }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Koreksi &amp; Hitung Nilai Akhir</button>
        </div>
    </div>
</form>
@endsection

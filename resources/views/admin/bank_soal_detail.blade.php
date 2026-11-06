@extends('layouts.admin')

@section('title', 'Detail Butir Soal - ' . $bank->kode_bank)

@section('content')
<div class="page-header">
    <div>
        <div style="font-size: 11px; color: var(--accent); font-weight: 700; text-transform: uppercase;">Bank Soal &bull; {{ $bank->mataPelajaran->nama_mapel ?? '-' }}</div>
        <h2 class="page-title">Kelola Butir Soal: {{ $bank->kode_bank }}</h2>
        <p class="page-subtitle">Tingkat: {{ $bank->tingkat }} &bull; Target: {{ $bank->jumlah_soal_pg + $bank->jumlah_soal_uraian }} Soal &bull; Total Bobot: {{ $bank->butirSoal->sum('bobot') }} / 100 Poin</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.bank_soal') }}" class="btn btn-secondary btn-sm">&larr; Kembali ke Daftar Bank</a>
        <button type="button" class="btn btn-primary btn-sm" onclick="openModal('modalTambahSoal')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Butir Soal
        </button>
    </div>
</div>

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 40px;">No.</th>
                <th>Jenis</th>
                <th>Isi / Konten Soal</th>
                <th>Pilihan Jawaban (A / B / C / D / E)</th>
                <th>Kunci</th>
                <th>Bobot</th>
                <th style="text-align: center; width: 80px;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bank->butirSoal as $soal)
            <tr>
                <td><strong>{{ $soal->nomor_urut }}</strong></td>
                <td>
                    @if ($soal->jenis_soal === 'pg')
                        <span class="badge badge-info">Pilihan Ganda</span>
                    @elseif ($soal->jenis_soal === 'benar_salah')
                        <span class="badge badge-warning">Benar / Salah</span>
                    @else
                        <span class="badge badge-neutral">Uraian / Esai</span>
                    @endif
                </td>
                <td style="max-width: 380px;">
                    <div style="font-size: 13px; color: var(--text-main); line-height: 1.4;">{{ $soal->konten_soal }}</div>
                </td>
                <td style="font-size: 11.5px; color: #475569; max-width: 320px;">
                    @if ($soal->jenis_soal !== 'uraian')
                        <div><strong>A:</strong> {{ Str::limit($soal->opsi_a, 40) }}</div>
                        <div><strong>B:</strong> {{ Str::limit($soal->opsi_b, 40) }}</div>
                        @if ($soal->opsi_c) <div><strong>C:</strong> {{ Str::limit($soal->opsi_c, 40) }}</div> @endif
                        @if ($soal->opsi_d) <div><strong>D:</strong> {{ Str::limit($soal->opsi_d, 40) }}</div> @endif
                        @if ($soal->opsi_e) <div><strong>E:</strong> {{ Str::limit($soal->opsi_e, 40) }}</div> @endif
                    @else
                        <em>Soal uraian / esai (dinilai manual oleh guru)</em>
                    @endif
                </td>
                <td>
                    @if ($soal->kunci_jawaban)
                        <span style="background: #0f172a; color: #38bdf8; font-family: monospace; font-size: 12px; font-weight: 700; padding: 2px 7px; border-radius: var(--radius-sm);">
                            {{ $soal->kunci_jawaban }}
                        </span>
                    @else
                        <span style="color: #94a3b8;">-</span>
                    @endif
                </td>
                <td><strong>{{ $soal->bobot }}</strong></td>
                <td style="text-align: center;">
                    <form action="{{ route('admin.bank_soal.butir.hapus', $soal->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus butir soal nomor {{ $soal->nomor_urut }}?');">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 32px; color: #64748b;">
                    Belum ada butir soal pada paket ini. Klik tombol 'Tambah Butir Soal' di atas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Butir Soal -->
<div id="modalTambahSoal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 640px; padding: 24px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Tambah Butir Soal Baru</h3>
            <button type="button" onclick="closeModal('modalTambahSoal')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.bank_soal.butir.simpan', $bank->id) }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1.5fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nomor Urut *</label>
                    <input type="number" name="nomor_urut" value="{{ $bank->butirSoal->count() + 1 }}" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jenis Soal *</label>
                    <select name="jenis_soal" id="jenisSoalSelect" onchange="toggleOpsiFields()" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="pg">Pilihan Ganda (A-E)</option>
                        <option value="benar_salah">Benar / Salah (A-B)</option>
                        <option value="uraian">Uraian / Esai</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Bobot Poin *</label>
                    <input type="number" step="0.5" name="bobot" value="4.0" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Isi / Konten Pertanyaan *</label>
                <textarea name="konten_soal" rows="3" required placeholder="Tuliskan teks pertanyaan soal..." style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-family: inherit;"></textarea>
            </div>

            <div id="opsiWrapper">
                <div style="margin-bottom: 8px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan A:</label>
                    <input type="text" name="opsi_a" placeholder="Teks Pilihan A" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div style="margin-bottom: 8px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan B:</label>
                    <input type="text" name="opsi_b" placeholder="Teks Pilihan B" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div style="margin-bottom: 8px;" id="opsiCWrapper">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan C:</label>
                    <input type="text" name="opsi_c" placeholder="Teks Pilihan C" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div style="margin-bottom: 8px;" id="opsiDWrapper">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan D:</label>
                    <input type="text" name="opsi_d" placeholder="Teks Pilihan D" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div style="margin-bottom: 12px;" id="opsiEWrapper">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan E:</label>
                    <input type="text" name="opsi_e" placeholder="Teks Pilihan E" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 20px;" id="kunciWrapper">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kunci Jawaban Benar *</label>
                <select name="kunci_jawaban" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalTambahSoal')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Butir Soal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    function toggleOpsiFields() {
        const jenis = document.getElementById('jenisSoalSelect').value;
        const opsiWrapper = document.getElementById('opsiWrapper');
        const kunciWrapper = document.getElementById('kunciWrapper');
        const opsiC = document.getElementById('opsiCWrapper');
        const opsiD = document.getElementById('opsiDWrapper');
        const opsiE = document.getElementById('opsiEWrapper');

        if (jenis === 'uraian') {
            opsiWrapper.style.display = 'none';
            kunciWrapper.style.display = 'none';
        } else if (jenis === 'benar_salah') {
            opsiWrapper.style.display = 'block';
            kunciWrapper.style.display = 'block';
            opsiC.style.display = 'none';
            opsiD.style.display = 'none';
            opsiE.style.display = 'none';
        } else {
            opsiWrapper.style.display = 'block';
            kunciWrapper.style.display = 'block';
            opsiC.style.display = 'block';
            opsiD.style.display = 'block';
            opsiE.style.display = 'block';
        }
    }
</script>
@endsection

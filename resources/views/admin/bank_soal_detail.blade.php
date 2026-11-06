@extends('layouts.admin')

@section('title', 'Kelola Soal - ' . $bank->kode_bank)

@section('content')
<div class="page-header">
    <div>
        <div style="font-size: 11px; color: var(--accent); font-weight: 700; text-transform: uppercase; margin-bottom: 2px;">
            Bank Soal &bull; {{ $bank->mataPelajaran->nama_mapel ?? '-' }} &bull; {{ $bank->mataPelajaran->jurusan ?? 'Semua Jurusan' }}
        </div>
        <h2 class="page-title">{{ $bank->kode_bank }} : {{ $bank->nama_bank ?: ($bank->mataPelajaran->nama_mapel ?? 'Paket Asesmen') }}</h2>
        <p class="page-subtitle">
            Standar: <strong>{{ str_replace('_', ' ', strtoupper($bank->standar_bobot)) }}</strong> &bull; 
            Guru Pengampu: <strong>{{ $bank->guru ? $bank->guru->name : ($bank->mataPelajaran->guru->name ?? 'Admin') }}</strong> &bull; 
            KKM: <strong>{{ number_format($bank->kkm, 0) }}</strong>
        </p>
    </div>
    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
        <a href="{{ route('admin.bank_soal') }}" class="btn btn-secondary btn-sm">&larr; Kembali</a>
        <a href="{{ route('admin.bank_soal.pratinjau', $bank->id) }}" class="btn btn-secondary btn-sm" target="_blank" title="Buka live simulator ujian siswa ANBK">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            Pratinjau Siswa
        </a>
        <a href="{{ route('admin.bank_soal.template_xlsx', $bank->id) }}" class="btn btn-secondary btn-sm" title="Unduh template Excel butir soal">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Template XLSX
        </a>
        <button type="button" class="btn btn-secondary btn-sm" onclick="openModal('modalImportXlsx')" title="Impor butir soal dari Excel">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            Impor XLSX
        </button>
        <button type="button" class="btn btn-secondary btn-sm" onclick="openModal('modalImportDocx')" title="Impor butir soal langsung dari Microsoft Word .docx">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            Impor Word DOCX
        </button>
        <a href="{{ route('admin.bank_soal.export_xlsx', $bank->id) }}" class="btn btn-secondary btn-sm" title="Ekspor butir soal ke Excel">
            Ekspor XLSX
        </a>
        <button type="button" class="btn btn-primary btn-sm" onclick="openModal('modalTambahSoal')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Butir Soal
        </button>
    </div>
</div>

<!-- Standarisasi Bobot & Progress Card -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 16px;">
    <div class="card" style="padding: 12px 16px;">
        <div style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase;">Total Butir Soal</div>
        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-top: 4px;">
            <strong style="font-size: 20px; color: var(--primary);">{{ $bank->butirSoal->count() }} Soal</strong>
            <span style="font-size: 11px; color: #64748b;">Target: {{ $bank->jumlah_soal_pg + ($bank->jumlah_soal_bs ?? 0) + $bank->jumlah_soal_uraian }}</span>
        </div>
    </div>
    <div class="card" style="padding: 12px 16px;">
        <div style="font-size: 11px; font-weight: 600; color: #0284c7; text-transform: uppercase;">Pilihan Ganda (PG)</div>
        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-top: 4px;">
            <strong style="font-size: 20px; color: #0284c7;">{{ $countPg }} / {{ $bank->jumlah_soal_pg }}</strong>
            <span style="font-size: 11px; color: #64748b;">Bobot: {{ $bobotPgCurrent }} / {{ $bank->bobot_pg }} pt</span>
        </div>
    </div>
    <div class="card" style="padding: 12px 16px;">
        <div style="font-size: 11px; font-weight: 600; color: #d97706; text-transform: uppercase;">Benar / Salah (B/S)</div>
        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-top: 4px;">
            <strong style="font-size: 20px; color: #d97706;">{{ $countBs }} / {{ $bank->jumlah_soal_bs ?? 0 }}</strong>
            <span style="font-size: 11px; color: #64748b;">Bobot: {{ $bobotBsCurrent }} / {{ $bank->bobot_bs ?? 0 }} pt</span>
        </div>
    </div>
    <div class="card" style="padding: 12px 16px;">
        <div style="font-size: 11px; font-weight: 600; color: #475569; text-transform: uppercase;">Uraian / Esai</div>
        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-top: 4px;">
            <strong style="font-size: 20px; color: #475569;">{{ $countUraian }} / {{ $bank->jumlah_soal_uraian }}</strong>
            <span style="font-size: 11px; color: #64748b;">Bobot: {{ $bobotUraianCurrent }} / {{ $bank->bobot_uraian }} pt</span>
        </div>
    </div>
    <div class="card" style="padding: 12px 16px; background: {{ $totalBobotCurrent == 100.0 ? '#f0fdf4' : ($totalBobotCurrent > 100.0 ? '#fef2f2' : '#fffbeb') }}; border-color: {{ $totalBobotCurrent == 100.0 ? '#86efac' : ($totalBobotCurrent > 100.0 ? '#fca5a5' : '#fde68a') }};">
        <div style="font-size: 11px; font-weight: 700; color: {{ $totalBobotCurrent == 100.0 ? '#15803d' : ($totalBobotCurrent > 100.0 ? '#b91c1c' : '#b45309') }}; text-transform: uppercase;">Akumulasi Bobot</div>
        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-top: 4px;">
            <strong style="font-size: 20px; color: {{ $totalBobotCurrent == 100.0 ? '#15803d' : ($totalBobotCurrent > 100.0 ? '#b91c1c' : '#b45309') }};">
                {{ $totalBobotCurrent }} / 100 Poin
            </strong>
            <span style="font-size: 11px; font-weight: 700; color: {{ $totalBobotCurrent == 100.0 ? '#15803d' : '#b45309' }};">
                {{ $totalBobotCurrent == 100.0 ? 'STANDAR TERCAPAI' : ($totalBobotCurrent > 100.0 ? 'LEBIH BOBOT' : 'BELUM GENAP') }}
            </span>
        </div>
    </div>
</div>

<!-- Tab Navigasi Tipe Soal -->
<div style="display: flex; gap: 8px; margin-bottom: 12px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
    <a href="{{ route('admin.bank_soal.detail', ['id' => $bank->id, 'jenis' => 'semua']) }}" class="btn btn-sm {{ $filterJenis === 'semua' ? 'btn-primary' : 'btn-secondary' }}">
        Semua Butir ({{ $bank->butirSoal->count() }})
    </a>
    <a href="{{ route('admin.bank_soal.detail', ['id' => $bank->id, 'jenis' => 'pg']) }}" class="btn btn-sm {{ $filterJenis === 'pg' ? 'btn-primary' : 'btn-secondary' }}">
        Pilihan Ganda ({{ $countPg }})
    </a>
    <a href="{{ route('admin.bank_soal.detail', ['id' => $bank->id, 'jenis' => 'benar_salah']) }}" class="btn btn-sm {{ $filterJenis === 'benar_salah' ? 'btn-primary' : 'btn-secondary' }}">
        Benar / Salah ({{ $countBs }})
    </a>
    <a href="{{ route('admin.bank_soal.detail', ['id' => $bank->id, 'jenis' => 'uraian']) }}" class="btn btn-sm {{ $filterJenis === 'uraian' ? 'btn-primary' : 'btn-secondary' }}">
        Uraian / Esai ({{ $countUraian }})
    </a>
</div>

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 40px;">No.</th>
                <th style="width: 100px;">Jenis</th>
                <th>Isi / Konten Soal (KaTeX & Media)</th>
                <th>Pilihan Jawaban / Rubrik</th>
                <th style="text-align: center; width: 80px;">Kunci</th>
                <th style="text-align: center; width: 60px;">Bobot</th>
                <th style="text-align: center; width: 110px;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($soalList as $soal)
            <tr>
                <td><strong>{{ $soal->nomor_urut }}</strong></td>
                <td>
                    @if ($soal->jenis_soal === 'pg')
                        <span class="badge badge-info">Pilihan Ganda</span>
                    @elseif ($soal->jenis_soal === 'benar_salah')
                        <span class="badge badge-warning">Benar / Salah</span>
                    @else
                        <span class="badge badge-neutral">Uraian</span>
                    @endif
                </td>
                <td style="max-width: 420px;">
                    <div class="katex-content" style="font-size: 13px; color: var(--text-main); line-height: 1.45; white-space: pre-line;">{!! nl2br(e($soal->konten_soal)) !!}</div>

                    @if ($soal->gambar_soal)
                        <div style="margin-top: 6px;">
                            <img src="{{ asset($soal->gambar_soal) }}" alt="Gambar Soal" style="max-height: 90px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                        </div>
                    @endif

                    @if ($soal->audio_file)
                        <div style="margin-top: 6px;">
                            <audio controls style="height: 28px; width: 220px;">
                                <source src="{{ asset($soal->audio_file) }}">
                            </audio>
                        </div>
                    @endif
                </td>
                <td style="font-size: 11.5px; color: #475569; max-width: 320px;">
                    @if ($soal->jenis_soal === 'pg')
                        <div><strong>A:</strong> {{ Str::limit($soal->opsi_a, 45) }}</div>
                        <div><strong>B:</strong> {{ Str::limit($soal->opsi_b, 45) }}</div>
                        @if ($soal->opsi_c) <div><strong>C:</strong> {{ Str::limit($soal->opsi_c, 45) }}</div> @endif
                        @if ($soal->opsi_d) <div><strong>D:</strong> {{ Str::limit($soal->opsi_d, 45) }}</div> @endif
                        @if ($soal->opsi_e) <div><strong>E:</strong> {{ Str::limit($soal->opsi_e, 45) }}</div> @endif
                    @elseif ($soal->jenis_soal === 'benar_salah')
                        <div style="color: #0369a1;">Pernyataan Benar / Salah</div>
                        <div><strong>A:</strong> {{ $soal->opsi_a ?: 'Benar' }}</div>
                        <div><strong>B:</strong> {{ $soal->opsi_b ?: 'Salah' }}</div>
                    @else
                        <div style="color: #475569;">
                            <strong>Rubrik Penilaian:</strong>
                            <div style="font-style: italic; margin-top: 2px;">{{ Str::limit($soal->rubrik_uraian ?: 'Dinilai manual oleh guru penguji', 70) }}</div>
                        </div>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if ($soal->kunci_jawaban)
                        <span style="background: #0f172a; color: #38bdf8; font-family: monospace; font-size: 12px; font-weight: 700; padding: 2px 7px; border-radius: var(--radius-sm);">
                            {{ $soal->kunci_jawaban }}
                        </span>
                    @else
                        <span style="color: #94a3b8; font-size: 11px;">-</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <strong>{{ number_format($soal->bobot, 1) }}</strong>
                </td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 4px; justify-content: center;">
                        <button type="button" class="btn btn-secondary btn-sm" onclick='editSoal(@json($soal))' style="padding: 3px 6px; font-size: 11px;">Edit</button>
                        <form action="{{ route('admin.bank_soal.butir.hapus', $soal->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus butir soal nomor {{ $soal->nomor_urut }}?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" style="padding: 3px 6px; font-size: 11px;">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 36px; color: #64748b;">
                    Belum ada butir soal pada kategori ini. Gunakan tombol <strong>'Tambah Butir Soal'</strong>, <strong>'Impor XLSX'</strong>, atau <strong>'Impor Word DOCX'</strong> di atas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Butir Soal -->
<div id="modalTambahSoal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 680px; padding: 24px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Tambah Butir Soal Baru</h3>
            <button type="button" onclick="closeModal('modalTambahSoal')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.bank_soal.butir.simpan', $bank->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1.6fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nomor Urut *</label>
                    <input type="number" name="nomor_urut" value="{{ $nextNomor }}" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jenis Soal *</label>
                    <select name="jenis_soal" id="tambah_jenis_soal" onchange="switchJenisSoalTambah()" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="pg">Pilihan Ganda (A - E)</option>
                        <option value="benar_salah">Benar / Salah (B/S)</option>
                        <option value="uraian">Uraian / Esai (Manual Grading)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Bobot Poin *</label>
                    <input type="number" step="0.1" name="bobot" id="tambah_bobot" value="{{ $saranBobotPg }}" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <!-- Toolbar Bantuan Rumus Matematika (KaTeX) -->
            <div style="margin-bottom: 6px; background: #f8fafc; border: 1px solid var(--border-light); border-radius: var(--radius-sm); padding: 8px 10px;">
                <div style="font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 4px;">Bantuan Rumus Matematika (KaTeX):</div>
                <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('tambah_konten', '$\\frac{a}{b}$')">Pecahan $\frac{a}{b}$</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('tambah_konten', '$\\sqrt{x}$')">Akar $\sqrt{x}$</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('tambah_konten', '$x^{2}$')">Pangkat $x^2$</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('tambah_konten', '$\\times$')">Kali $\times$</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('tambah_konten', '$\\pm$')">Plus-Minus $\pm$</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('tambah_konten', '$\\le$')">Kurang Sama $\le$</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('tambah_konten', '$\\ge$')">Lebih Sama $\ge$</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('tambah_konten', '$\\pi$')">Pi $\pi$</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('tambah_konten', '$\\alpha$')">Alpha $\alpha$</button>
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Isi / Konten Pertanyaan *</label>
                <textarea name="konten_soal" id="tambah_konten" rows="4" required placeholder="Tuliskan teks pertanyaan soal asesmen..." style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-family: inherit;"></textarea>
            </div>

            <!-- Media Unggah: Gambar & Audio -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px; background: #fdfefe; border: 1px dashed var(--border); padding: 10px; border-radius: var(--radius-sm);">
                <div>
                    <label style="display: block; font-size: 11.5px; font-weight: 600; color: #475569; margin-bottom: 4px;">Unggah Gambar Soal (Opsional)</label>
                    <input type="file" name="gambar_soal" accept="image/*" style="font-size: 11.5px; width: 100%;">
                </div>
                <div>
                    <label style="display: block; font-size: 11.5px; font-weight: 600; color: #475569; margin-bottom: 4px;">Unggah Audio Listening (Opsional)</label>
                    <input type="file" name="audio_file" accept="audio/*" style="font-size: 11.5px; width: 100%;">
                </div>
            </div>

            <!-- Opsi PG -->
            <div id="tambah_section_pg">
                <div style="margin-bottom: 8px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan A:</label>
                    <input type="text" name="opsi_a" placeholder="Pilihan A" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div style="margin-bottom: 8px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan B:</label>
                    <input type="text" name="opsi_b" placeholder="Pilihan B" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div style="margin-bottom: 8px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan C:</label>
                    <input type="text" name="opsi_c" placeholder="Pilihan C" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div style="margin-bottom: 8px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan D:</label>
                    <input type="text" name="opsi_d" placeholder="Pilihan D" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan E:</label>
                    <input type="text" name="opsi_e" placeholder="Pilihan E" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <!-- Opsi Benar / Salah -->
            <div id="tambah_section_bs" style="display: none; margin-bottom: 14px; background: #fffbeb; border: 1px solid #fde68a; padding: 12px; border-radius: var(--radius-sm);">
                <div style="font-size: 12px; font-weight: 600; color: #b45309; margin-bottom: 6px;">Pilihan Pernyataan:</div>
                <div style="display: flex; gap: 16px;">
                    <label style="font-size: 13px; font-weight: 700; color: #15803d; cursor: pointer;">
                        <input type="radio" name="kunci_bs" value="BENAR" checked> A. BENAR
                    </label>
                    <label style="font-size: 13px; font-weight: 700; color: #b91c1c; cursor: pointer;">
                        <input type="radio" name="kunci_bs" value="SALAH"> B. SALAH
                    </label>
                </div>
            </div>

            <!-- Opsi Uraian -->
            <div id="tambah_section_uraian" style="display: none; margin-bottom: 14px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Rubrik Penilaian / Kunci Jawaban Uraian</label>
                <textarea name="rubrik_uraian" rows="3" placeholder="Tuliskan kata kunci, indikator penilaian, atau pedoman penskoran uraian..." style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12.5px; font-family: inherit;"></textarea>
            </div>

            <!-- Kunci Jawaban PG Dropdown -->
            <div style="margin-bottom: 20px;" id="tambah_kunci_pg_wrapper">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kunci Jawaban Benar (PG) *</label>
                <select name="kunci_jawaban" id="tambah_kunci_select" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
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

<!-- Modal Edit Butir Soal -->
<div id="modalEditSoal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 680px; padding: 24px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Ubah Butir Soal</h3>
            <button type="button" onclick="closeModal('modalEditSoal')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form id="formEditSoal" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1.6fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nomor Urut *</label>
                    <input type="number" name="nomor_urut" id="edit_nomor_urut" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jenis Soal *</label>
                    <select name="jenis_soal" id="edit_jenis_soal" onchange="switchJenisSoalEdit()" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="pg">Pilihan Ganda (A - E)</option>
                        <option value="benar_salah">Benar / Salah (B/S)</option>
                        <option value="uraian">Uraian / Esai (Manual Grading)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Bobot Poin *</label>
                    <input type="number" step="0.1" name="bobot" id="edit_bobot" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <!-- Toolbar Bantuan Rumus Matematika (KaTeX) -->
            <div style="margin-bottom: 6px; background: #f8fafc; border: 1px solid var(--border-light); border-radius: var(--radius-sm); padding: 8px 10px;">
                <div style="font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 4px;">Bantuan Rumus Matematika (KaTeX):</div>
                <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('edit_konten', '$\\frac{a}{b}$')">Pecahan $\frac{a}{b}$</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('edit_konten', '$\\sqrt{x}$')">Akar $\sqrt{x}$</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('edit_konten', '$x^{2}$')">Pangkat $x^2$</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('edit_konten', '$\\times$')">Kali $\times$</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('edit_konten', '$\\pm$')">Plus-Minus $\pm$</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 2px 6px; font-size: 11px;" onclick="insertFormula('edit_konten', '$\\pi$')">Pi $\pi$</button>
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Isi / Konten Pertanyaan *</label>
                <textarea name="konten_soal" id="edit_konten" rows="4" required style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-family: inherit;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px; background: #fdfefe; border: 1px dashed var(--border); padding: 10px; border-radius: var(--radius-sm);">
                <div>
                    <label style="display: block; font-size: 11.5px; font-weight: 600; color: #475569; margin-bottom: 4px;">Ganti Gambar (Opsional)</label>
                    <input type="file" name="gambar_soal" accept="image/*" style="font-size: 11.5px; width: 100%;">
                </div>
                <div>
                    <label style="display: block; font-size: 11.5px; font-weight: 600; color: #475569; margin-bottom: 4px;">Ganti Audio (Opsional)</label>
                    <input type="file" name="audio_file" accept="audio/*" style="font-size: 11.5px; width: 100%;">
                </div>
            </div>

            <!-- Opsi PG Edit -->
            <div id="edit_section_pg">
                <div style="margin-bottom: 8px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan A:</label>
                    <input type="text" name="opsi_a" id="edit_opsi_a" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div style="margin-bottom: 8px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan B:</label>
                    <input type="text" name="opsi_b" id="edit_opsi_b" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div style="margin-bottom: 8px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan C:</label>
                    <input type="text" name="opsi_c" id="edit_opsi_c" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div style="margin-bottom: 8px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan D:</label>
                    <input type="text" name="opsi_d" id="edit_opsi_d" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569;">Pilihan E:</label>
                    <input type="text" name="opsi_e" id="edit_opsi_e" style="width: 100%; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <!-- Opsi Benar/Salah Edit -->
            <div id="edit_section_bs" style="display: none; margin-bottom: 14px; background: #fffbeb; border: 1px solid #fde68a; padding: 12px; border-radius: var(--radius-sm);">
                <div style="font-size: 12px; font-weight: 600; color: #b45309; margin-bottom: 6px;">Kunci Benar / Salah:</div>
                <div style="display: flex; gap: 16px;">
                    <label style="font-size: 13px; font-weight: 700; color: #15803d; cursor: pointer;">
                        <input type="radio" name="kunci_jawaban_bs" id="edit_kunci_bs_benar" value="BENAR"> A. BENAR
                    </label>
                    <label style="font-size: 13px; font-weight: 700; color: #b91c1c; cursor: pointer;">
                        <input type="radio" name="kunci_jawaban_bs" id="edit_kunci_bs_salah" value="SALAH"> B. SALAH
                    </label>
                </div>
            </div>

            <!-- Opsi Uraian Edit -->
            <div id="edit_section_uraian" style="display: none; margin-bottom: 14px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Rubrik Penilaian / Kunci Jawaban Uraian</label>
                <textarea name="rubrik_uraian" id="edit_rubrik_uraian" rows="3" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12.5px; font-family: inherit;"></textarea>
            </div>

            <!-- Kunci Jawaban PG Dropdown -->
            <div style="margin-bottom: 20px;" id="edit_kunci_pg_wrapper">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kunci Jawaban Benar (PG) *</label>
                <select name="kunci_jawaban" id="edit_kunci_select" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalEditSoal')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Impor XLSX Soal -->
<div id="modalImportXlsx" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 520px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Impor Butir Soal Excel (.xlsx)</h3>
            <button type="button" onclick="closeModal('modalImportXlsx')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.bank_soal.import_xlsx', $bank->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: var(--radius-sm); padding: 12px; margin-bottom: 16px; font-size: 12px; color: #166534;">
                <strong>Panduan Impor Soal:</strong>
                <ol style="margin: 6px 0 0 16px; padding: 0;">
                    <li>Unduh template resmi: <a href="{{ route('admin.bank_soal.template_xlsx', $bank->id) }}" style="color: #15803d; font-weight: 700; text-decoration: underline;">Unduh Template Soal</a></li>
                    <li>Mendukung jenis: <code>PG</code>, <code>BENAR_SALAH</code>, dan <code>URAIAN</code>.</li>
                    <li>Rumus KaTeX didukung, contoh: <code>$x^2$</code> atau <code>$\frac{a}{b}$</code>.</li>
                    <li>Bobot otomatis dihitung sesuai preset jika kolom bobot dikosongkan.</li>
                </ol>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px;">Pilih Berkas Excel (.xlsx, .xls, .csv) *</label>
                <input type="file" name="file_excel" required accept=".xlsx, .xls, .csv" style="width: 100%; padding: 8px; border: 1px dashed var(--border); border-radius: var(--radius-sm); font-size: 12.5px;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalImportXlsx')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Mulai Impor Soal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Impor Word DOCX -->
<div id="modalImportDocx" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 540px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Impor Butir Soal dari Microsoft Word (.docx)</h3>
            <button type="button" onclick="closeModal('modalImportDocx')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.bank_soal.import_docx', $bank->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 12px; margin-bottom: 16px; font-size: 12px; color: #334155;">
                <strong>Format Penulisan di Word:</strong>
                <pre style="background: #ffffff; border: 1px solid var(--border-light); padding: 8px; margin-top: 6px; font-size: 11px; border-radius: var(--radius-sm); line-height: 1.4;">
1. Protokol jaringan yang bertugas menerjemahkan IP adalah...
A. DHCP
B. DNS
C. FTP
D. HTTP
E. SMTP
Kunci: B

2. [B/S] Topologi star memakai switch konsentrator.
Kunci: BENAR

3. [URAIAN] Jelaskan perbedaan UTP dan STP!
Rubrik: UTP tanpa foil, STP memakai foil pelindung.</pre>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px;">Pilih Berkas Word (.docx) *</label>
                <input type="file" name="file_docx" required accept=".docx" style="width: 100%; padding: 8px; border: 1px dashed var(--border); border-radius: var(--radius-sm); font-size: 12.5px;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalImportDocx')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Mulai Parse & Impor DOCX</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }

    function insertFormula(textareaId, formula) {
        const txt = document.getElementById(textareaId);
        const start = txt.selectionStart;
        const end = txt.selectionEnd;
        const current = txt.value;
        txt.value = current.substring(0, start) + formula + current.substring(end);
        txt.focus();
        txt.setSelectionRange(start + formula.length, start + formula.length);
    }

    function switchJenisSoalTambah() {
        const jenis = document.getElementById('tambah_jenis_soal').value;
        const pgSec = document.getElementById('tambah_section_pg');
        const bsSec = document.getElementById('tambah_section_bs');
        const urSec = document.getElementById('tambah_section_uraian');
        const kunciPg = document.getElementById('tambah_kunci_pg_wrapper');
        const bobotInput = document.getElementById('tambah_bobot');

        if (jenis === 'pg') {
            pgSec.style.display = 'block';
            bsSec.style.display = 'none';
            urSec.style.display = 'none';
            kunciPg.style.display = 'block';
            bobotInput.value = '{{ $saranBobotPg }}';
        } else if (jenis === 'benar_salah') {
            pgSec.style.display = 'none';
            bsSec.style.display = 'block';
            urSec.style.display = 'none';
            kunciPg.style.display = 'none';
            bobotInput.value = '{{ $saranBobotBs }}';
        } else {
            pgSec.style.display = 'none';
            bsSec.style.display = 'none';
            urSec.style.display = 'block';
            kunciPg.style.display = 'none';
            bobotInput.value = '{{ $saranBobotUraian }}';
        }
    }

    function switchJenisSoalEdit() {
        const jenis = document.getElementById('edit_jenis_soal').value;
        const pgSec = document.getElementById('edit_section_pg');
        const bsSec = document.getElementById('edit_section_bs');
        const urSec = document.getElementById('edit_section_uraian');
        const kunciPg = document.getElementById('edit_kunci_pg_wrapper');

        if (jenis === 'pg') {
            pgSec.style.display = 'block';
            bsSec.style.display = 'none';
            urSec.style.display = 'none';
            kunciPg.style.display = 'block';
        } else if (jenis === 'benar_salah') {
            pgSec.style.display = 'none';
            bsSec.style.display = 'block';
            urSec.style.display = 'none';
            kunciPg.style.display = 'none';
        } else {
            pgSec.style.display = 'none';
            bsSec.style.display = 'none';
            urSec.style.display = 'block';
            kunciPg.style.display = 'none';
        }
    }

    function editSoal(s) {
        document.getElementById('edit_nomor_urut').value = s.nomor_urut || '';
        document.getElementById('edit_jenis_soal').value = s.jenis_soal || 'pg';
        document.getElementById('edit_bobot').value = s.bobot || '';
        document.getElementById('edit_konten').value = s.konten_soal || '';
        document.getElementById('edit_opsi_a').value = s.opsi_a || '';
        document.getElementById('edit_opsi_b').value = s.opsi_b || '';
        document.getElementById('edit_opsi_c').value = s.opsi_c || '';
        document.getElementById('edit_opsi_d').value = s.opsi_d || '';
        document.getElementById('edit_opsi_e').value = s.opsi_e || '';
        document.getElementById('edit_rubrik_uraian').value = s.rubrik_uraian || '';

        if (s.jenis_soal === 'benar_salah') {
            if (s.kunci_jawaban === 'SALAH') {
                document.getElementById('edit_kunci_bs_salah').checked = true;
            } else {
                document.getElementById('edit_kunci_bs_benar').checked = true;
            }
        } else if (s.jenis_soal === 'pg') {
            document.getElementById('edit_kunci_select').value = s.kunci_jawaban || 'A';
        }

        document.getElementById('formEditSoal').action = '/admin/butir-soal/' + s.id + '/update';
        switchJenisSoalEdit();
        openModal('modalEditSoal');
    }

    // Auto-render KaTeX on document load
    document.addEventListener("DOMContentLoaded", function() {
        if (window.renderMathInElement) {
            renderMathInElement(document.body, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false}
                ]
            });
        }
    });
</script>
@endsection

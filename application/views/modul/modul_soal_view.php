<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Mengelola Soal
		<small>Mengelola soal berdasarkan modul dan topik (Standar TKA Kemdikbud)</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Soal</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-title"><i class="fa fa-folder-open"></i> Pilih Topik</div>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="col-xs-2"></div>
                    <div class="col-xs-8">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Pilih Topik</label>
                            <div class="col-sm-9">
                                <select name="topik" id="topik" class="form-control input-sm">
                                    <?php if(!empty($select_topik)){ echo $select_topik; } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-2"></div>
                </div>
                <div class="box-footer">
                    <p class="text-muted"><i class="fa fa-info-circle"></i> Pilih terlebih dahulu Topik yang akan digunakan sebelum menambah atau mengubah soal.</p>
                </div>
            </div>
        </div>
    </div>
	<div class="row">
        <div class="col-xs-12">
                <div class="box box-success">
                    <?php echo form_open_multipart($url.'/tambah','id="form-tambah" class="form-horizontal"'); ?>
                        <div class="box-header with-border">
                            <div class="box-title"><i class="fa fa-pencil-square-o"></i> Mengelola Soal <span id="judul-tambah-soal"></span></div>
                        </div><!-- /.box-header -->

                        <div class="box-body">
                            <div id="form-pesan"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Soal</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="tambah-topik-id" id="tambah-topik-id" >
                                    <input type="hidden" name="tambah-soal-id" id="tambah-soal-id" >
                                    <input type="hidden" name="tambah-soal" id="tambah-soal" >
                                    
                                    <!-- Toolbar Bantuan Pembuatan Soal Modern TKA -->
                                    <div style="margin-bottom: 8px; background: #f8fafc; padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 4px;">
                                        <button type="button" class="btn btn-sm btn-info" onclick="bukaModalRumus('tambah_soal')" style="margin-right: 5px;">
                                            <i class="fa fa-calculator"></i> <b>Sisipkan Rumus Matematika (LaTeX)</b>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-default" onclick="imageUpload()" style="margin-right: 8px;">
                                            <i class="fa fa-image text-green"></i> <b>Kelola Gambar</b>
                                        </button>
                                        <span class="text-muted" style="font-size: 12px;">
                                            <i class="fa fa-lightbulb-o text-yellow"></i> Tip: Bisa copy & paste (Ctrl+V) gambar atau rumus langsung ke dalam editor.
                                        </span>
                                    </div>

                                    <textarea class="textarea" id="tambah_soal" name="tambah_soal" style="width: 100%; height: 160px; font-size: 14px; line-height: 25px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                    <p class="help-block">File gambar yang didukung: JPG, PNG, GIF. Gambar dapat dicopy-paste langsung atau diupload via tombol Kelola Gambar.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">File Audio (Listening)</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control input-sm" id="tambah-nama-audio" name="tambah-nama-audio" readonly placeholder="Nama file audio...">
                                </div>
                                <div class="col-sm-4">
                                    <input type="file" id="tambah-audio" name="tambah-audio" accept="audio/*">
                                    <p class="help-block">Format MP3. Untuk audio listening mata pelajaran Bahasa.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Putar Sekali</label>
                                <div class="col-sm-4">
                                    <select class="form-control input-sm" id="tambah-putar" name="tambah-putar">
                                        <option value="0">Tidak (Bisa diputar ulang)</option>
                                        <option value="1">Ya (Hanya 1 kali putar)</option>
                                    </select>
                                    <p class="help-block">Batasan pemutaran audio dalam satu tes.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tipe Soal</label>
                                <div class="col-sm-4">
                                    <select class="form-control input-sm" id="tambah-tipe" name="tambah-tipe">
                                        <option value="1">Pilihan Ganda (Single Choice)</option>
                                        <option value="4">Benar / Salah (True / False)</option>
                                        <option value="2">Uraian / Esai</option>
                                        <option value="3">Jawaban / Isian Singkat</option>
                                    </select>
                                    <p class="help-block">Standar penilaian otomatis berlaku untuk Pilihan Ganda, Benar/Salah, dan Isian Singkat.</p>
                                </div>
                                <label class="col-sm-2 control-label">Tingkat Kesulitan</label>
                                <div class="col-sm-4">
                                    <select class="form-control input-sm" id="tambah-kesulitan" name="tambah-kesulitan">
                                        <option value="1">1 - Sangat Mudah</option>
                                        <option value="2">2 - Mudah</option>
                                        <option value="3">3 - Sedang</option>
                                        <option value="4">4 - Sulit</option>
                                        <option value="5">5 - Sangat Sulit</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Form Kunci Benar / Salah (Khusus Tipe 4) -->
                            <div class="form-group hide" id="form-tambah-bs" style="background: #f0fdf4; padding: 12px 0; border-top: 1px dashed #86efac; border-bottom: 1px dashed #86efac;">
                                <label class="col-sm-2 control-label" style="color: #166534;"><i class="fa fa-check-circle"></i> Kunci Benar / Salah</label>
                                <div class="col-sm-6">
                                    <label class="radio-inline" style="font-size: 14px; font-weight: bold; color: #16a34a; cursor: pointer; padding-left: 25px;">
                                        <input type="radio" name="tambah-kunci-bs" id="tambah-kunci-bs-benar" value="1" checked style="margin-left: -22px; width: 18px; height: 18px;">
                                        <i class="fa fa-check"></i> PERNYATAAN BENAR
                                    </label>
                                    <label class="radio-inline" style="font-size: 14px; font-weight: bold; color: #dc2626; cursor: pointer; padding-left: 25px; margin-left: 25px;">
                                        <input type="radio" name="tambah-kunci-bs" id="tambah-kunci-bs-salah" value="0" style="margin-left: -22px; width: 18px; height: 18px;">
                                        <i class="fa fa-times"></i> PERNYATAAN SALAH
                                    </label>
                                    <p class="help-block" style="color: #15803d; margin-top: 8px;">
                                        Pilihan jawaban "BENAR" dan "SALAH" akan dibuat secara otomatis untuk peserta ujian. Anda cukup menentukan kunci jawaban yang tepat di sini.
                                    </p>
                                </div>
                            </div>

                            <!-- Form Kunci Jawaban Singkat (Khusus Tipe 3) -->
                            <div class="form-group hide" id="form-tambah-jawaban">
                                <label class="col-sm-2 control-label">Kunci Jawaban Singkat</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control input-sm" id="tambah-kunci-jawaban-singkat" name="tambah-kunci-jawaban-singkat" placeholder="Ketik kunci jawaban singkat...">
                                    <p class="help-block">
                                        Kunci Jawaban untuk Tipe Soal Jawaban Singkat.<br />
                                        Gunakan jawaban singkat dan padat untuk menghindari variasi penulisan peserta. Untuk angka desimal gunakan koma (,).
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" id="btn-tambah-simpan" class="btn btn-primary"><i class="fa fa-save"></i> <span id="judul-tambah-simpan">Simpan Soal</span></button>
                                    <button type="button" id="btn-tambah-batal" class="btn btn-default"><i class="fa fa-times"></i> <span>Batal</span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <div class="box-title"><i class="fa fa-list"></i> Daftar Soal: <span id="judul-daftar-soal"></span></div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-xs btn-default" onclick="refresh_table()"><i class="fa fa-refresh"></i> Refresh Data Soal</button>
                        </div>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <table id="table-soal" class="table table-bordered table-hover">
                            <thead>
                                <tr style="background-color: #f1f5f9;">
                                    <th style="width: 30px;">No.</th>
                                    <th>Soal & Kategori</th>
                                    <th style="width: 80px; text-align: center;">Jawaban</th>
                                    <th style="width: 90px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>                        
                    </div>
                </div>
        </div>
    </div>

    <!-- Modal Gambar -->
    <div class="modal" id="modal-image" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog" style="width: 950px">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <div id="trx-judul"><i class="fa fa-image"></i> Kelola & Sisipkan Gambar</div>
                </div>
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    <?php echo form_open_multipart($url.'/upload_file','id="form-upload-image" class="form-horizontal"'); ?>
                                        <div class="box">
                                            <div class="box-header with-border">
                                                <div class="box-title">Upload File Baru</div>
                                            </div><!-- /.box-header -->

                                            <div class="box-body">
                                                <div class="row-fluid">
                                                    <div class="box-body">
                                                        <div id="form-pesan-upload-image"></div>
                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">File</label>
                                                            <div class="col-sm-10">
                                                                <input type="hidden" id="image-topik-id" name="image-topik-id" >
                                                                <input type="file" id="image-file" name="image-file" accept="image/*">
                                                                <p class="help-block">File yang didukung: JPG, JPEG, PNG, GIF</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" id="image-upload" class="btn btn-primary"><i class="fa fa-upload"></i> Upload File</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-xs-6">
                                        <div class="box hide" id="box-preview" >
                                            <div class="box-body">
                                                <div class="row-fluid">
                                                    <div class="box-body" style="height: 132px;">
                                                        <input type="hidden" name="image-isi" id="image-isi">
                                                        <div id="image-preview" style="text-align: center;vertical-align: middle;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" id="btn-image-insert" class="btn btn-success"><i class="fa fa-plus-circle"></i> Masukkan Gambar ke Editor</button>
                                            </div>
                                        </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box">
                                        <div class="box-header with-border">
                                            <div class="box-title">Galeri Gambar Topik Ini</div>
                                        </div>
                                        <div class="box-body" style="max-height: 230px;overflow: auto;">
                                            <table id="table-image" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Nama File</th>
                                                        <th>Preview</th>
                                                        <th>Tanggal</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Soal -->
    <div class="modal" id="modal-hapus-soal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <?php echo form_open($url.'/hapus_soal','id="form-hapus-soal"'); ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <div id="trx-judul"><i class="fa fa-trash"></i> Konfirmasi Hapus Soal</div>
                </div>
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class="box-body">
                            <div id="form-pesan-hapus"></div>
                            <div class="form-group">
                                <label>Isi Soal</label>
                                <input type="hidden" name="hapus-id" id="hapus-id">
                                <div id="hapus-soal" style="max-height: 220px; overflow: auto; padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px;"></div>
                            </div>
                            <div class="alert alert-warning" style="margin-bottom: 0;">
                                <i class="fa fa-warning"></i> <b>Perhatian:</b> Menghapus soal ini akan sekaligus menghapus seluruh opsi jawaban yang terkait secara permanen.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-hapus-soal" class="btn btn-danger"><i class="fa fa-trash"></i> Ya, Hapus Soal</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </form>
    </div>

    <!-- Modal Rumus Matematika (KaTeX / LaTeX) -->
    <div class="modal" id="modal-rumus" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog" style="width: 860px; max-width: 95%;">
            <div class="modal-content">
                <div class="modal-header" style="background: #1e3a5f; color: #ffffff;">
                    <button class="close" type="button" data-dismiss="modal" style="color: #ffffff; opacity: 0.9;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-calculator"></i> <b>Editor Rumus Matematika & Sains (LaTeX / KaTeX)</b></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Kolom Tombol Simbol Cepat -->
                        <div class="col-xs-12">
                            <label style="font-size: 13px; color: #475569;"><i class="fa fa-hand-pointer-o"></i> Klik Simbol Cepat untuk Menyisipkan Template Rumus:</label>
                            <div style="background: #f1f5f9; padding: 10px; border-radius: 6px; margin-bottom: 12px; border: 1px solid #cbd5e1;">
                                <div style="margin-bottom: 6px;">
                                    <span class="text-muted" style="font-size: 11px; font-weight: bold; text-transform: uppercase;">Aritmetika & Aljabar:</span><br/>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\frac{a}{b}')" title="Pecahan">\frac{a}{b}</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\sqrt{x}')" title="Akar Kuadrat">\sqrt{x}</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\sqrt[n]{x}')" title="Akar Pangkat n">\sqrt[n]{x}</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('x^{2}')" title="Pangkat">x^{2}</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('x_{1}')" title="Subskrip">x_{1}</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\times')" title="Perkalian">\times</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\div')" title="Pembagian">\div</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\pm')" title="Plus Minus">\pm</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\approx')" title="Mendekati">\approx</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\neq')" title="Tidak Sama Dengan">\neq</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\le')" title="Kurang Dari Sama Dengan">\le</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\ge')" title="Lebih Dari Sama Dengan">\ge</button>
                                </div>
                                <div style="margin-bottom: 6px;">
                                    <span class="text-muted" style="font-size: 11px; font-weight: bold; text-transform: uppercase;">Kalkulus & Matriks:</span><br/>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\int_{a}^{b} f(x)\\,dx')" title="Integral Tertentu">\int_{a}^{b}</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\sum_{i=1}^{n} x_i')" title="Sigma / Penjumlahan">\sum</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\lim_{x \\to 0}')" title="Limit">\lim</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\infty')" title="Tak Hingga">\infty</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\begin{pmatrix} a & b \\\\ c & d \\end{pmatrix}')" title="Matriks 2x2">Matriks</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\vec{v}')" title="Vektor">\vec{v}</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\log_{10}(x)')" title="Logaritma">\log</button>
                                </div>
                                <div>
                                    <span class="text-muted" style="font-size: 11px; font-weight: bold; text-transform: uppercase;">Huruf Yunani & Kimia / Simbol:</span><br/>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\alpha')">\alpha</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\beta')">\beta</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\pi')">\pi</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\theta')">\theta</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\lambda')">\lambda</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\Delta')">\Delta</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\Omega')">\Omega</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\rightarrow')" title="Panah Kanan">\rightarrow</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\leftrightarrow')" title="Reaksi Bolak-balik">\leftrightarrow</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\text{H}_2\\text{O}')" title="Kimia Air">H_2O</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\text{CO}_2')" title="Kimia CO2">CO_2</button>
                                    <button type="button" class="btn btn-xs btn-default" onclick="tambahSimbol('\\degree')">^\circ</button>
                                </div>
                            </div>
                        </div>

                        <!-- Input Kode LaTeX -->
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="input-formula-latex"><b>Kode LaTeX Rumus:</b></label>
                                <textarea id="input-formula-latex" class="form-control" rows="3" style="font-family: monospace; font-size: 14px; background: #0f172a; color: #38bdf8;" placeholder="Contoh: x = \frac{-b \pm \sqrt{b^2 - 4ac}}{2a}"></textarea>
                            </div>
                        </div>

                        <!-- Live Preview Box -->
                        <div class="col-xs-12">
                            <label><b>Pratinjau Hasil Rumus (Live KaTeX Preview):</b></label>
                            <div id="formula-preview-box" style="min-height: 70px; background: #ffffff; border: 2px dashed #94a3b8; border-radius: 6px; padding: 15px; text-align: center; font-size: 19px; color: #0f172a; display: flex; align-items: center; justify-content: center;">
                                <span class="text-muted" style="font-size: 13px;"><i>Rumus yang Anda ketik akan langsung ditampilkan di sini...</i></span>
                            </div>
                        </div>

                        <!-- Format Mode -->
                        <div class="col-xs-12" style="margin-top: 10px;">
                            <label class="radio-inline" style="font-weight: normal; cursor: pointer;">
                                <input type="radio" name="formula-mode" value="inline" checked> <b>Inline Formula</b> <span class="text-muted">(Menyatu dengan kalimat teks, contoh: \( x^2 + y^2 = r^2 \))</span>
                            </label>
                            <br/>
                            <label class="radio-inline" style="font-weight: normal; cursor: pointer; margin-left: 0; margin-top: 5px;">
                                <input type="radio" name="formula-mode" value="block"> <b>Display / Block Formula</b> <span class="text-muted">(Berada di baris tersendiri di tengah, contoh: \[ \int_0^1 f(x) dx \])</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc;">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="sisipkanRumus()"><i class="fa fa-check-circle"></i> <b>Sisipkan Rumus ke Soal</b></button>
                </div>
            </div>
        </div>
    </div>
</section><!-- /.content -->

<script lang="javascript">
    var targetEditorInstance = 'tambah_soal';

    function refresh_table(){
        $('#table-soal').dataTable().fnReloadAjax();
    }

    function refresh_table_image(){
        $('#table-image').dataTable().fnReloadAjax();
    }
	
	function refresh_topik(){
        var judul = $('#topik option:selected').text();
        $('#judul-daftar-soal').html(judul);
        $('#judul-tambah-soal').html(judul);
	}

    function edit(id){
        $("#modal-proses").modal('show');
        $.getJSON('<?php echo site_url().'/'.$url; ?>/get_by_id/'+id+'', function(data){
            if(data.data==1){
                $("#form-pesan").html('');
                $('#tambah-soal').val('');
                CKEDITOR.instances.tambah_soal.setData(data.soal);
                $('#tambah-tipe').val(data.tipe);
                $('#tambah-topik-id').val(data.id_topik);
                $('#tambah-soal-id').val(data.id);
                $('#tambah-putar').val(data.putar);
                $('#tambah-audio').val('');
				$('#tambah-kesulitan').val(data.kesulitan);
                $('#tambah-nama-audio').val(data.audio);
                $('#topik').val(data.id_topik);

                // Pengkondisian tampilan tipe soal
                if(data.tipe==4){
                    $('#form-tambah-bs').removeClass('hide');
                    $('#form-tambah-jawaban').addClass('hide');
                    var kunci = (typeof data.kunci_bs !== 'undefined' && data.kunci_bs !== null) ? data.kunci_bs : 1;
                    if(kunci == 1){
                        $('#tambah-kunci-bs-benar').prop('checked', true);
                    }else{
                        $('#tambah-kunci-bs-salah').prop('checked', true);
                    }
                }else if(data.tipe==3){
                    $('#form-tambah-bs').addClass('hide');
                    $('#form-tambah-jawaban').removeClass('hide');
                    $('#tambah-kunci-jawaban-singkat').val(data.kunci);
                }else{
                    $('#form-tambah-bs').addClass('hide');
                    $('#form-tambah-jawaban').addClass('hide');
                }

                $('html, body').animate({
                    scrollTop: $("#form-tambah").offset().top
                }, 500);
            }
            $("#modal-proses").modal('hide');
        });
    }

    function hapus(id){
        $('#hapus-id').val('');
        $('#hapus-soal').html('');
        $('#form-pesan-hapus').html('');
        $("#modal-proses").modal('show');
        $.getJSON('<?php echo site_url().'/'.$url; ?>/get_by_id/'+id+'', function(data){
            if(data.data==1){
                $('#hapus-id').val(data.id);
                $('#hapus-soal').html(data.soal);
                if(typeof renderMathOffline === 'function'){
                    renderMathOffline(document.getElementById('hapus-soal'));
                }
				$("#modal-proses").modal('hide');
                $("#modal-hapus-soal").modal("show");
            }else{
				$("#modal-proses").modal('hide');
			}
        });
    }

    function jawaban(id){
        window.open('<?php echo site_url(); ?>/manager/modul_jawaban/index/'+id);
    }

    /**
     * Fungsi untuk upload image dari editor text
     */
    function imageUpload(){
        $('#box-preview').addClass('hide');
        $('#image-preview').html('');
        $('#form-pesan-upload-image').html('');
        $('#image-isi').val('');
        $('#image-file').val('');

        refresh_table_image();

        $("#modal-image").modal("show");
    }

    function image_preview(posisi, image){
        $('#image-preview').html('<img src="<?php echo base_url(); ?>'+posisi+'/'+image+'" style="max-height: 110px;" />');
        $('#image-isi').val('<img src="<?php echo base_url(); ?>'+posisi+'/'+image+'" style="max-width: 600px;" />');
        $('#box-preview').removeClass('hide');
    }

    function batal_tambah(){
        $("#form-pesan").html('');
        $('#tambah-topik-id').val('');
        $('#tambah-soal-id').val('');
        $('#tambah-soal').val('');
        $('#tambah-putar').val('0');
        $('#tambah-audio').val('');
        $('#tambah-nama-audio').val('');
        $('#tambah-tipe').val('1');
		$('#tambah-kesulitan').val('1');
        $('#tambah-kunci-jawaban-singkat').val('');
        $('#form-tambah-jawaban').addClass('hide');
        $('#form-tambah-bs').addClass('hide');
        $('#tambah-kunci-bs-benar').prop('checked', true);

        CKEDITOR.instances.tambah_soal.setData('');
    }

    /**
     * Fitur Rumus Matematika (KaTeX / LaTeX)
     */
    function bukaModalRumus(instanceName){
        targetEditorInstance = instanceName || 'tambah_soal';
        $('#input-formula-latex').val('');
        $('#formula-preview-box').html('<span class="text-muted" style="font-size: 13px;"><i>Rumus yang Anda ketik akan langsung ditampilkan di sini...</i></span>');
        $('#modal-rumus').modal('show');
        setTimeout(function(){
            $('#input-formula-latex').focus();
        }, 500);
    }

    function tambahSimbol(latex){
        var curVal = $('#input-formula-latex').val();
        if(curVal.length > 0 && !curVal.endsWith(' ')){
            curVal += ' ';
        }
        $('#input-formula-latex').val(curVal + latex + ' ').focus();
        updateFormulaPreview();
    }

    function updateFormulaPreview(){
        var code = $('#input-formula-latex').val().trim();
        if(!code){
            $('#formula-preview-box').html('<span class="text-muted" style="font-size: 13px;"><i>Rumus yang Anda ketik akan langsung ditampilkan di sini...</i></span>');
            return;
        }

        var isBlock = $('input[name="formula-mode"]:checked').val() === 'block';
        try {
            if(typeof katex !== 'undefined'){
                var html = katex.renderToString(code, {
                    displayMode: isBlock,
                    throwOnError: false
                });
                $('#formula-preview-box').html(html);
            } else {
                $('#formula-preview-box').text(code);
            }
        } catch(e) {
            $('#formula-preview-box').html('<span class="text-danger">' + e.message + '</span>');
        }
    }

    function sisipkanRumus(){
        var code = $('#input-formula-latex').val().trim();
        if(!code){
            notify_error('Silahkan ketikkan rumus atau pilih salah satu simbol.');
            return;
        }

        var isBlock = $('input[name="formula-mode"]:checked').val() === 'block';
        var wrapped = isBlock ? '$$\n' + code + '\n$$' : '\\(' + code + '\\)';

        if(CKEDITOR.instances[targetEditorInstance]){
            CKEDITOR.instances[targetEditorInstance].insertHtml(wrapped);
        }
        $('#modal-rumus').modal('hide');
        notify_success('Rumus berhasil disisipkan ke editor soal!');
    }

    $(function(){
        $('#topik').select2();
        
        $("#topik").change(function(){
            refresh_topik();
            refresh_table();
        });

        $('#input-formula-latex').on('input propertychange', function(){
            updateFormulaPreview();
        });

        $('input[name="formula-mode"]').change(function(){
            updateFormulaPreview();
        });

        $('#tambah-audio').change(function(e){
            if(e.target.files && e.target.files[0]){
                var fileName = e.target.files[0].name;
                $('#tambah-nama-audio').val(fileName);
            }
        });

        $('#btn-image-insert').click(function(){
            var image = $('#image-isi').val();
            CKEDITOR.instances.tambah_soal.insertHtml(image);
            $("#modal-image").modal("hide");
        });

        $('#tambah-tipe').change(function(e){
            var tipe = $('#tambah-tipe').val();

            if(tipe==4){
                $('#form-tambah-bs').removeClass('hide');
                $('#form-tambah-jawaban').addClass('hide');
            }else if(tipe==3){
                $('#form-tambah-bs').addClass('hide');
                $('#form-tambah-jawaban').removeClass('hide');
            }else{
                $('#form-tambah-bs').addClass('hide');
                $('#form-tambah-jawaban').addClass('hide');
            }
        });

        $('#btn-tambah-batal').click(function(){
            batal_tambah();
        });

        /**
         * Submit form tambah soal
         */
        $('#form-tambah').submit(function(){
            $('#tambah-soal').val(CKEDITOR.instances.tambah_soal.getData());
            $('#tambah-topik-id').val($('#topik').val());
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/tambah",
                    type:"POST",
                    timeout: 60000,
                    data:new FormData(this),
                    mimeType: "multipart/form-data",
                    contentType:false,
                    cache: false,
                    processData: false,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            refresh_table();
                            $("#modal-proses").modal('hide');
                            batal_tambah();
                            notify_success(obj.pesan);
                        }else{
                            $("#modal-proses").modal('hide');
                            $('#form-pesan').html(pesan_err(obj.pesan));
                        }
                    },
                    error: function(xmlhttprequest, textstatus, message) {
                        if(textstatus==="timeout") {
                            $("#modal-proses").modal('hide');
                            notify_error("Gagal menyimpan Soal, Silahkan Refresh Halaman");
                        }else{
                            $("#modal-proses").modal('hide');
                            notify_error(textstatus);
                        }
                    }
            });
            return false;
        });

        /**
         * Submit form hapus soal
         */
        $('#form-hapus-soal').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/hapus_soal",
                    type:"POST",
                    data:$('#form-hapus-soal').serialize(),
                    cache: false,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            refresh_table();
                            $("#modal-proses").modal('hide');
                            $("#form-pesan-hapus").html('');
                            $("#modal-hapus-soal").modal('hide');
                            notify_success(obj.pesan);
                        }else{
                            $("#modal-proses").modal('hide');
                            $('#form-pesan-hapus').html(pesan_err(obj.pesan));
                        }
                    }
            });
            return false;
        });

        /**
         * Submit form upload pada image browser
         */
        $('#form-upload-image').submit(function(){
            $('#image-topik-id').val($('#topik').val());
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/upload_file",
                    type:"POST",
                    data:new FormData(this),
                    mimeType: "multipart/form-data",
                    contentType:false,
                    cache: false,
                    processData: false,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            refresh_table();
                            $('#image-preview').html(obj.image);
                            $('#image-isi').val(obj.image_isi);
                            $('#box-preview').removeClass('hide');
                            $("#modal-proses").modal('hide');
                            $("#form-pesan-upload-image").html('');
                            $('#image-file').val('');
                            refresh_table_image();
                            notify_success(obj.pesan);
                        }else{
                            $("#modal-proses").modal('hide');
                            $('#form-pesan-upload-image').html(pesan_err(obj.pesan));
                        }
                    }
            });
            return false;
        });
		 
		$( document ).ready(function() {
            refresh_topik();
            $('#table-soal').DataTable({
                  "paging": true,
                  "iDisplayLength":10,
                  "bProcessing": false,
                  "bServerSide": true, 
                  "searching": true,
                  "aoColumns": [
                        {"bSearchable": false, "bSortable": false, "sWidth":"20px"},
                        {"bSearchable": false, "bSortable": false},
                        {"bSearchable": false, "bSortable": false, "sWidth":"80px"},
                        {"bSearchable": false, "bSortable": false, "sWidth":"90px"}],
                  "sAjaxSource": "<?php echo site_url().'/'.$url; ?>/get_datatable/",
                  "autoWidth": false,
                  "fnServerParams": function ( aoData ) {
                    aoData.push( { "name": "topik", "value": $('#topik').val()} );
                  },
                  "fnDrawCallback": function (oSettings) {
                    if(typeof renderMathOffline === 'function'){
                        renderMathOffline(document.getElementById('table-soal'));
                    }
                  }
            });

            $('#table-image').DataTable({
                  "bPaginate": false,
                  "bProcessing": false,
                  "bServerSide": true, 
                  "searching": false,
                  "aoColumns": [
                        {"bSearchable": false, "bSortable": false, "sWidth":"20px"},
                        {"bSearchable": false, "bSortable": false},
                        {"bSearchable": false, "bSortable": false, "sWidth":"100px"},
                        {"bSearchable": false, "bSortable": false, "sWidth":"90px"},
                        {"bSearchable": false, "bSortable": false, "sWidth":"50px"}],
                  "sAjaxSource": "<?php echo site_url().'/'.$url; ?>/get_datatable_image/",
                  "autoWidth": false,
                  "fnServerParams": function ( aoData ) {
                    aoData.push( { "name": "topik", "value": $('#topik').val()} );
                  }
            });

            CKEDITOR.replace('tambah_soal');

            <?php if(!empty($data_soal)){ echo $data_soal; } ?>
		});
    });
</script>
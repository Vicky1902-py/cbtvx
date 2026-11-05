<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Mengelola Jawaban
		<small>Mengelola opsi jawaban berdasarkan soal yang dipilih (Standar TKA Kemdikbud)</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url(); ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Jawaban</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <div class="box-title"><i class="fa fa-question-circle"></i> Data Soal <?php if(!empty($topik)){ echo ': '.$topik; } ?></div>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="col-xs-12" id="preview-data-soal" style="font-size: 15px; padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;">
                        <input type="hidden" name="topik" id="topik" value="<?php if(!empty($id_topik)){ echo $id_topik; } ?>" />
                        <?php if(!empty($soal)){ echo $soal; } ?>
                    </div>
                </div>
                <div class="box-footer">
                    <p class="text-muted"><i class="fa fa-info-circle"></i> Kelola pilihan jawaban untuk soal di atas. Tentukan satu atau lebih jawaban yang bernilai <b>BENAR</b> sebagai kunci jawaban.</p>
                </div>
            </div>
        </div>
    </div>
	<div class="row">
        <div class="col-xs-12">
                <div class="box box-success">
                    <?php echo form_open_multipart($url.'/tambah','id="form-tambah" class="form-horizontal"'); ?>
                        <div class="box-header with-border">
                            <div class="box-title"><i class="fa fa-pencil"></i> Form Tambah / Ubah Jawaban</div>
                        </div><!-- /.box-header -->

                        <div class="box-body">
                            <div id="form-pesan"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Isi Jawaban</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="tambah-soal-id" id="tambah-soal-id" value="<?php if(!empty($id_soal)){ echo $id_soal; } ?>">
                                    <input type="hidden" name="tambah-jawaban-id" id="tambah-jawaban-id" >
                                    <input type="hidden" name="tambah-jawaban" id="tambah-jawaban" >
                                    
                                    <!-- Toolbar Formula & Gambar -->
                                    <div style="margin-bottom: 8px; background: #f8fafc; padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 4px;">
                                        <button type="button" class="btn btn-sm btn-info" onclick="bukaModalRumus('tambah_jawaban')" style="margin-right: 5px;">
                                            <i class="fa fa-calculator"></i> <b>Sisipkan Rumus Matematika (LaTeX)</b>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-default" onclick="imageUpload()" style="margin-right: 8px;">
                                            <i class="fa fa-image text-green"></i> <b>Kelola Gambar</b>
                                        </button>
                                        <span class="text-muted" style="font-size: 12px;">
                                            <i class="fa fa-lightbulb-o text-yellow"></i> Tip: Bisa copy & paste (Ctrl+V) teks rumus atau gambar langsung ke dalam editor.
                                        </span>
                                    </div>

                                    <textarea class="textarea" id="tambah_jawaban" name="tambah_jawaban" style="width: 100%; height: 120px; font-size: 14px; line-height: 25px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                    <p class="help-block">Tuliskan teks opsi jawaban. Gambar dan rumus matematika didukung penuh secara offline.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status Kunci Jawaban</label>
                                <div class="col-sm-4">
                                    <select class="form-control input-sm" id="tambah-benar" name="tambah-benar">
                                        <option value="0">SALAH (Bukan Kunci)</option>
                                        <option value="1">BENAR (Kunci Jawaban)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" id="btn-tambah-simpan" class="btn btn-primary"><i class="fa fa-save"></i> <span id="judul-tambah-simpan">Simpan Jawaban</span></button>
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
                        <div class="box-title"><i class="fa fa-list-ol"></i> Daftar Pilihan Jawaban</div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-xs btn-default" onclick="refresh_table()"><i class="fa fa-refresh"></i> Refresh</button>
                        </div>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <table id="table-jawaban" class="table table-bordered table-hover">
                            <thead>
                                <tr style="background: #f1f5f9;">
                                    <th style="width: 30px;">No.</th>
                                    <th>Pilihan Jawaban</th>
                                    <th style="width: 120px; text-align: center;">Status Kunci</th>
                                    <th style="width: 80px; text-align: center;">Aksi</th>
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

    <!-- Modal Image -->
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
                                                <div class="box-title">Upload File Gambar</div>
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
                                                <button type="button" id="btn-image-insert" class="btn btn-success"><i class="fa fa-plus-circle"></i> Masukkan Gambar</button>
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

    <!-- Modal Hapus Jawaban -->
    <div class="modal" id="modal-hapus-jawaban" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <?php echo form_open($url.'/hapus_jawaban','id="form-hapus-jawaban"'); ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                    <div id="trx-judul"><i class="fa fa-trash"></i> Konfirmasi Hapus Jawaban</div>
                </div>
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class="box-body">
                            <div id="form-pesan-hapus"></div>
                            <div class="form-group">
                                <label>Opsi Jawaban</label>
                                <input type="hidden" name="hapus-id" id="hapus-id">
                                <div id="hapus-jawaban" style="max-height: 200px; overflow: auto; padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px;"></div>
                            </div>
                            <p class="text-danger"><i class="fa fa-warning"></i> Yakin ingin menghapus opsi jawaban ini?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-hapus-jawaban" class="btn btn-danger"><i class="fa fa-trash"></i> Ya, Hapus</button>
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
                                <input type="radio" name="formula-mode" value="inline" checked> <b>Inline Formula</b> <span class="text-muted">(Menyatu dengan opsi pilihan, contoh: \( x^2 + 1 \))</span>
                            </label>
                            <br/>
                            <label class="radio-inline" style="font-weight: normal; cursor: pointer; margin-left: 0; margin-top: 5px;">
                                <input type="radio" name="formula-mode" value="block"> <b>Display / Block Formula</b> <span class="text-muted">(Berada di tengah baris)</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc;">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="sisipkanRumus()"><i class="fa fa-check-circle"></i> <b>Sisipkan Rumus ke Jawaban</b></button>
                </div>
            </div>
        </div>
    </div>
</section><!-- /.content -->

<script lang="javascript">
    var targetEditorInstance = 'tambah_jawaban';

    function refresh_table(){
        $('#table-jawaban').dataTable().fnReloadAjax();
    }

    function refresh_table_image(){
        $('#table-image').dataTable().fnReloadAjax();
    }

    function edit(id){
        $("#modal-proses").modal('show');
        $.getJSON('<?php echo site_url().'/'.$url; ?>/get_by_id/'+id+'', function(data){
            if(data.data==1){
                $("#form-pesan").html('');
                $('#tambah-jawaban').val('');
                CKEDITOR.instances.tambah_jawaban.setData(data.jawaban);
                $('#tambah-benar').val(data.benar);
                $('#tambah-jawaban-id').val(data.id);

                $('html, body').animate({
                    scrollTop: $("#form-tambah").offset().top
                }, 500);
            }
            $("#modal-proses").modal('hide');
        });
    }

    function hapus(id){
        $('#hapus-id').val('');
        $('#hapus-jawaban').html('');
        $('#form-pesan-hapus').html('');
        $("#modal-proses").modal('show');
        $.getJSON('<?php echo site_url().'/'.$url; ?>/get_by_id/'+id+'', function(data){
            if(data.data==1){
                $('#hapus-id').val(data.id);
                $('#hapus-jawaban').html(data.jawaban);
                if(typeof renderMathOffline === 'function'){
                    renderMathOffline(document.getElementById('hapus-jawaban'));
                }
				$("#modal-proses").modal('hide');
                $("#modal-hapus-jawaban").modal("show");
            }else{
				$("#modal-proses").modal('hide');
			}
        });
    }

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
        $('#tambah-jawaban-id').val('');
        $('#tambah-jawaban').val('');
        $('#tambah-benar').val('0');

        CKEDITOR.instances.tambah_jawaban.setData('');
    }

    /**
     * Fitur Rumus Matematika (KaTeX / LaTeX)
     */
    function bukaModalRumus(instanceName){
        targetEditorInstance = instanceName || 'tambah_jawaban';
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
        notify_success('Rumus berhasil disisipkan ke pilihan jawaban!');
    }

    $(function(){
        $('#input-formula-latex').on('input propertychange', function(){
            updateFormulaPreview();
        });

        $('input[name="formula-mode"]').change(function(){
            updateFormulaPreview();
        });

        $('#btn-image-insert').click(function(){
            var image = $('#image-isi').val();
            CKEDITOR.instances.tambah_jawaban.insertHtml(image);
            $("#modal-image").modal("hide");
        });

        $('#btn-tambah-batal').click(function(){
            batal_tambah();
        });

        $('#form-tambah').submit(function(){
            $('#tambah-jawaban').val(CKEDITOR.instances.tambah_jawaban.getData());
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
                            notify_error("Gagal menyimpan Jawaban, Silahkan Refresh Halaman");
                        }else{
                            $("#modal-proses").modal('hide');
                            notify_error(textstatus);
                        }
                    }
            });
            return false;
        });

        $('#form-hapus-jawaban').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/hapus_jawaban",
                    type:"POST",
                    data:$('#form-hapus-jawaban').serialize(),
                    cache: false,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            refresh_table();
                            $("#modal-proses").modal('hide');
                            $("#form-pesan-hapus").html('');
                            $("#modal-hapus-jawaban").modal('hide');
                            notify_success(obj.pesan);
                        }else{
                            $("#modal-proses").modal('hide');
                            $('#form-pesan-hapus').html(pesan_err(obj.pesan));
                        }
                    }
            });
            return false;
        });

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
            if(typeof renderMathOffline === 'function'){
                renderMathOffline(document.getElementById('preview-data-soal'));
            }

            $('#table-jawaban').DataTable({
                  "paging": true,
                  "iDisplayLength":10,
                  "bProcessing": false,
                  "bServerSide": true, 
                  "searching": true,
                  "aoColumns": [
                        {"bSearchable": false, "bSortable": false, "sWidth":"20px"},
                        {"bSearchable": false, "bSortable": false},
                        {"bSearchable": false, "bSortable": false, "sWidth":"100px"},
                        {"bSearchable": false, "bSortable": false, "sWidth":"50px"}],
                  "sAjaxSource": "<?php echo site_url().'/'.$url; ?>/get_datatable/",
                  "autoWidth": false,
                  "fnServerParams": function ( aoData ) {
                    aoData.push( { "name": "soal", "value": $('#tambah-soal-id').val()} );
                  },
                  "fnDrawCallback": function (oSettings) {
                    if(typeof renderMathOffline === 'function'){
                        renderMathOffline(document.getElementById('table-jawaban'));
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

            CKEDITOR.replace('tambah_jawaban');
		});
    });
</script>
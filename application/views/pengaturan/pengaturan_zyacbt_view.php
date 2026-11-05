<!-- Content Header (Page header) -->
<section class="content-header" style="margin-bottom: 10px;">
	<h1 style="font-size: 22px; font-weight: 800; color: #001233;">
		Pengaturan CBT & Slideshow
		<small style="font-size: 13px; color: #64748b;">Pengaturan Identitas Sekolah & Slideshow Gambar Halaman Login</small>
	</h1>
	<ol class="breadcrumb" style="background: transparent;">
		<li><a href="<?php echo site_url(); ?>/manager"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Pengaturan CBT</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
        <div class="col-xs-12">
			<?php echo form_open_multipart($url.'/simpan','id="form-pengaturan"'); ?>
				<div id="form-pesan" style="margin-bottom: 16px;"></div>

                <!-- Box 1: Identitas Sekolah & Sistem CBT -->
                <div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
                    <div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
    					<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
							<i class="fa fa-university text-blue" style="margin-right: 8px;"></i> Identitas Sekolah & Sistem Asesmen
						</div>
                    </div>

                    <div class="box-body form-horizontal" style="padding: 24px;">
                        <div class="form-group">
							<label class="col-sm-3 control-label">Nama Sekolah / CBT</label>
                            <div class="col-sm-9">
								<input type="text" class="form-control" id="zyacbt-nama" name="zyacbt-nama" placeholder="SMK Negeri 1 Kupang Barat">
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Nama identitas resmi sekolah penyelenggara CBT.</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Slogan / Keterangan</label>
                            <div class="col-sm-9">
								<input type="text" class="form-control" id="zyacbt-keterangan" name="zyacbt-keterangan" placeholder="CBT Modern Standar TKA Pusmendik">
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Keterangan atau semboyan yang muncul di header dan footer.</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Link Login Operator</label>
                            <div class="col-sm-9">
								<select class="form-control" id="zyacbt-link-login" name="zyacbt-link-login" style="max-width: 200px;">
									<option value="tidak">Tidak Ditampilkan</option>
                                    <option value="ya">Tampilkan di Halaman Siswa</option>
								</select>
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Menampilkan tombol pintas ke Portal Operator pada halaman siswa.</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Kunci Mobile Exam Browser</label>
                            <div class="col-sm-9">
								<select class="form-control" id="zyacbt-mobile-lock-xambro" name="zyacbt-mobile-lock-xambro" style="max-width: 200px;">
									<option value="tidak">Bebas (Semua Browser)</option>
                                    <option value="ya">Khusus Exambro Mobile</option>
								</select>
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Kunci akses peserta di Android agar hanya bisa dibuka lewat Exam Browser.</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Pengumuman Peserta Tes</label>
                            <div class="col-sm-9">
								<input type="hidden" name="zyacbt-informasi" id="zyacbt-informasi" >
								<textarea class="textarea" id="zyacbt_informasi" name="zyacbt_informasi" style="width: 100%; height: 120px; font-size: 13px; line-height: 22px; border: 1px solid #cbd5e1; padding: 10px; border-radius: 8px;"></textarea>
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Instruksi/pengumuman yang ditampilkan di dashboard siswa sebelum memilih ujian.</p>
							</div>
						</div>
                    </div>
                </div>

				<!-- Box 2: Pengaturan Slideshow Gambar Halaman Login -->
				<div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
					<div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
						<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
							<i class="fa fa-picture-o text-green" style="margin-right: 8px;"></i> Slideshow Gambar Halaman Login (Samping Form)
						</div>
					</div>

					<div class="box-body" style="padding: 24px;">
						<p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">
							Atur gambar, judul, dan deskripsi slide yang tampil di sebelah kiri formulir login peserta dan operator. Anda dapat mengunggah file foto/gambar baru atau memasukkan URL gambar.
						</p>

						<div class="row">
							<!-- SLIDE 1 -->
							<div class="col-md-4 col-sm-12" style="margin-bottom: 20px;">
								<div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 18px;">
									<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
										<span class="label" style="background: #0077b6; font-size: 12px; padding: 4px 10px; border-radius: 8px;">SLIDE 1</span>
									</div>
									<div style="margin-bottom: 12px; border-radius: 10px; overflow: hidden; height: 150px; background: #001233; position: relative;">
										<img id="preview-slide1" src="<?php echo base_url('public/images/slides/slide1.jpg'); ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Slide 1 Preview">
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #334155;">Judul Slide 1</label>
										<input type="text" class="form-control input-sm" id="slide1_judul" name="slide1_judul" placeholder="Judul slide 1">
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #334155;">Deskripsi Slide 1</label>
										<textarea class="form-control input-sm" id="slide1_deskripsi" name="slide1_deskripsi" rows="2" placeholder="Deskripsi singkat slide 1"></textarea>
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #334155;">Upload Foto Baru</label>
										<input type="file" class="form-control input-sm" id="slide1_file" name="slide1_file" accept="image/*" onchange="previewImg(this, '#preview-slide1')">
									</div>
									<input type="hidden" id="slide1_gambar" name="slide1_gambar">
								</div>
							</div>

							<!-- SLIDE 2 -->
							<div class="col-md-4 col-sm-12" style="margin-bottom: 20px;">
								<div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 18px;">
									<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
										<span class="label" style="background: #0096c7; font-size: 12px; padding: 4px 10px; border-radius: 8px;">SLIDE 2</span>
									</div>
									<div style="margin-bottom: 12px; border-radius: 10px; overflow: hidden; height: 150px; background: #001233; position: relative;">
										<img id="preview-slide2" src="<?php echo base_url('public/images/slides/slide2.jpg'); ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Slide 2 Preview">
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #334155;">Judul Slide 2</label>
										<input type="text" class="form-control input-sm" id="slide2_judul" name="slide2_judul" placeholder="Judul slide 2">
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #334155;">Deskripsi Slide 2</label>
										<textarea class="form-control input-sm" id="slide2_deskripsi" name="slide2_deskripsi" rows="2" placeholder="Deskripsi singkat slide 2"></textarea>
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #334155;">Upload Foto Baru</label>
										<input type="file" class="form-control input-sm" id="slide2_file" name="slide2_file" accept="image/*" onchange="previewImg(this, '#preview-slide2')">
									</div>
									<input type="hidden" id="slide2_gambar" name="slide2_gambar">
								</div>
							</div>

							<!-- SLIDE 3 -->
							<div class="col-md-4 col-sm-12" style="margin-bottom: 20px;">
								<div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 18px;">
									<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
										<span class="label" style="background: #059669; font-size: 12px; padding: 4px 10px; border-radius: 8px;">SLIDE 3</span>
									</div>
									<div style="margin-bottom: 12px; border-radius: 10px; overflow: hidden; height: 150px; background: #001233; position: relative;">
										<img id="preview-slide3" src="<?php echo base_url('public/images/slides/slide3.jpg'); ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Slide 3 Preview">
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #334155;">Judul Slide 3</label>
										<input type="text" class="form-control input-sm" id="slide3_judul" name="slide3_judul" placeholder="Judul slide 3">
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #334155;">Deskripsi Slide 3</label>
										<textarea class="form-control input-sm" id="slide3_deskripsi" name="slide3_deskripsi" rows="2" placeholder="Deskripsi singkat slide 3"></textarea>
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #334155;">Upload Foto Baru</label>
										<input type="file" class="form-control input-sm" id="slide3_file" name="slide3_file" accept="image/*" onchange="previewImg(this, '#preview-slide3')">
									</div>
									<input type="hidden" id="slide3_gambar" name="slide3_gambar">
								</div>
							</div>
						</div>
					</div>

					<div class="box-footer" style="padding: 16px 24px; background: #fafafa; border-top: 1px solid #e2e8f0; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
						<button type="submit" id="btn-simpan" class="btn btn-primary pull-right" style="border-radius: 10px; padding: 10px 28px; font-weight: 700; background: linear-gradient(135deg, #0077b6 0%, #00b4d8 100%); border: none;">
							<i class="fa fa-save" style="margin-right: 6px;"></i> Simpan Semua Pengaturan
						</button>
					</div>
				</div>
			</form>
        </div>
    </div>
</section><!-- /.content -->

<script lang="javascript">
	function previewImg(input, target) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$(target).attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	function load_data(){
        $("#modal-proses").modal('show');
        $.getJSON('<?php echo site_url().'/'.$url; ?>/get_pengaturan_zyacbt', function(data){
            if(data.data==1){
                $('#zyacbt-nama').val(data.cbt_nama);
                $('#zyacbt-keterangan').val(data.cbt_keterangan);
                $('#zyacbt-link-login').val(data.link_login_operator);
				$('#zyacbt-mobile-lock-xambro').val(data.mobile_lock_xambro);
				$('#zyacbt_informasi').val(data.cbt_informasi);
				$('#zyacbt-informasi').val('');

				// Slideshow data
				$('#slide1_judul').val(data.slide1_judul);
				$('#slide1_deskripsi').val(data.slide1_deskripsi);
				$('#slide1_gambar').val(data.slide1_gambar);
				if(data.slide1_gambar) $('#preview-slide1').attr('src', data.slide1_gambar);

				$('#slide2_judul').val(data.slide2_judul);
				$('#slide2_deskripsi').val(data.slide2_deskripsi);
				$('#slide2_gambar').val(data.slide2_gambar);
				if(data.slide2_gambar) $('#preview-slide2').attr('src', data.slide2_gambar);

				$('#slide3_judul').val(data.slide3_judul);
				$('#slide3_deskripsi').val(data.slide3_deskripsi);
				$('#slide3_gambar').val(data.slide3_gambar);
				if(data.slide3_gambar) $('#preview-slide3').attr('src', data.slide3_gambar);
            }
            $("#modal-proses").modal('hide');
        });
    }

    $(function(){
		CKEDITOR.replace('zyacbt_informasi');
		
		load_data();
        $('#form-pengaturan').submit(function(e){
			e.preventDefault();
            $("#modal-proses").modal('show');
			$('#zyacbt-informasi').val(CKEDITOR.instances.zyacbt_informasi.getData());

			var formData = new FormData(this);
			formData.set('zyacbt-informasi', CKEDITOR.instances.zyacbt_informasi.getData());

            $.ajax({
				url: "<?php echo site_url().'/'.$url; ?>/simpan",
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				cache: false,
				dataType: "json",
				success: function(obj){
					$("#modal-proses").modal('hide');
					if(obj.status==1){
						notify_success(obj.pesan);
					}else{
						$('#form-pesan').html(pesan_err(obj.pesan));
					}
				},
				error: function(){
					$("#modal-proses").modal('hide');
					notify_error("Terjadi kesalahan koneksi server saat menyimpan.");
				}
            });
            return false;
        });
    });
</script>

<!-- Content Header (Page header) -->
<section class="content-header" style="margin-bottom: 15px;">
	<h1 style="font-size: 22px; font-weight: 800; color: #001233;">
		Pengaturan Identitas Sekolah & CBT
		<small style="font-size: 13px; color: #64748b;">Kop Surat Kartu Peserta, Data Kepala Sekolah, dan Slideshow Login</small>
	</h1>
	<ol class="breadcrumb" style="background: transparent;">
		<li><a href="<?php echo site_url(); ?>/manager"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Pengaturan Sekolah & CBT</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
        <div class="col-xs-12">
			<?php echo form_open_multipart($url.'/simpan','id="form-pengaturan"'); ?>
				<div id="form-pesan" style="margin-bottom: 16px;"></div>

                <!-- BOX 1: IDENTITAS RESMI SEKOLAH & KOP SURAT KARTU UJIAN -->
                <div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                    <div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
    					<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
							<i class="fa fa-university text-blue" style="margin-right: 8px;"></i> Identitas Sekolah & Kop Resmi (Kartu Ujian / Dokumen)
						</div>
                    </div>

                    <div class="box-body form-horizontal" style="padding: 24px;">
                        <div class="form-group">
							<label class="col-sm-3 control-label">Pemerintah Provinsi</label>
                            <div class="col-sm-9">
								<input type="text" class="form-control" id="sekolah_provinsi" name="sekolah_provinsi" placeholder="PEMERINTAH PROVINSI NUSA TENGGARA TIMUR">
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Teks baris 1 Kop Surat Kartu Peserta.</p>
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Dinas Pendidikan</label>
                            <div class="col-sm-9">
								<input type="text" class="form-control" id="sekolah_dinas" name="sekolah_dinas" placeholder="DINAS PENDIDIKAN DAN KEBUDAYAAN">
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Teks baris 2 Kop Surat Kartu Peserta.</p>
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Nama Satuan Pendidikan / Sekolah</label>
                            <div class="col-sm-9">
								<input type="text" class="form-control" id="zyacbt-nama" name="zyacbt-nama" placeholder="SMK NEGERI 1 KUPANG BARAT">
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Nama resmi sekolah (baris 3 Kop Surat dan Judul CBT).</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Alamat / Jalan / Desa</label>
                            <div class="col-sm-9">
								<input type="text" class="form-control" id="sekolah_alamat" name="sekolah_alamat" placeholder="Jln. Jurusan Batakte - Kuanheun, Desa Kuanheun">
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Lokasi definitif sekolah (SMKN 1 Kupang Barat berada di <b>Desa Kuanheun</b>).</p>
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Kecamatan & Kabupaten/Kota</label>
                            <div class="col-sm-4">
								<input type="text" class="form-control" id="sekolah_kecamatan" name="sekolah_kecamatan" placeholder="Kec. Kupang Barat">
							</div>
                            <div class="col-sm-5">
								<input type="text" class="form-control" id="sekolah_kota" name="sekolah_kota" placeholder="Kab. Kupang">
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Titimangsa Cetak Kartu</label>
                            <div class="col-sm-9">
								<input type="text" class="form-control" id="sekolah_titimangsa" name="sekolah_titimangsa" placeholder="Kuanheun" style="max-width: 250px;">
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Tempat penandatanganan pada kartu ujian (contoh: <b>Kuanheun</b>).</p>
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Email & Website</label>
                            <div class="col-sm-4">
								<input type="text" class="form-control" id="sekolah_email" name="sekolah_email" placeholder="smkn1kupangbarat@gmail.com">
							</div>
                            <div class="col-sm-5">
								<input type="text" class="form-control" id="sekolah_website" name="sekolah_website" placeholder="https://bikinaplikasi.my.id">
							</div>
						</div>
                    </div>
                </div>

                <!-- BOX 2: DATA KEPALA SEKOLAH & LOGO PENGESAHAN -->
                <div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                    <div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
    					<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
							<i class="fa fa-user-secret text-blue" style="margin-right: 8px;"></i> Data Kepala Sekolah & Logo Pengesahan Kartu
						</div>
                    </div>

                    <div class="box-body form-horizontal" style="padding: 24px;">
                        <div class="form-group">
							<label class="col-sm-3 control-label">Nama Kepala Sekolah</label>
                            <div class="col-sm-9">
								<input type="text" class="form-control" id="sekolah_kepsek_nama" name="sekolah_kepsek_nama" placeholder="Nama Kepala Sekolah beserta Gelar">
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Nama yang dicantumkan pada tanda tangan kartu login peserta ujian.</p>
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">NIP Kepala Sekolah</label>
                            <div class="col-sm-9">
								<input type="text" class="form-control" id="sekolah_kepsek_nip" name="sekolah_kepsek_nip" placeholder="19XXXXXXXX XXXXXX X XXX atau -">
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Nomor Induk Pegawai Kepala Sekolah (isi tanda - jika belum ada).</p>
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Tanda Tangan & Stempel Kepsek</label>
                            <div class="col-sm-9">
								<div style="display: flex; align-items: center; gap: 16px; margin-bottom: 8px;">
									<img id="preview-ttd-kepsek" src="<?php echo base_url('public/images/ttd_kepsek.png'); ?>" style="height: 60px; max-width: 180px; object-fit: contain; border: 1px dashed #cbd5e1; padding: 4px; border-radius: 8px; background: #fafafa;">
									<input type="file" name="ttd_kepsek_file" id="ttd_kepsek_file" class="form-control" accept="image/png, image/jpeg" style="max-width: 320px;" onchange="previewImg(this, 'preview-ttd-kepsek')">
								</div>
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Format PNG transparan atau JPG. Ditampilkan otomatis pada kartu peserta.</p>
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Logo Pemprov NTT (Kiri Kop)</label>
                            <div class="col-sm-9">
								<div style="display: flex; align-items: center; gap: 16px; margin-bottom: 8px;">
									<img id="preview-logo-prov" src="<?php echo base_url('public/images/logo_pemprov_ntt.svg'); ?>" style="height: 55px; width: 55px; object-fit: contain; border: 1px dashed #cbd5e1; padding: 4px; border-radius: 8px; background: #fafafa;">
									<input type="file" name="logo_provinsi_file" id="logo_provinsi_file" class="form-control" accept="image/png, image/jpeg, image/svg+xml" style="max-width: 320px;" onchange="previewImg(this, 'preview-logo-prov')">
								</div>
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Logo Pemerintah Provinsi Nusa Tenggara Timur di sisi kiri kop surat.</p>
							</div>
						</div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Logo Sekolah (Kanan Kop)</label>
                            <div class="col-sm-9">
								<div style="display: flex; align-items: center; gap: 16px; margin-bottom: 8px;">
									<img id="preview-logo-sek" src="<?php echo base_url('public/images/logo_smk.png'); ?>" style="height: 55px; width: 55px; object-fit: contain; border: 1px dashed #cbd5e1; padding: 4px; border-radius: 8px; background: #fafafa;">
									<input type="file" name="logo_sekolah_file" id="logo_sekolah_file" class="form-control" accept="image/png, image/jpeg" style="max-width: 320px;" onchange="previewImg(this, 'preview-logo-sek')">
								</div>
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Logo resmi SMKN 1 Kupang Barat di sisi kanan kop surat.</p>
							</div>
						</div>
                    </div>
                </div>

                <!-- BOX 3: KONFIGURASI SISTEM CBT -->
                <div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                    <div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
    					<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
							<i class="fa fa-cogs text-blue" style="margin-right: 8px;"></i> Konfigurasi Sistem Asesmen CBT
						</div>
                    </div>

                    <div class="box-body form-horizontal" style="padding: 24px;">
						<div class="form-group">
							<label class="col-sm-3 control-label">Slogan / Keterangan Sistem</label>
                            <div class="col-sm-9">
								<input type="text" class="form-control" id="zyacbt-keterangan" name="zyacbt-keterangan" placeholder="CBT Modern Standar TKA Pusmendik">
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Keterangan atau semboyan yang muncul di header dan footer sistem.</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Link Login Operator</label>
                            <div class="col-sm-9">
								<select class="form-control" id="zyacbt-link-login" name="zyacbt-link-login" style="max-width: 250px;">
									<option value="tidak">Tidak Ditampilkan</option>
                                    <option value="ya">Tampilkan di Halaman Siswa</option>
								</select>
                                <p class="help-block" style="font-size: 12px; color: #64748b;">Menampilkan tombol pintas ke Portal Operator pada halaman siswa.</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Kunci Mobile Exam Browser</label>
                            <div class="col-sm-9">
								<select class="form-control" id="zyacbt-mobile-lock-xambro" name="zyacbt-mobile-lock-xambro" style="max-width: 250px;">
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

				<!-- BOX 4: PENGATURAN SLIDESHOW HALAMAN LOGIN -->
				<div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
					<div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
						<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
							<i class="fa fa-picture-o text-blue" style="margin-right: 8px;"></i> Slideshow Gambar Halaman Login (Berita & Aktivitas Sekolah)
						</div>
					</div>
					<div class="box-body" style="padding: 24px;">
						<p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">
							Kelola 3 gambar banner dan teks narasi yang tampil bergantian otomatis pada sisi kiri halaman login.
						</p>

						<div class="row">
							<!-- SLIDE 1 -->
							<div class="col-md-4">
								<div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; background: #ffffff; margin-bottom: 15px;">
									<div style="font-weight: 800; font-size: 14px; color: #0077b6; margin-bottom: 10px;">
										<span class="badge bg-blue" style="margin-right: 4px;">1</span> Banner Slide 1
									</div>
									<div style="height: 150px; background: #03045e; border-radius: 8px; overflow: hidden; margin-bottom: 12px; position: relative; border: 1px solid #cbd5e1;">
										<img id="preview-slide-1" src="" style="width: 100%; height: 100%; object-fit: cover;">
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #1e293b;">Ganti Gambar</label>
										<input type="file" name="slide1_file" id="slide1_file" class="form-control input-sm" accept="image/*" onchange="previewImg(this, 'preview-slide-1')">
										<input type="hidden" name="slide1_gambar" id="slide1_gambar">
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #1e293b;">Judul Slide 1</label>
										<input type="text" name="slide1_judul" id="slide1_judul" class="form-control input-sm" placeholder="Judul Slide 1">
									</div>
									<div class="form-group" style="margin-bottom: 0;">
										<label style="font-size: 12px; font-weight: 700; color: #1e293b;">Deskripsi Narasi</label>
										<textarea name="slide1_deskripsi" id="slide1_deskripsi" class="form-control input-sm" rows="3" placeholder="Deskripsi ringkas"></textarea>
									</div>
								</div>
							</div>

							<!-- SLIDE 2 -->
							<div class="col-md-4">
								<div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; background: #ffffff; margin-bottom: 15px;">
									<div style="font-weight: 800; font-size: 14px; color: #0077b6; margin-bottom: 10px;">
										<span class="badge bg-blue" style="margin-right: 4px;">2</span> Banner Slide 2
									</div>
									<div style="height: 150px; background: #03045e; border-radius: 8px; overflow: hidden; margin-bottom: 12px; position: relative; border: 1px solid #cbd5e1;">
										<img id="preview-slide-2" src="" style="width: 100%; height: 100%; object-fit: cover;">
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #1e293b;">Ganti Gambar</label>
										<input type="file" name="slide2_file" id="slide2_file" class="form-control input-sm" accept="image/*" onchange="previewImg(this, 'preview-slide-2')">
										<input type="hidden" name="slide2_gambar" id="slide2_gambar">
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #1e293b;">Judul Slide 2</label>
										<input type="text" name="slide2_judul" id="slide2_judul" class="form-control input-sm" placeholder="Judul Slide 2">
									</div>
									<div class="form-group" style="margin-bottom: 0;">
										<label style="font-size: 12px; font-weight: 700; color: #1e293b;">Deskripsi Narasi</label>
										<textarea name="slide2_deskripsi" id="slide2_deskripsi" class="form-control input-sm" rows="3" placeholder="Deskripsi ringkas"></textarea>
									</div>
								</div>
							</div>

							<!-- SLIDE 3 -->
							<div class="col-md-4">
								<div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; background: #ffffff; margin-bottom: 15px;">
									<div style="font-weight: 800; font-size: 14px; color: #0077b6; margin-bottom: 10px;">
										<span class="badge bg-blue" style="margin-right: 4px;">3</span> Banner Slide 3
									</div>
									<div style="height: 150px; background: #03045e; border-radius: 8px; overflow: hidden; margin-bottom: 12px; position: relative; border: 1px solid #cbd5e1;">
										<img id="preview-slide-3" src="" style="width: 100%; height: 100%; object-fit: cover;">
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #1e293b;">Ganti Gambar</label>
										<input type="file" name="slide3_file" id="slide3_file" class="form-control input-sm" accept="image/*" onchange="previewImg(this, 'preview-slide-3')">
										<input type="hidden" name="slide3_gambar" id="slide3_gambar">
									</div>
									<div class="form-group" style="margin-bottom: 10px;">
										<label style="font-size: 12px; font-weight: 700; color: #1e293b;">Judul Slide 3</label>
										<input type="text" name="slide3_judul" id="slide3_judul" class="form-control input-sm" placeholder="Judul Slide 3">
									</div>
									<div class="form-group" style="margin-bottom: 0;">
										<label style="font-size: 12px; font-weight: 700; color: #1e293b;">Deskripsi Narasi</label>
										<textarea name="slide3_deskripsi" id="slide3_deskripsi" class="form-control input-sm" rows="3" placeholder="Deskripsi ringkas"></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- TOMBOL AKSI SIMPAN -->
				<div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 40px; background: #f8fafc;">
					<div class="box-body" style="padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
						<div style="font-size: 13px; color: #64748b;">
							<i class="fa fa-info-circle text-blue"></i> Perubahan identitas sekolah langsung disinkronkan ke Kartu Login, Kop Dokumen, dan Dashboard.
						</div>
						<div>
							<button type="submit" id="btn-simpan" class="btn btn-primary" style="padding: 10px 28px; font-weight: 700; border-radius: 10px; background: linear-gradient(135deg, #0077b6 0%, #0096c7 100%); border: none; box-shadow: 0 4px 14px rgba(0, 119, 182, 0.4);">
								<i class="fa fa-save" style="margin-right: 6px;"></i> Simpan Seluruh Pengaturan
							</button>
						</div>
					</div>
				</div>
			<?php echo form_close(); ?> 
        </div>
    </div>
</section>

<script lang="javascript">
	function previewImg(input, targetId) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#' + targetId).attr('src', e.target.result);
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

				// Data Sekolah & Kepsek
				$('#sekolah_provinsi').val(data.sekolah_provinsi);
				$('#sekolah_dinas').val(data.sekolah_dinas);
				$('#sekolah_alamat').val(data.sekolah_alamat);
				$('#sekolah_kecamatan').val(data.sekolah_kecamatan);
				$('#sekolah_kota').val(data.sekolah_kota);
				$('#sekolah_titimangsa').val(data.sekolah_titimangsa);
				$('#sekolah_telepon').val(data.sekolah_telepon);
				$('#sekolah_email').val(data.sekolah_email);
				$('#sekolah_website').val(data.sekolah_website);
				$('#sekolah_kepsek_nama').val(data.sekolah_kepsek_nama);
				$('#sekolah_kepsek_nip').val(data.sekolah_kepsek_nip);

				if(data.sekolah_ttd_kepsek) $('#preview-ttd-kepsek').attr('src', data.sekolah_ttd_kepsek);
				if(data.sekolah_logo_provinsi) $('#preview-logo-prov').attr('src', data.sekolah_logo_provinsi);
				if(data.sekolah_logo_sekolah) $('#preview-logo-sek').attr('src', data.sekolah_logo_sekolah);

				// Slideshow
				$('#slide1_judul').val(data.slide1_judul);
				$('#slide1_deskripsi').val(data.slide1_deskripsi);
				$('#slide1_gambar').val(data.slide1_gambar);
				$('#preview-slide-1').attr('src', data.slide1_gambar);

				$('#slide2_judul').val(data.slide2_judul);
				$('#slide2_deskripsi').val(data.slide2_deskripsi);
				$('#slide2_gambar').val(data.slide2_gambar);
				$('#preview-slide-2').attr('src', data.slide2_gambar);

				$('#slide3_judul').val(data.slide3_judul);
				$('#slide3_deskripsi').val(data.slide3_deskripsi);
				$('#slide3_gambar').val(data.slide3_gambar);
				$('#preview-slide-3').attr('src', data.slide3_gambar);
            }
            $("#modal-proses").modal('hide');
        });
    }

    $(function(){
        load_data();

        $('#form-pengaturan').submit(function(e){
			e.preventDefault();
            $("#modal-proses").modal('show');
			$('#zyacbt-informasi').val($('#zyacbt_informasi').val());

			var formData = new FormData(this);

            $.ajax({
                url:"<?php echo site_url().'/'.$url; ?>/simpan",
                type:"POST",
                data: formData,
                cache: false,
				contentType: false,
				processData: false,
                success:function(respon){
                    var obj = $.parseJSON(respon);
                    if(obj.status==1){
                        $("#modal-proses").modal('hide');
                        $('#form-pesan').html(
							'<div class="alert alert-success alert-dismissible" style="border-radius: 10px;">' +
							'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
							'<h4><i class="icon fa fa-check"></i> Sukses!</h4>' + obj.pesan + '</div>'
						);
						load_data();
                    }else{
                        $("#modal-proses").modal('hide');
                        $('#form-pesan').html(
							'<div class="alert alert-danger alert-dismissible" style="border-radius: 10px;">' +
							'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
							'<h4><i class="icon fa fa-ban"></i> Gagal!</h4>' + obj.pesan + '</div>'
						);
                    }
                },
				error: function(){
					$("#modal-proses").modal('hide');
					$('#form-pesan').html(
						'<div class="alert alert-danger" style="border-radius: 10px;">Terjadi kendala saat menyimpan data ke server.</div>'
					);
				}
            });
            return false;
        });
    });
</script>

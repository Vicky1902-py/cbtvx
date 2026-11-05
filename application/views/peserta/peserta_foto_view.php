<!-- Content Header (Page header) -->
<section class="content-header" style="margin-bottom: 15px;">
	<h1 style="font-size: 22px; font-weight: 800; color: #001233;">
		Upload & Manajemen Foto Siswa
		<small style="font-size: 13px; color: #64748b;">Unggah massal foto siswa via ZIP untuk Kartu Login & Asesmen CBT</small>
	</h1>
	<ol class="breadcrumb" style="background: transparent;">
		<li><a href="<?php echo site_url(); ?>/manager"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="<?php echo site_url(); ?>/manager/peserta_daftar">Data Peserta</a></li>
		<li class="active">Upload Foto Siswa</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<!-- Summary Statistics Cards -->
	<div class="row" style="margin-bottom: 15px;">
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box" style="border-radius: 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;">
				<span class="info-box-icon bg-aqua" style="border-top-left-radius: 14px; border-bottom-left-radius: 14px;"><i class="fa fa-users"></i></span>
				<div class="info-box-content">
					<span class="info-box-text" style="font-weight: 700; color: #64748b;">TOTAL SISWA TERDAFTAR</span>
					<span class="info-box-number" style="font-size: 24px; font-weight: 800; color: #001233;"><?php echo $total_peserta; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box" style="border-radius: 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;">
				<span class="info-box-icon bg-green" style="border-top-left-radius: 14px; border-bottom-left-radius: 14px;"><i class="fa fa-camera"></i></span>
				<div class="info-box-content">
					<span class="info-box-text" style="font-weight: 700; color: #64748b;">FOTO SISWA TERUNGGAH</span>
					<span class="info-box-number" style="font-size: 24px; font-weight: 800; color: #001233;"><?php echo $total_foto; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12">
			<div class="info-box" style="border-radius: 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;">
				<span class="info-box-icon bg-blue" style="border-top-left-radius: 14px; border-bottom-left-radius: 14px;"><i class="fa fa-id-card"></i></span>
				<div class="info-box-content">
					<span class="info-box-text" style="font-weight: 700; color: #64748b;">AKSI CEPAT</span>
					<div style="margin-top: 4px;">
						<a href="<?php echo site_url(); ?>/manager/peserta_kartu" class="btn btn-xs btn-primary" style="font-weight: 700; border-radius: 6px;">
							<i class="fa fa-print"></i> Cetak Kartu Peserta
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<!-- Panel Kiri: Form Upload ZIP Massal -->
        <div class="col-md-6 col-xs-12">
			<div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 25px;">
				<div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
					<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
						<i class="fa fa-file-archive-o text-blue" style="margin-right: 8px;"></i> Upload Foto Massal via File .ZIP
					</div>
				</div>
				<div class="box-body" style="padding: 24px;">
					<div class="callout callout-info" style="border-radius: 10px; background-color: #f0f9ff !important; border-color: #0096c7; color: #034f75 !important;">
						<h4 style="font-size: 14px; font-weight: 800; margin-bottom: 6px;"><i class="fa fa-info-circle"></i> Petunjuk Penamaan File Foto:</h4>
						<ul style="padding-left: 20px; font-size: 12.5px; line-height: 20px; margin-bottom: 0;">
							<li>Foto di dalam arsip ZIP harus dinamai dengan <b>NISN</b> atau <b>Username</b> siswa.</li>
							<li>Contoh nama berkas: <code>0012345678.jpg</code>, <code>siswa01.png</code>, <code>user1.jpeg</code>.</li>
							<li>Format foto yang didukung: <b>JPG, JPEG, PNG, WEBP</b>.</li>
							<li>Foto otomatis terhubung ke biodata siswa saat Kartu Peserta dicetak.</li>
						</ul>
					</div>

					<div id="form-pesan-zip" style="margin-bottom: 16px;"></div>

					<?php echo form_open_multipart($url.'/upload_zip', 'id="form-upload-zip"'); ?>
						<div class="form-group">
							<label style="font-weight: 700; color: #1e293b;">Pilih File ZIP Berisi Foto Siswa</label>
							<input type="file" name="zip_file" id="zip_file" class="form-control" accept=".zip" required style="border-radius: 8px;">
							<p class="help-block" style="font-size: 12px; color: #64748b;">Maksimal ukuran file zip: 50 MB.</p>
						</div>

						<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
							<button type="submit" id="btn-submit-zip" class="btn btn-primary" style="font-weight: 700; padding: 10px 24px; border-radius: 10px; background: linear-gradient(135deg, #0077b6 0%, #0096c7 100%); border: none;">
								<i class="fa fa-upload" style="margin-right: 6px;"></i> Ekstrak & Proses Foto
							</button>

							<button type="button" id="btn-hapus-semua" class="btn btn-danger btn-sm" style="border-radius: 8px;" onclick="konfirmasiHapusSemua()">
								<i class="fa fa-trash"></i> Bersihkan Semua Foto
							</button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>

		<!-- Panel Kanan: Form Upload Individual & Filter -->
		<div class="col-md-6 col-xs-12">
			<div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 25px;">
				<div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
					<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
						<i class="fa fa-user-plus text-blue" style="margin-right: 8px;"></i> Upload Foto Satuan / Per Siswa
					</div>
				</div>
				<div class="box-body" style="padding: 24px;">
					<div id="form-pesan-single" style="margin-bottom: 16px;"></div>

					<?php echo form_open_multipart($url.'/upload_single', 'id="form-upload-single"'); ?>
						<div class="form-group">
							<label style="font-weight: 700; color: #1e293b;">Nomor Peserta / Username / NISN</label>
							<input type="text" name="single_username" id="single_username" class="form-control" placeholder="Contoh: 0012345678 atau user1" required style="border-radius: 8px;">
							<p class="help-block" style="font-size: 12px; color: #64748b;">Masukkan username/NISN akun siswa yang terdaftar.</p>
						</div>

						<div class="form-group">
							<label style="font-weight: 700; color: #1e293b;">Pilih Berkas Foto</label>
							<input type="file" name="single_foto" id="single_foto" class="form-control" accept="image/jpeg, image/png, image/webp" required style="border-radius: 8px;">
							<p class="help-block" style="font-size: 12px; color: #64748b;">Format JPG atau PNG (disarankan pas foto 3x4 formal).</p>
						</div>

						<div style="margin-top: 20px;">
							<button type="submit" id="btn-submit-single" class="btn btn-default" style="font-weight: 700; padding: 10px 24px; border-radius: 10px; border-color: #cbd5e1;">
								<i class="fa fa-camera" style="margin-right: 6px;"></i> Simpan Foto Satuan
							</button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>

	<!-- TABEL DAFTAR FOTO SISWA -->
	<div class="row">
		<div class="col-xs-12">
			<div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 40px;">
				<div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px; display: flex; justify-content: space-between; align-items: center;">
					<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
						<i class="fa fa-list text-blue" style="margin-right: 8px;"></i> Status & Pratinjau Foto Siswa
					</div>
					<div style="display: flex; gap: 10px; align-items: center;">
						<label style="font-size: 12px; color: #64748b; margin: 0;">Filter Group:</label>
						<select id="pilih-grup" class="form-control input-sm" style="max-width: 180px; border-radius: 6px;" onchange="refreshTabel()">
							<?php echo $select_group; ?>
						</select>
					</div>
				</div>
				<div class="box-body table-responsive" style="padding: 20px;">
					<table id="tabel-foto-siswa" class="table table-bordered table-hover" style="font-size: 13px;">
						<thead>
							<tr style="background: #f1f5f9; color: #0f172a;">
								<th style="width: 40px; text-align: center;">No</th>
								<th style="width: 60px; text-align: center;">Foto</th>
								<th>Username / NISN</th>
								<th>Nama Siswa</th>
								<th>Ruang / Sesi</th>
								<th style="width: 110px; text-align: center;">Status Foto</th>
								<th style="width: 90px; text-align: center;">Aksi</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Modal Upload Cepat Per Baris -->
<div class="modal" id="modal-ganti-foto" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.3);">
			<?php echo form_open_multipart($url.'/upload_single', 'id="form-modal-foto"'); ?>
				<div class="modal-header" style="background: #0077b6; color: #ffffff; padding: 16px 20px;">
					<button type="button" class="close" data-dismiss="modal" style="color: #ffffff; opacity: 0.9;">&times;</button>
					<h4 class="modal-title" style="font-weight: 800; font-size: 16px;"><i class="fa fa-camera"></i> Unggah Foto Siswa</h4>
				</div>
				<div class="modal-body" style="padding: 24px;">
					<div class="form-group">
						<label style="font-weight: 700;">Siswa Target</label>
						<input type="text" id="modal_username_display" class="form-control" readonly style="background: #f1f5f9; font-weight: 700;">
						<input type="hidden" name="single_username" id="modal_username">
					</div>
					<div class="form-group">
						<label style="font-weight: 700;">Pilih Pas Foto Siswa</label>
						<input type="file" name="single_foto" id="modal_foto" class="form-control" accept="image/*" required>
					</div>
				</div>
				<div class="modal-footer" style="background: #f8fafc; padding: 14px 20px;">
					<button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
					<button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 700;">Simpan Foto</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	var tabelFoto = null;

	function refreshTabel(){
		var grup = $('#pilih-grup').val();
		if(tabelFoto) {
			tabelFoto.ajax.url('<?php echo site_url().'/'.$url; ?>/get_datatable?grup=' + grup).load();
		}
	}

	function gantiFoto(username, nama){
		$('#modal_username').val(username);
		$('#modal_username_display').val(username + ' - ' + nama);
		$('#modal_foto').val('');
		$('#modal-ganti-foto').modal('show');
	}

	function konfirmasiHapusSemua(){
		if(confirm('Apakah Anda yakin ingin menghapus SELURUH foto siswa yang tersimpan?')) {
			$("#modal-proses").modal('show');
			$.getJSON('<?php echo site_url().'/'.$url; ?>/hapus_semua', function(res){
				$("#modal-proses").modal('hide');
				alert(res.pesan);
				window.location.reload();
			});
		}
	}

	$(function(){
		tabelFoto = $('#tabel-foto-siswa').DataTable({
			"ajax": '<?php echo site_url().'/'.$url; ?>/get_datatable?grup=' + $('#pilih-grup').val(),
			"order": [[ 2, "asc" ]],
			"language": {
				"search": "Cari Siswa:",
				"lengthMenu": "Tampilkan _MENU_ baris",
				"info": "Menampilkan _START_ s/d _END_ dari _TOTAL_ siswa",
				"emptyTable": "Tidak ada data siswa",
				"paginate": {
					"first": "Awal",
					"last": "Akhir",
					"next": "Lanjut",
					"previous": "Mundur"
				}
			}
		});

		// Submit Upload ZIP
		$('#form-upload-zip').submit(function(e){
			e.preventDefault();
			$("#modal-proses").modal('show');
			var formData = new FormData(this);

			$.ajax({
				url: $(this).attr('action'),
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				success: function(resp){
					$("#modal-proses").modal('hide');
					var obj = $.parseJSON(resp);
					if(obj.status == 1){
						$('#form-pesan-zip').html(
							'<div class="alert alert-success alert-dismissible" style="border-radius: 10px;">' +
							'<button type="button" class="close" data-dismiss="alert">&times;</button>' +
							'<h4><i class="fa fa-check"></i> Berhasil!</h4>' + obj.pesan + '</div>'
						);
						$('#zip_file').val('');
						refreshTabel();
					} else {
						$('#form-pesan-zip').html(
							'<div class="alert alert-danger alert-dismissible" style="border-radius: 10px;">' +
							'<button type="button" class="close" data-dismiss="alert">&times;</button>' +
							'<h4><i class="fa fa-ban"></i> Gagal!</h4>' + obj.pesan + '</div>'
						);
					}
				},
				error: function(){
					$("#modal-proses").modal('hide');
					alert('Terjadi kesalahan koneksi server saat upload file zip.');
				}
			});
		});

		// Submit Upload Single
		$('#form-upload-single, #form-modal-foto').submit(function(e){
			e.preventDefault();
			$("#modal-proses").modal('show');
			var formElem = this;
			var formData = new FormData(formElem);

			$.ajax({
				url: '<?php echo site_url().'/'.$url; ?>/upload_single',
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				success: function(resp){
					$("#modal-proses").modal('hide');
					$('#modal-ganti-foto').modal('hide');
					var obj = $.parseJSON(resp);
					if(obj.status == 1){
						alert(obj.pesan);
						formElem.reset();
						refreshTabel();
					} else {
						alert('Gagal: ' + obj.pesan);
					}
				},
				error: function(){
					$("#modal-proses").modal('hide');
					alert('Terjadi kesalahan koneksi server saat upload foto.');
				}
			});
		});
	});
</script>

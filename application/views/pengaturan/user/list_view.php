<!-- Content Header (Page header) -->
<section class="content-header" style="margin-bottom: 10px;">
	<h1 style="font-size: 20px; font-weight: 800; color: #001233;">
		Data Pengguna & Guru
		<small style="color: #64748b; font-size: 13px;">Kelola akun Administrator, Guru Mapel, dan Proktor Ujian</small>
	</h1>
	<ol class="breadcrumb" style="background: transparent;">
		<li><a href="<?php echo site_url(); ?>/manager"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">User</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
        <div class="col-xs-12">
                <div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                    <div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
    						<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
								<i class="fa fa-users text-blue" style="margin-right: 8px;"></i> Daftar Pengguna Sistem
							</div>
    						<div class="box-tools pull-right">
                                <a href="<?php echo site_url(); ?>/manager/useratur/import" class="btn btn-sm btn-success" style="border-radius: 8px; font-weight: 700; margin-right: 6px;">
									<i class="fa fa-file-excel-o" style="margin-right: 4px;"></i> Import Pengguna (.xlsx)
								</a>
    							<a href="<?php echo site_url(); ?>/manager/useratur/index/add" class="btn btn-sm btn-primary" style="border-radius: 8px; font-weight: 700; background: linear-gradient(135deg, #0077b6 0%, #0096c7 100%); border: none;">
									<i class="fa fa-plus" style="margin-right: 4px;"></i> Tambah User
								</a>
    						</div>
                    </div><!-- /.box-header -->

                    <div class="box-body" style="padding: 20px;">
                        <table id="table-user" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th class="all">Username</th>
                                    <th class="all">Nama Lengkap</th>
                                    <th>Level</th>
									<th>Opsi 1</th>
									<th>Opsi 2</th>
									<th>Keterangan</th>
                                    <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
									<td> </td>
                                    <td> </td>
									<td> </td>
                                </tr>
                            </tbody>
                        </table>                        
                    </div>
                </div>
        </div>
    </div>
</section><!-- /.content -->

<script lang="javascript">
    $(function(){
        $('#table-user').DataTable({
                  "paging": true,
                  "iDisplayLength":10,
                  "bProcessing": false,
                  "bServerSide": true, 
                  "searching": true,
                  "aoColumns": [
    					{"bSearchable": false, "bSortable": false, "sWidth":"20px"},
    					{"bSearchable": false, "bSortable": false, "sWidth":"80px"},
    					{"bSearchable": false, "bSortable": false, "sWidth":"180px"},
                        {"bSearchable": false, "bSortable": false},
						{"bSearchable": false, "bSortable": false},
						{"bSearchable": false, "bSortable": false},
						{"bSearchable": false, "bSortable": false},
                        {"bSearchable": false, "bSortable": false, "sWidth":"60px"}],
                  "sAjaxSource": "<?php echo site_url(); ?>/manager/useratur/get_all_user/",
                  "autoWidth": false,
				  "responsive": true
         });          
    });
</script>

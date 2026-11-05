<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php if(!empty($site_name)){ echo $site_name; } ?> | <?php echo $title; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=10, user-scalable=yes' name='viewport'>
	<meta name="description" content="CBT Modern Standar TKA Kemdikbud oleh Vicky Koroh">
	<meta name="keywords" content="CBT, TKA Kemdikbud, Ujian Online, Vicky Koroh">
	<meta name="author" content="Vicky Koroh">
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo base_url(); ?>public/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="<?php echo base_url(); ?>public/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="<?php echo base_url(); ?>public/plugins/ionicons/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>public/plugins/adminlte/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo base_url(); ?>public/plugins/adminlte/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <!-- DATA TABLES -->
    <link href="<?php echo base_url(); ?>public/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>public/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url(); ?>public/plugins/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>public/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />

    <!-- summernote dimatikan
    <link href="<?php echo base_url(); ?>public/plugins/summernote/summernote.css" rel="stylesheet" type="text/css" />
    -->

    <link href="<?php echo base_url(); ?>public/plugins/pnotify/pnotify.custom.min.css" rel="stylesheet" type="text/css" />
    <!-- daterange picker -->
    <link href="<?php echo base_url(); ?>public/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />

    <!-- select2 -->
    <link href="<?php echo base_url(); ?>public/plugins/select2-4.0.5/css/select2.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    
    <!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url(); ?>public/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo base_url(); ?>public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='<?php echo base_url(); ?>public/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>public/plugins/adminlte/js/app.min.js" type="text/javascript"></script>
    <!-- Sparkline -->
    <script src="<?php echo base_url(); ?>public/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="<?php echo base_url(); ?>public/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	
	 <!-- ChartJS 1.0.1 -->
    <script src="<?php echo base_url(); ?>public/app.js" type="text/javascript"></script>

    <script src="<?php echo base_url(); ?>public/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/plugins/datatables/dataTables.reload.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script>
    <!-- bootstrap time picker -->
    <script src="<?php echo base_url(); ?>public/plugins/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url(); ?>public/plugins/adminlte/js/demo.js" type="text/javascript"></script>
    
    <!-- summernote dimatikan
    <script src="<?php echo base_url(); ?>public/plugins/summernote/summernote.js" type="text/javascript"></script>
    -->

    <script src="<?php echo base_url(); ?>public/plugins/pnotify/pnotify.custom.min.js" type="text/javascript"></script>

    <!-- datetimerange -->
    <script src="<?php echo base_url(); ?>public/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>

    <!-- ckeditor -->
    <script src="<?php echo base_url(); ?>public/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>

    <!-- KaTeX Offline Math Engine by Vicky Koroh -->
    <link href="<?php echo base_url(); ?>public/plugins/katex/katex.min.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>public/plugins/katex/katex.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/plugins/katex/auto-render.min.js" type="text/javascript"></script>
    <script type="text/javascript">
      function renderMathOffline(elem) {
        if (typeof renderMathInElement !== 'undefined') {
          renderMathInElement(elem || document.body, {
            delimiters: [
              {left: '$$', right: '$$', display: true},
              {left: '\\[', right: '\\]', display: true},
              {left: '\\(', right: '\\)', display: false},
              {left: '$', right: '$', display: false}
            ],
            throwOnError: false
          });
        }
      }
      $(document).ready(function() { renderMathOffline(); });
    </script>
    
    <!-- select2 -->
    <script src="<?php echo base_url(); ?>public/plugins/select2-4.0.5/js/select2.min.js" type="text/javascript"></script>

    <!-- membuat gambar responsive pada table -->
    <style type="text/css">
      table img {
        display: block;
        max-width: 100%;
        height: auto;
      }
    </style>

    <script type="text/javascript">
		function notify_success(pesan){
			new PNotify({
				title: 'Berhasil',
				text: pesan,
				type: 'success',
				history: false,
				delay:4000
			});
		}
        
        function notify_info(pesan){
			new PNotify({
				title: 'Informasi',
				text: pesan,
				type: 'info',
				history: false,
				delay:2000
			});
		}
    
		function notify_error(pesan){
			new PNotify({
				title: 'Error',
				text: pesan,
				type: 'error',
				history: false,
				delay:2000
			});
		} 
  </script>
      <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Modern TKA Kemdikbud Design System & Motion by Vicky Koroh -->
    <style type="text/css">
      body, .main-header, .sidebar, .content-wrapper, .box, .modal, .btn, .table, h1, h2, h3, h4, h5, h6 {
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
      }

      /* Animated Content Entrance */
      @keyframes fadeInUpModern {
        from {
          opacity: 0;
          transform: translateY(12px) scale(0.99);
        }
        to {
          opacity: 1;
          transform: translateY(0) scale(1);
        }
      }
      .content {
        animation: fadeInUpModern 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      }

      /* Header Navbar: Glassmorphic Navy */
      .skin-green .main-header .navbar {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.15);
      }
      .skin-green .main-header .logo {
        background: #0b1329 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        font-weight: 800;
        letter-spacing: 0.8px;
        transition: all 0.25s ease;
      }
      .skin-green .main-header .logo:hover {
        background: #0284c7 !important;
      }
      .main-header .sidebar-toggle {
        transition: all 0.2s ease;
      }
      .main-header .sidebar-toggle:hover {
        background: rgba(255, 255, 255, 0.1) !important;
      }

      /* Sidebar: Sleek Dark Slate */
      .skin-green .main-sidebar, .skin-green .left-side {
        background-color: #0b1329 !important;
        box-shadow: 2px 0 16px rgba(0, 0, 0, 0.12);
      }
      .sidebar-menu > li.header {
        background: transparent !important;
        color: #64748b !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        letter-spacing: 1px !important;
        padding: 14px 18px 6px 18px !important;
      }
      .sidebar-menu > li > a {
        border-radius: 8px !important;
        margin: 3px 12px !important;
        padding: 10px 14px !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #94a3b8 !important;
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1) !important;
      }
      .sidebar-menu > li > a:hover {
        background: rgba(255, 255, 255, 0.06) !important;
        color: #ffffff !important;
        transform: translateX(4px);
      }
      .sidebar-menu > li.active > a, .sidebar-menu > li.menu-open > a {
        background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35) !important;
      }
      .treeview-menu {
        background: rgba(15, 23, 42, 0.6) !important;
        padding: 4px 0 !important;
        border-radius: 8px;
        margin: 2px 12px !important;
      }
      .treeview-menu > li > a {
        border-radius: 6px !important;
        padding: 8px 14px !important;
        font-size: 13px !important;
        transition: all 0.2s ease !important;
      }
      .treeview-menu > li > a:hover {
        color: #38bdf8 !important;
        background: rgba(255, 255, 255, 0.04) !important;
      }

      /* Content Area */
      .content-wrapper {
        background-color: #f8fafc !important;
      }
      .content-header {
        padding: 18px 24px 8px 24px !important;
      }
      .content-header > h1 {
        font-size: 22px !important;
        font-weight: 800 !important;
        color: #0f172a !important;
        letter-spacing: -0.3px;
      }
      .content-header > h1 > small {
        font-size: 13px !important;
        color: #64748b !important;
        font-weight: 500 !important;
        margin-left: 8px;
      }

      /* Modern Boxes & Cards */
      .box {
        border-radius: 14px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04) !important;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        background: #ffffff !important;
        margin-bottom: 22px !important;
      }
      .box:hover {
        box-shadow: 0 10px 25px -4px rgba(15, 23, 42, 0.08) !important;
      }
      .box-header {
        padding: 14px 20px !important;
        border-bottom: 1px solid #f1f5f9 !important;
      }
      .box-header .box-title {
        font-size: 15px !important;
        font-weight: 700 !important;
        color: #1e293b !important;
      }
      .box-body {
        padding: 20px !important;
      }
      .box-footer {
        padding: 14px 20px !important;
        background: #fafafa !important;
        border-top: 1px solid #f1f5f9 !important;
        border-bottom-left-radius: 14px !important;
        border-bottom-right-radius: 14px !important;
      }

      /* Modern Buttons with Gradients & Motion */
      .btn {
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 7px 16px !important;
        letter-spacing: 0.2px !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
      }
      .btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12) !important;
      }
      .btn-primary {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        border: none !important;
        color: #ffffff !important;
      }
      .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        border: none !important;
        color: #ffffff !important;
      }
      .btn-info {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%) !important;
        border: none !important;
        color: #ffffff !important;
      }
      .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        border: none !important;
        color: #ffffff !important;
      }
      .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        border: none !important;
        color: #ffffff !important;
      }
      .btn-default {
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        color: #334155 !important;
      }
      .btn-default:hover {
        background: #f1f5f9 !important;
        border-color: #94a3b8 !important;
      }

      /* Modern Form Controls */
      .form-control {
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        padding: 8px 12px !important;
        font-size: 13.5px !important;
        box-shadow: none !important;
        transition: all 0.2s ease !important;
      }
      .form-control:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
      }

      /* Modern Tables */
      .table {
        border-collapse: separate !important;
        border-spacing: 0 !important;
      }
      .table thead th {
        background: #f1f5f9 !important;
        color: #475569 !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.6px !important;
        border-bottom: 2px solid #e2e8f0 !important;
        padding: 12px 14px !important;
      }
      .table tbody tr {
        transition: background-color 0.15s ease !important;
      }
      .table tbody tr:hover td {
        background-color: #f8fafc !important;
      }
      .table td {
        padding: 12px 14px !important;
        vertical-align: middle !important;
        border-top: 1px solid #f1f5f9 !important;
      }

      /* Status Indicator Pulse Dot */
      .tka-pulse-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulseAnimation 2s infinite;
        margin-right: 6px;
      }
      @keyframes pulseAnimation {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
      }

      /* Modern Footer */
      .main-footer {
        background: #ffffff !important;
        border-top: 1px solid #e2e8f0 !important;
        color: #64748b !important;
        font-size: 12.5px !important;
        padding: 15px 24px !important;
      }
    </style>
  </head>
  <body class="skin-green sidebar-mini">
    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->
        <a href="<?php echo site_url(); ?>/manager" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>TKA</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><i class="fa fa-graduation-cap"></i> <b>CBT TKA</b> MODERN</span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo base_url(); ?>public/plugins/adminlte/img/avatar04.png" class="user-image" alt="User Image"/>
                  <span class="hidden-xs"><?php if(!empty($nama)){ echo $nama; }else{ echo 'Administrator'; } ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo base_url(); ?>public/plugins/adminlte/img/avatar04.png" class="img-circle" alt="User Image" />
                    <p>
                      <?php if(!empty($nama)){ echo $nama; }else{ echo 'Administrator'; } ?>
                      <small>Selamat Datang</small>
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a data-toggle="modal" href="#modal-password" class="btn btn-default btn-flat">Password</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo site_url(); ?>/manager/welcome/logout" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo base_url(); ?>public/plugins/adminlte/img/avatar04.png" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p><?php if(!empty($nama)){ echo $nama; }else{ echo 'Administrator'; } ?></p>

              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
			<li><a href="<?php echo site_url(); ?>/manager"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            
            <?php 
            if(!empty($sidemenu)){
                echo $sidemenu;
            }
            ?>
            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
		<?php 
			if(!empty($content)){
				echo $content;
			}
		?>
      </div><!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> <?php if(!empty($site_version)){ echo $site_version; } ?> | Dirancang oleh <b>Vicky Koroh</b>
        </div>
        <strong>Hak Cipta &copy; <?php echo date('Y'); ?> Vicky Koroh. All rights reserved.</strong>
      </footer>

    </div><!-- ./wrapper -->
	
	<div class="modal" id="modal-password" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Change Password</h4>
				</div>
				<div class="modal-body">
						<span id="form-pesan-password">
                        </span>
						<?php echo form_open('manager/dashboard/password','id="form-password"')?>
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Old Password</label>
                                    <input type="password" class="form-control" id="password-old" name="password-old" placeholder="Old Password">
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" id="password-new" name="password-new" placeholder="New Password">
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control" id="password-confirm" name="password-confirm" placeholder="Confirm Password">
                                </div>
                            </div>
                        <?php echo form_close(); ?>   
				</div>
				<div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="password-submit">Change</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
    
    <div class="modal fade" id="modal-proses" data-backdrop="static" tabindex="-1" role="dialog">
  		<div class="modal-dialog modal-sm" style="margin-top: 20vh;">
  			<div class="modal-content" style="border-radius: 16px; border: none; background: rgba(15, 23, 42, 0.92); backdrop-filter: blur(12px); color: #ffffff; box-shadow: 0 20px 40px rgba(0,0,0,0.4);">
  				<div class="modal-body text-center" style="padding: 28px 20px;">
            <div style="width: 44px; height: 44px; border: 4px solid rgba(56, 189, 248, 0.2); border-top-color: #38bdf8; border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 14px auto;"></div>
            <h4 style="font-weight: 700; margin: 0 0 6px 0; font-size: 15px;">Sedang Memproses...</h4>
            <p style="margin: 0; font-size: 12px; color: #94a3b8;">Mohon tunggu sejenak hingga selesai</p>
  				</div>
  			</div><!-- /.modal-content -->
  		</div><!-- /.modal-dialog -->
  	</div><!-- /.modal -->
	
	<script>
    $(function () {
                        
      //Form Ubah Password
			$('#modal-password').on('shown.bs.modal', function (e) {
				$('#form-pesan-password').html('');
				$('#password-old').val('');
				$('#password-new').val('');
				$('#password-confirm').val('');
				$('#password-old').focus();
			});
			
			$('#password-submit').click(function(){
        $('#form-password').submit();
			});
			
			$('#form-password').submit(function(){        
        $.ajax({
          url:"<?php echo site_url(); ?>/manager/dashboard/password",
          type:"POST",
          data:$('#form-password').serialize(),
          cache: false,
          success:function(respon){
            var obj = $.parseJSON(respon);
            if(obj.status==1){
              $('#form-pesan-password').html(pesan_succ('Password berhasil diubah !'));
              setTimeout(function(){$('#modal-password').modal('hide')}, 1500);
            }else{
              $('#form-pesan-password').html(pesan_err(obj.error));
            }
          }
        });
        return false;
			});
    });
  </script>
  </body>
</html>

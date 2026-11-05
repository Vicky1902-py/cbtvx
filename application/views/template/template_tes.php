<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <title><?php if(!empty($site_name)){ echo $site_name; }else{ echo 'SMKN 1 Kupang Barat'; } ?> | <?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, shrink-to-fit=no, user-scalable=yes">
    <meta name="description" content="Aplikasi CBT Modern Standar TKA Kemdikbud - SMK Negeri 1 Kupang Barat">
    <meta name="author" content="Vicky Koroh">
    <meta name="copyright" content="Hak Cipta © Vicky Koroh">

    <!-- Favicon SMKN 1 Kupang Barat -->
    <link rel="icon" type="image/png" href="<?php echo base_url(); ?>public/images/favicon.png">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>public/images/favicon.png">
    
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo base_url(); ?>public/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="<?php echo base_url(); ?>public/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>public/plugins/adminlte/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>public/plugins/adminlte/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>public/plugins/pnotify/pnotify.custom.min.css" rel="stylesheet" type="text/css" />

    <!-- KaTeX Offline Math Engine by Vicky Koroh -->
    <link href="<?php echo base_url(); ?>public/plugins/katex/katex.min.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>public/plugins/katex/katex.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/plugins/katex/auto-render.min.js" type="text/javascript"></script>

    <!-- jQuery 2.1.4 & Bootstrap JS -->
    <script src="<?php echo base_url(); ?>public/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/plugins/pnotify/pnotify.custom.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/app.js" type="text/javascript"></script>

    <style type="text/css">
      :root {
        --tka-navy-dark: #0f2b48;
        --tka-navy: #1a365d;
        --tka-blue: #2563eb;
        --tka-blue-light: #eff6ff;
        --tka-blue-border: #93c5fd;
        --tka-gray-bg: #f8fafc;
        --tka-border: #e2e8f0;
        --tka-text: #1e293b;
        --tka-text-muted: #64748b;
        --tka-green: #10b981;
        --tka-red: #ef4444;
        --tka-yellow: #f59e0b;
      }

      body {
        background-color: #f1f5f9;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: var(--tka-text);
        margin: 0;
        padding: 0;
      }

      /* TKA Top Navbar SMKN 1 Kupang Barat */
      .tka-navbar {
        background: linear-gradient(135deg, #03045e 0%, #0077b6 100%);
        color: #ffffff;
        padding: 8px 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        border-bottom: 2px solid rgba(0, 180, 216, 0.35);
        position: sticky;
        top: 0;
        z-index: 1030;
      }
      .tka-brand {
        display: flex;
        align-items: center;
        gap: 12px;
      }
      .tka-logo-badge {
        width: 38px;
        height: 38px;
        background: #ffffff;
        color: #0f2b48;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 18px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.12);
      }
      .tka-title-box h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #ffffff;
      }
      .tka-title-box p {
        margin: 0;
        font-size: 11px;
        color: #93c5fd;
      }

      .tka-user-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.12);
        padding: 6px 14px;
        border-radius: 20px;
        color: #ffffff;
        font-size: 13px;
        font-weight: 500;
      }
      .tka-user-badge i {
        margin-right: 8px;
        color: #60a5fa;
      }

      .tka-timer-box {
        background: #fbbf24;
        color: #78350f;
        font-family: 'Courier New', Courier, monospace;
        font-weight: 800;
        font-size: 17px;
        padding: 6px 16px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      }
      .tka-timer-box i {
        margin-right: 8px;
      }

      /* Responsive Soal Workspace */
      .tka-workspace {
        padding: 20px 0 40px 0;
      }

      /* Card Soal */
      .tka-card-soal {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid var(--tka-border);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        overflow: hidden;
        margin-bottom: 20px;
      }
      .tka-card-header {
        background: #ffffff;
        padding: 14px 20px;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
      }
      .tka-soal-pill {
        background: #0f2b48;
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 6px;
        display: inline-block;
      }
      .tka-tipe-pill {
        font-size: 12px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        margin-left: 8px;
        display: inline-block;
      }
      .tka-tipe-pg { background: #dbeafe; color: #1e40af; }
      .tka-tipe-bs { background: #dcfce7; color: #15803d; }
      .tka-tipe-essay { background: #fef3c7; color: #b45309; }
      .tka-tipe-isian { background: #f3e8ff; color: #6b21a8; }

      .tka-zoom-btn {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-weight: bold;
        padding: 4px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.15s ease;
      }
      .tka-zoom-btn:hover {
        background: #e2e8f0;
      }

      .tka-card-body {
        padding: 24px;
        min-height: 280px;
        font-size: 16px;
        line-height: 1.7;
        color: #1e293b;
      }
      .tka-card-body img {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
        margin: 10px 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      }

      /* Option Cards - Standar TKA / ANBK */
      .tka-options-container, .tka-options-list {
        margin-top: 25px;
        display: flex;
        flex-direction: column;
        gap: 12px;
      }
      .tka-option-item, .tka-option-card {
        display: flex;
        align-items: flex-start;
        padding: 12px 18px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        user-select: none;
      }
      .tka-option-item:hover, .tka-option-card:hover {
        border-color: #93c5fd;
        background: #f8fafc;
      }
      .tka-option-item.selected, .tka-option-card.selected {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15);
      }
      .tka-option-badge, .tka-option-bullet {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 14px;
        flex-shrink: 0;
        transition: all 0.2s ease;
        border: 1px solid #cbd5e1;
      }
      .tka-option-item.selected .tka-option-badge, .tka-option-card.selected .tka-option-bullet {
        background: #2563eb;
        color: #ffffff;
        border-color: #1d4ed8;
      }
      .tka-option-text, .tka-option-content {
        flex-grow: 1;
        padding-top: 5px;
        font-size: 15px;
        line-height: 1.6;
        color: #1e293b;
      }

      /* Benar / Salah Cards */
      .tka-bs-container, .tka-bs-list {
        margin-top: 25px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
      }
      @media(max-width: 600px){
        .tka-bs-container, .tka-bs-list { grid-template-columns: 1fr; }
      }
      .tka-bs-btn, .tka-bs-card {
        padding: 20px 24px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        background: #ffffff;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        user-select: none;
      }
      .tka-bs-benar { color: #059669; border-color: #a7f3d0; background: #f0fdf4; }
      .tka-bs-benar:hover { border-color: #059669; background: #dcfce7; }
      .tka-bs-benar.selected { border-color: #059669; background: #059669; color: #ffffff; box-shadow: 0 4px 10px rgba(5, 150, 105, 0.25); }
      .tka-bs-salah { color: #dc2626; border-color: #fecaca; background: #fef2f2; }
      .tka-bs-salah:hover { border-color: #dc2626; background: #fee2e2; }
      .tka-bs-salah.selected { border-color: #dc2626; background: #dc2626; color: #ffffff; box-shadow: 0 4px 10px rgba(220, 38, 38, 0.25); }
      .tka-bs-icon { font-size: 22px; }

      /* Uraian / Essay & Isian Singkat Boxes */
      .tka-essay-box, .tka-isian-box {
        margin-top: 20px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 18px;
      }
      .tka-essay-textarea {
        border-radius: 8px;
        font-size: 15px;
        line-height: 1.6;
      }

      /* Card Footer Navigation */
      .tka-card-footer {
        background: #ffffff;
        padding: 16px 24px;
        border-top: 2px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
      }
      .tka-btn-nav {
        padding: 10px 22px;
        font-size: 14px;
        font-weight: 700;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
      }
      .tka-btn-prev { background: #64748b; color: #ffffff; }
      .tka-btn-prev:hover { background: #475569; color: #ffffff; }
      .tka-btn-next { background: #2563eb; color: #ffffff; }
      .tka-btn-next:hover { background: #1d4ed8; color: #ffffff; }
      .tka-btn-ragu { background: #f59e0b; color: #ffffff; }
      .tka-btn-ragu:hover { background: #d97706; color: #ffffff; }
      .tka-btn-selesai { background: #10b981; color: #ffffff; }
      .tka-btn-selesai:hover { background: #059669; color: #ffffff; }

      /* Modal Daftar Soal Grid */
      .tka-grid-soal {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
        gap: 10px;
        padding: 15px 5px;
      }
      .tka-soal-btn {
        height: 48px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        border: 2px solid #cbd5e1;
        background: #ffffff;
        color: #334155;
      }
      .tka-soal-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      }
      .tka-soal-btn.answered {
        background: #2563eb;
        border-color: #1d4ed8;
        color: #ffffff;
      }
      .tka-soal-btn.ragu {
        background: #f59e0b;
        border-color: #d97706;
        color: #ffffff;
      }
      .tka-soal-btn.active {
        box-shadow: 0 0 0 3px #38bdf8 !important;
        border-color: #0284c7 !important;
      }

      /* Modern Footer */
      .tka-footer {
        background: #ffffff;
        border-top: 1px solid var(--tka-border);
        padding: 15px 0;
        font-size: 12px;
        color: var(--tka-text-muted);
        text-align: center;
      }
    </style>

    <script type="text/javascript">
      function renderMathOffline(elem) {
        if (!elem) elem = document.body;
        if (typeof renderMathInElement === 'function') {
          renderMathInElement(elem, {
            delimiters: [
              {left: '$$', right: '$$', display: true},
              {left: '\\[', right: '\\]', display: true},
              {left: '$', right: '$', display: false},
              {left: '\\(', right: '\\)', display: false}
            ],
            throwOnError: false
          });
        }
      }

      function notify_success(pesan){
        new PNotify({
          title: 'Berhasil',
          text: pesan,
          type: 'success',
          history: false,
          delay: 2000
        });
      }
          
      function notify_info(pesan){
        new PNotify({
          title: 'Informasi',
          text: pesan,
          type: 'info',
          history: false,
          delay: 2000
        });
      }
      
      function notify_error(pesan){
        new PNotify({
          title: 'Perhatian',
          text: pesan,
          type: 'error',
          history: false,
          delay: 3000
        });
      } 

      document.addEventListener("DOMContentLoaded", function() {
        renderMathOffline(document.body);
      });
    </script>
  </head>
  <body>
    <!-- Header Standar TKA Kemdikbud -->
    <header class="tka-navbar">
      <div class="container-fluid" style="max-width: 1200px;">
        <div class="row" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
          <!-- Brand -->
          <div class="col-xs-12 col-sm-5" style="display: flex; align-items: center; padding: 4px 0;">
            <div class="tka-brand">
              <img src="<?php echo base_url(); ?>public/images/logo_smk.png" alt="Logo SMKN 1 Kupang Barat" style="width: 38px; height: 38px; object-fit: contain; filter: drop-shadow(0 2px 6px rgba(0, 180, 216, 0.45)); flex-shrink: 0;" />
              <div class="tka-title-box">
                <h3 style="margin: 0; font-size: 15px; font-weight: 800; letter-spacing: -0.2px;">SMKN 1 KUPANG BARAT</h3>
                <p style="margin: 0; font-size: 11px; color: #90e0ef;"><i class="fa fa-shield"></i> CBT Modern Standar TKA Pusmendik</p>
              </div>
            </div>
          </div>

          <!-- Middle / User & Timer -->
          <div class="col-xs-12 col-sm-7" style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; padding: 4px 0; flex-wrap: wrap;">
            <div class="tka-user-badge">
              <i class="fa fa-user-circle"></i>
              <span><b><?php if(!empty($nama)){ echo $nama; }else{ echo 'Peserta Ujian'; } ?></b> <?php if(!empty($group)){ echo ' ('.$group.')'; } ?></span>
            </div>

            <!-- Jam Server -->
            <div style="color: #93c5fd; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center;">
              <i class="fa fa-clock-o" style="margin-right: 5px;"></i> <span id="timestamp"></span>
            </div>

            <a href="<?php echo site_url(); ?>/welcome/logout" class="btn btn-xs btn-danger" style="padding: 5px 10px; border-radius: 4px; font-weight: 600;" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
              <i class="fa fa-sign-out"></i> Keluar
            </a>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Workspace -->
    <main class="tka-workspace">
      <div class="container-fluid" style="max-width: 1200px;">
        <?php 
        if(!empty($content)){
            echo $content; 
        }
        ?>
      </div>
    </main>

    <!-- Footer Copyright Vicky Koroh -->
    <footer class="tka-footer">
      <div class="container">
        <p style="margin: 0; font-weight: 500;">
          <?php echo !empty($site_copyright) ? $site_copyright : 'Hak Cipta © Vicky Koroh'; ?>. All rights reserved.
        </p>
        <p style="margin: 3px 0 0 0; font-size: 11px; color: #94a3b8;">
          Sistem Ujian CBT Terstandar Modern TKA Pusmendik Kemdikbud
        </p>
      </div>
    </footer>

    <!-- Modal Loading Proses -->
    <div class="modal" id="modal-proses" data-backdrop="static">
      <div class="modal-dialog modal-sm" style="margin-top: 20%;">
        <div class="modal-content" style="border-radius: 10px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
          <div class="modal-body" style="text-align: center; padding: 25px;">
            <i class="fa fa-spinner fa-spin fa-3x fa-fw text-primary" style="color: #2563eb;"></i>
            <div style="margin-top: 15px; font-weight: 600; font-size: 14px; color: #334155;">
              Menyimpan data...
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Live Server Time -->
    <script type="text/javascript">
      $(function () {
          var serverTime = <?php if(!empty($timestamp)){ echo $timestamp; }else{ echo time(); } ?>;
          var date;

          setInterval(function() {
            serverTime = serverTime + 1;
            date = new Date(serverTime * 1000);
            var hours = ("0" + date.getHours()).slice(-2);
            var minutes = ("0" + date.getMinutes()).slice(-2);
            var seconds = ("0" + date.getSeconds()).slice(-2);
            $("#timestamp").html(hours + ":" + minutes + ":" + seconds);
          }, 1000);
      });
    </script>
  </body>
</html>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <title><?php if(!empty($site_name)){ echo $site_name; } ?> | <?php echo $title; ?></title>
    <!-- Responsive meta -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=10, user-scalable=yes' name='viewport'>
    <meta name="description" content="Aplikasi CBT Modern Standar TKA Kemdikbud oleh Vicky Koroh">
    <meta name="keywords" content="CBT, TKA Kemdikbud, Ujian Online, Vicky Koroh">
    <meta name="author" content="Vicky Koroh">

    <!-- Bootstrap 3.3.4 & Font Awesome -->
    <link href="<?php echo base_url(); ?>public/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>public/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>public/plugins/adminlte/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>public/plugins/adminlte/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>public/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- KaTeX Offline Math Engine by Vicky Koroh -->
    <link href="<?php echo base_url(); ?>public/plugins/katex/katex.min.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>public/plugins/katex/katex.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/plugins/katex/auto-render.min.js" type="text/javascript"></script>

    <!-- jQuery & Bootstrap Scripts -->
    <script src="<?php echo base_url(); ?>public/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/plugins/adminlte/js/app.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>public/app.js" type="text/javascript"></script>

    <!-- Modern Motion & Styling System by Vicky Koroh -->
    <style type="text/css">
      * {
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        -webkit-font-smoothing: antialiased;
      }

      body {
        background-color: #0b1329 !important;
        background: radial-gradient(circle at 10% 20%, rgba(37, 99, 235, 0.15) 0%, transparent 40%),
                    radial-gradient(circle at 90% 80%, rgba(14, 165, 233, 0.12) 0%, transparent 45%),
                    radial-gradient(circle at 50% 50%, rgba(30, 58, 138, 0.2) 0%, transparent 60%),
                    #070d1e !important;
        min-height: 100vh;
        color: #1e293b;
      }

      .wrapper {
        background: transparent !important;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }

      /* Modern Glassmorphic Top Navigation */
      .cbt-modern-nav {
        background: rgba(15, 23, 42, 0.75) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 12px 0;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
      }

      .cbt-brand {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        text-decoration: none !important;
      }

      .cbt-brand-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, #2563eb 0%, #38bdf8 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 18px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
      }

      .cbt-brand-text {
        font-size: 16px;
        font-weight: 800;
        letter-spacing: -0.3px;
        color: #ffffff;
        margin: 0;
      }

      .cbt-brand-sub {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        letter-spacing: 0.3px;
        text-transform: uppercase;
      }

      .cbt-clock-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.12);
        padding: 6px 14px;
        border-radius: 20px;
        color: #e2e8f0;
        font-size: 13px;
        font-weight: 600;
      }

      .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse 1.8s infinite;
      }

      @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
      }

      /* Content Area */
      .content-wrapper {
        background: transparent !important;
        flex: 1;
        padding-top: 25px;
        padding-bottom: 40px;
      }

      /* Animations */
      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translateY(20px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .cbt-card-animated {
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      }

      /* Modern Footer */
      .cbt-modern-footer {
        background: rgba(11, 19, 41, 0.95);
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        padding: 20px 0;
        color: #94a3b8;
        font-size: 13px;
        margin-top: auto;
      }

      .cbt-modern-footer a {
        color: #38bdf8;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
      }

      .cbt-modern-footer a:hover {
        color: #7dd3fc;
        text-decoration: underline;
      }

      /* Responsive Adjustments */
      @media (max-width: 768px) {
        .cbt-clock-pill {
          display: none;
        }
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
      document.addEventListener("DOMContentLoaded", function() {
        renderMathOffline(document.body);
      });
    </script>
  </head>

  <body>
    <div class="wrapper">
      <!-- Modern Glassmorphic Topbar -->
      <nav class="cbt-modern-nav">
        <div class="container">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <a href="<?php echo base_url(); ?>" class="cbt-brand">
              <div class="cbt-brand-icon">
                <i class="fa fa-graduation-cap"></i>
              </div>
              <div>
                <h1 class="cbt-brand-text"><?php if(!empty($site_name)){ echo $site_name; }else{ echo 'CBT STANDAR TKA'; } ?></h1>
                <div class="cbt-brand-sub">PUSMENDIK KEMDIKBUD • VICKY KOROH</div>
              </div>
            </a>

            <div class="cbt-clock-pill">
              <span class="pulse-dot"></span>
              <span>WAKTU SERVER:</span>
              <span id="timestamp" style="font-family: monospace; font-weight: 700; color: #38bdf8;">--:--:--</span>
            </div>
          </div>
        </div>
      </nav>

      <!-- Main Content Dynamic Container -->
      <div class="content-wrapper">
        <?php 
        if(!empty($content)){
            echo $content; 
        }
        ?>
      </div>

      <!-- Modern Footer with Vicky Koroh Copyright -->
      <footer class="cbt-modern-footer">
        <div class="container">
          <div class="row" style="align-items: center;">
            <div class="col-sm-6 text-left">
              <strong>Hak Cipta &copy; <?php echo date('Y'); ?> Vicky Koroh. All rights reserved.</strong>
              <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                Aplikasi CBT Modern Standar TKA Kemdikbud (Pilihan Ganda, Benar/Salah, Uraian & Formula LaTeX)
              </div>
            </div>
            <div class="col-sm-6 text-right" style="margin-top: 6px;">
              <?php
                if(!empty($link_login_operator) && $link_login_operator=='ya'){
                  echo '<a href="'.site_url().'/manager/" class="btn btn-xs btn-default" style="background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.15); color: #cbd5e1;"><i class="fa fa-user-secret"></i> Portal Operator / Admin</a>';
                } else {
                  echo '<a href="'.base_url().'" class="btn btn-xs btn-default" style="background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.15); color: #cbd5e1;"><i class="fa fa-home"></i> Halaman Utama Peserta</a>';
                }
              ?>
            </div>
          </div>
        </div>
      </footer>
    </div>

    <!-- Modal Loading Global Modern -->
    <div class="modal fade" id="modal-proses" data-backdrop="static" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-sm" style="margin-top: 18vh;">
        <div class="modal-content" style="border-radius: 18px; border: none; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(12px); color: #ffffff; box-shadow: 0 20px 40px rgba(0,0,0,0.5);">
          <div class="modal-body text-center" style="padding: 30px 20px;">
            <div style="width: 48px; height: 48px; border: 4px solid rgba(56, 189, 248, 0.2); border-top-color: #38bdf8; border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 16px auto;"></div>
            <h4 style="font-weight: 700; margin: 0 0 6px 0; font-size: 16px;">Memproses Data</h4>
            <p style="margin: 0; font-size: 13px; color: #94a3b8;">Mohon tunggu beberapa saat...</p>
          </div>
        </div>
      </div>
    </div>

    <style>
      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
    </style>

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

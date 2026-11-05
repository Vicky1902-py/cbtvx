<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <title><?php if(!empty($site_name)){ echo $site_name; }else{ echo 'SMKN 1 Kupang Barat'; } ?> | <?php echo $title; ?></title>
    <!-- Viewport responsive mobile first -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, shrink-to-fit=no, user-scalable=yes">
    <meta name="description" content="Aplikasi CBT Modern Standar TKA Pusmendik Kemdikbud - SMK Negeri 1 Kupang Barat oleh Vicky Koroh">
    <meta name="author" content="Vicky Koroh">

    <!-- Favicon SMKN 1 Kupang Barat -->
    <link rel="icon" type="image/png" href="<?php echo base_url(); ?>public/images/favicon.png">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>public/images/favicon.png">

    <!-- Bootstrap 3.3.4 & Font Awesome -->
    <link href="<?php echo base_url(); ?>public/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>public/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>public/plugins/adminlte/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>public/plugins/adminlte/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>public/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
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

    <!-- SMKN 1 Kupang Barat Blue Design System & Ultra-Responsive Motion -->
    <style type="text/css">
      :root {
        --smk-blue-primary: #0077b6;
        --smk-blue-vibrant: #0096c7;
        --smk-blue-cyan: #00b4d8;
        --smk-blue-sky: #90e0ef;
        --smk-blue-dark: #03045e;
        --smk-blue-navy: #001233;
        --smk-yellow: #ffb703;
        --smk-red: #ef233c;
      }

      * {
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        -webkit-font-smoothing: antialiased;
        box-sizing: border-box;
      }

      html, body {
        overflow-x: hidden;
        width: 100%;
        min-height: 100vh;
      }

      body {
        background-color: var(--smk-blue-navy) !important;
        background: radial-gradient(circle at 15% 15%, rgba(0, 180, 216, 0.22) 0%, transparent 45%),
                    radial-gradient(circle at 85% 85%, rgba(0, 119, 182, 0.25) 0%, transparent 50%),
                    radial-gradient(circle at 50% 50%, rgba(3, 4, 94, 0.6) 0%, transparent 70%),
                    #020b1e !important;
        min-height: 100vh;
        color: #f1f5f9;
        margin: 0;
        padding: 0;
      }

      .wrapper {
        background: transparent !important;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        width: 100%;
      }

      /* Navbar SMKN 1 Kupang Barat */
      .smk-header-nav {
        background: rgba(3, 18, 48, 0.85) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 2px solid rgba(0, 180, 216, 0.35);
        padding: 10px 0;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.35);
      }

      .smk-brand-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none !important;
      }

      .smk-logo-img {
        width: 44px;
        height: 44px;
        object-fit: contain;
        filter: drop-shadow(0 3px 8px rgba(0, 180, 216, 0.45));
        transition: transform 0.25s ease;
      }

      .smk-brand-wrapper:hover .smk-logo-img {
        transform: scale(1.06);
      }

      .smk-title-main {
        font-size: 16px;
        font-weight: 800;
        color: #ffffff;
        margin: 0;
        letter-spacing: -0.2px;
        line-height: 1.2;
      }

      .smk-subtitle {
        font-size: 11px;
        font-weight: 700;
        color: var(--smk-blue-cyan);
        letter-spacing: 0.6px;
        margin-top: 2px;
        text-transform: uppercase;
      }

      .smk-time-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(0, 180, 216, 0.12);
        border: 1px solid rgba(0, 180, 216, 0.3);
        padding: 6px 14px;
        border-radius: 20px;
        color: #e0f2fe;
        font-size: 12px;
        font-weight: 600;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
      }

      .pulse-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulseEffect 2s infinite;
      }

      @keyframes pulseEffect {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
      }

      /* Content Area */
      .content-wrapper {
        background: transparent !important;
        flex: 1;
        padding-top: 20px;
        padding-bottom: 35px;
        width: 100%;
      }

      /* Entrance Animation */
      @keyframes fadeInUpSmooth {
        from {
          opacity: 0;
          transform: translateY(16px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .smk-animated-box {
        animation: fadeInUpSmooth 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      }

      /* Footer */
      .smk-footer {
        background: rgba(2, 8, 24, 0.96);
        border-top: 1px solid rgba(0, 180, 216, 0.2);
        padding: 16px 0;
        color: #94a3b8;
        font-size: 12px;
        margin-top: auto;
      }

      /* Responsive Rules */
      @media (max-width: 768px) {
        .smk-header-nav {
          padding: 8px 0;
        }
        .smk-logo-img {
          width: 36px;
          height: 36px;
        }
        .smk-title-main {
          font-size: 14px;
        }
        .smk-subtitle {
          font-size: 10px;
        }
        .smk-time-pill {
          padding: 4px 10px;
          font-size: 11px;
        }
        .smk-time-label {
          display: none;
        }
      }

      @media (max-width: 480px) {
        .smk-title-main {
          font-size: 13px;
        }
        .smk-subtitle {
          display: none;
        }
        .smk-time-pill {
          font-size: 10.5px;
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
      <!-- Navbar SMK Negeri 1 Kupang Barat -->
      <nav class="smk-header-nav">
        <div class="container">
          <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <a href="<?php echo base_url(); ?>" class="smk-brand-wrapper">
              <img src="<?php echo base_url(); ?>public/images/logo_smk.png" alt="Logo SMKN 1 Kupang Barat" class="smk-logo-img" />
              <div>
                <h1 class="smk-title-main">SMKN 1 KUPANG BARAT</h1>
                <div class="smk-subtitle">CBT MODERN STANDAR TKA PUSMENDIK</div>
              </div>
            </a>

            <div class="smk-time-pill">
              <span class="pulse-indicator"></span>
              <span class="smk-time-label">SERVER:</span>
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
      <footer class="smk-footer">
        <div class="container">
          <div class="row" style="align-items: center;">
            <div class="col-sm-7 col-xs-12 text-left" style="margin-bottom: 6px;">
              <strong>Hak Cipta &copy; <?php echo date('Y'); ?> Vicky Koroh. All rights reserved.</strong>
              <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                Aplikasi CBT Modern Standar TKA Kemdikbud &bull; SMK Negeri 1 Kupang Barat
              </div>
            </div>
            <div class="col-sm-5 col-xs-12 text-right">
              <?php
                if(!empty($link_login_operator) && $link_login_operator=='ya'){
                  echo '<a href="'.site_url().'/manager/" class="btn btn-xs btn-default" style="background: rgba(0, 180, 216, 0.15); border-color: rgba(0, 180, 216, 0.3); color: #38bdf8; font-weight: 600;"><i class="fa fa-user-secret"></i> Portal Operator / Admin</a>';
                } else {
                  echo '<a href="'.base_url().'" class="btn btn-xs btn-default" style="background: rgba(0, 180, 216, 0.15); border-color: rgba(0, 180, 216, 0.3); color: #38bdf8; font-weight: 600;"><i class="fa fa-home"></i> Halaman Utama Peserta</a>';
                }
              ?>
            </div>
          </div>
        </div>
      </footer>
    </div>

    <!-- Modal Loading Global Modern -->
    <div class="modal fade" id="modal-proses" data-backdrop="static" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-sm" style="margin: 20vh auto 0 auto; max-width: 320px; width: 90%;">
        <div class="modal-content" style="border-radius: 18px; border: 1px solid rgba(0, 180, 216, 0.3); background: rgba(3, 18, 48, 0.95); backdrop-filter: blur(16px); color: #ffffff; box-shadow: 0 20px 40px rgba(0,0,0,0.6);">
          <div class="modal-body text-center" style="padding: 28px 20px;">
            <div style="width: 44px; height: 44px; border: 4px solid rgba(0, 180, 216, 0.2); border-top-color: #00b4d8; border-radius: 50%; animation: spinLoader 0.8s linear infinite; margin: 0 auto 14px auto;"></div>
            <h4 style="font-weight: 700; margin: 0 0 6px 0; font-size: 15px;">Sedang Memproses...</h4>
            <p style="margin: 0; font-size: 12px; color: #94a3b8;">Mohon tunggu beberapa saat</p>
          </div>
        </div>
      </div>
    </div>

    <style>
      @keyframes spinLoader {
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

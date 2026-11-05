<style type="text/css">
  /* Split Screen Architecture - Fully Responsive & Centered */
  .cbt-split-wrapper {
    max-width: 1040px;
    width: min(1040px, 94vw);
    margin: 20px auto 35px auto;
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.65), 0 0 0 1px rgba(0, 180, 216, 0.25);
    display: flex;
    flex-direction: row;
    min-height: 520px;
  }

  /* Left Column: Slideshow Showcase */
  .cbt-slide-col {
    flex: 1.15;
    position: relative;
    background: #020c22;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    min-height: 520px;
  }

  .cbt-carousel-track {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }

  .cbt-slide-frame {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center 25%;
    background-repeat: no-repeat;
    opacity: 0;
    transition: opacity 0.8s ease-in-out, transform 1.2s ease-out;
    transform: scale(1.02);
  }

  .cbt-slide-frame.active {
    opacity: 1;
    transform: scale(1);
  }

  .cbt-slide-scrim {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(180deg, rgba(2, 11, 30, 0.05) 0%, rgba(2, 11, 30, 0.3) 45%, rgba(2, 11, 30, 0.88) 75%, #020c22 100%);
    pointer-events: none;
  }

  .cbt-slide-caption {
    position: relative;
    z-index: 5;
    padding: 24px 30px 65px 30px;
    color: #ffffff;
  }

  .cbt-slide-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(0, 180, 216, 0.22);
    border: 1px solid rgba(0, 180, 216, 0.45);
    backdrop-filter: blur(8px);
    color: #e0f2fe;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    margin-bottom: 10px;
  }

  .cbt-slide-title {
    font-size: clamp(19px, 2.2vw, 24px);
    font-weight: 800;
    margin: 0 0 6px 0;
    line-height: 1.3;
    color: #ffffff;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
  }

  .cbt-slide-desc {
    font-size: 13px;
    color: #cbd5e1;
    margin: 0;
    line-height: 1.55;
    max-width: 480px;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.4);
  }

  /* Carousel Controls */
  .cbt-carousel-controls {
    position: absolute;
    bottom: 18px;
    left: 30px;
    right: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 10;
  }

  .cbt-dots {
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .cbt-dot {
    width: 8px;
    height: 8px;
    border-radius: 4px;
    background: rgba(255, 255, 255, 0.4);
    cursor: pointer;
    transition: all 0.25s ease;
  }

  .cbt-dot.active {
    width: 26px;
    background: #00b4d8;
  }

  .cbt-arrow-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.2s ease;
    backdrop-filter: blur(6px);
  }

  .cbt-arrow-btn:hover {
    background: rgba(0, 180, 216, 0.5);
    border-color: #00b4d8;
  }

  /* Right Column: Clean Professional Admin Form */
  .cbt-form-col {
    flex: 1;
    background: #ffffff;
    padding: clamp(24px, 3.5vw, 38px);
    display: flex;
    flex-direction: column;
    justify-content: center;
    border-left: 1px solid rgba(226, 232, 240, 0.9);
  }

  .cbt-input-group {
    margin-bottom: 18px;
  }

  .cbt-input-label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 6px;
  }

  .cbt-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
  }

  .cbt-input-icon {
    position: absolute;
    left: 14px;
    color: #64748b;
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
  }

  .cbt-text-input {
    width: 100%;
    height: 46px;
    padding: 0 16px 0 44px;
    border-radius: 10px;
    border: 1.5px solid #cbd5e1;
    background: #f8fafc;
    color: #0f172a;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s ease;
    outline: none;
  }

  .cbt-text-input:focus {
    background: #ffffff;
    border-color: #0077b6;
    box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.15);
  }

  .cbt-submit-btn {
    width: 100%;
    height: 48px;
    border-radius: 10px;
    background: linear-gradient(135deg, #0077b6 0%, #0096c7 100%);
    color: #ffffff;
    font-size: 15px;
    font-weight: 700;
    border: none;
    box-shadow: 0 6px 18px rgba(0, 119, 182, 0.35);
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 10px;
  }

  .cbt-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(0, 119, 182, 0.45);
    background: linear-gradient(135deg, #006097 0%, #0087b3 100%);
  }

  /* Responsive Rules for Tablets and Phones */
  @media (max-width: 960px) {
    .cbt-split-wrapper {
      flex-direction: column;
      border-radius: 16px;
      margin: 12px auto 25px auto;
      width: 95%;
      min-height: auto;
    }
    .cbt-slide-col {
      min-height: 240px;
      flex: none;
    }
    .cbt-slide-caption {
      padding: 16px 20px 45px 20px;
    }
    .cbt-slide-desc {
      display: none;
    }
    .cbt-carousel-controls {
      bottom: 12px;
      left: 20px;
      right: 20px;
    }
    .cbt-form-col {
      padding: 24px 20px;
      border-left: none;
      border-top: 1px solid #e2e8f0;
    }
  }

  @media (max-width: 480px) {
    .cbt-split-wrapper {
      width: 96%;
      border-radius: 14px;
      margin: 8px auto 20px auto;
    }
    .cbt-slide-col {
      min-height: 190px;
    }
    .cbt-slide-title {
      font-size: 16px;
    }
    .cbt-form-col {
      padding: 20px 16px;
    }
  }
</style>

<div class="cbt-split-wrapper">
    <!-- KOLOM KIRI: SLIDESHOW GAMBAR AKTIVITAS SEKOLAH -->
    <div class="cbt-slide-col">
        <div class="cbt-carousel-track">
            <?php if(!empty($slides) && is_array($slides)): ?>
                <?php foreach($slides as $idx => $s): ?>
                    <div class="cbt-slide-frame <?php echo $idx === 0 ? 'active' : ''; ?>" id="slide-item-<?php echo $idx; ?>" style="background-image: url('<?php echo $s['gambar']; ?>');"></div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="cbt-slide-frame active" style="background-image: url('<?php echo base_url('public/images/slides/slide1.jpg'); ?>');"></div>
            <?php endif; ?>
        </div>

        <div class="cbt-slide-scrim"></div>

        <!-- Slide Caption -->
        <div class="cbt-slide-caption">
            <div class="cbt-slide-pill">
                <span class="pulse-indicator"></span> SMK NEGERI 1 KUPANG BARAT
            </div>
            <h2 class="cbt-slide-title" id="slide-caption-title">
                <?php echo !empty($slides[0]['judul']) ? htmlspecialchars($slides[0]['judul']) : 'Sistem Asesmen Berbasis Komputer'; ?>
            </h2>
            <p class="cbt-slide-desc" id="slide-caption-desc">
                <?php echo !empty($slides[0]['deskripsi']) ? htmlspecialchars($slides[0]['deskripsi']) : 'Pelaksanaan ujian terstandar TKA Pusmendik Kemdikbud di SMK Negeri 1 Kupang Barat yang aman, presisi, dan terpercaya.'; ?>
            </p>
        </div>

        <!-- Carousel Indicators & Arrows -->
        <div class="cbt-carousel-controls">
            <div class="cbt-dots" id="cbtDots">
                <?php if(!empty($slides)): ?>
                    <?php foreach($slides as $idx => $s): ?>
                        <span class="cbt-dot <?php echo $idx === 0 ? 'active' : ''; ?>" onclick="gotoSlide(<?php echo $idx; ?>)"></span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div style="display: flex; gap: 6px;">
                <button type="button" class="cbt-arrow-btn" onclick="prevSlide()" title="Slide Sebelumnya">&#10094;</button>
                <button type="button" class="cbt-arrow-btn" onclick="nextSlide()" title="Slide Berikutnya">&#10095;</button>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: FORM LOGIN ADMINISTRATOR -->
    <div class="cbt-form-col">
        <div style="text-align: left; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 10px;">
                <img src="<?php echo base_url(); ?>public/images/logo_smk.png" alt="Logo SMKN 1 Kupang Barat" 
                     style="width: 52px; height: 52px; object-fit: contain; filter: drop-shadow(0 3px 8px rgba(0, 119, 182, 0.3));" />
                <div>
                    <span style="display: inline-block; background: #e0f2fe; color: #0077b6; font-size: 10.5px; font-weight: 700; padding: 2.5px 9px; border-radius: 6px; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">
                        PANEL OPERATOR & GURU
                    </span>
                    <h2 style="font-size: 19px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">
                        LOGIN ADMINISTRATOR CBT
                    </h2>
                </div>
            </div>
            <p style="font-size: 13px; color: #64748b; margin: 0;">
                Masuk ke konsol kontrol ujian untuk manajemen bank soal, peserta, dan evaluasi nilai.
            </p>
        </div>

        <!-- Alert Box Pesan Error -->
        <div id="form-pesan" style="margin-bottom: 16px;"></div>

        <!-- Form Login Operator -->
        <?php echo form_open('manager/welcome/login', 'id="form-login"'); ?>
            <div class="cbt-input-group">
                <label for="username" class="cbt-input-label">Username Operator</label>
                <div class="cbt-input-wrapper">
                    <span class="cbt-input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </span>
                    <input type="text" class="cbt-text-input" id="username" name="username" placeholder="Masukkan Username Admin" required autocomplete="username" />
                </div>
            </div>

            <div class="cbt-input-group" style="margin-bottom: 18px;">
                <label for="password" class="cbt-input-label">Password Operator</label>
                <div class="cbt-input-wrapper">
                    <span class="cbt-input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </span>
                    <input type="password" class="cbt-text-input" id="password" name="password" placeholder="Masukkan Password Akun" required autocomplete="current-password" />
                    <span onclick="togglePasswordVisibility()" style="position: absolute; right: 14px; top: 14px; color: #94a3b8; cursor: pointer;" title="Lihat/Sembunyikan Password">
                        <svg id="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </span>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px;">
                <label style="color: #64748b; font-size: 13px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; margin: 0;">
                    <input type="checkbox" id="show-password-chk" onchange="togglePasswordVisibility()" style="cursor: pointer; width: 16px; height: 16px;">
                    <span>Tampilkan Password</span>
                </label>
                <span style="font-size: 12px; color: #0077b6; font-weight: 600;">
                    <i class="fa fa-lock"></i> Akses Terbatas
                </span>
            </div>

            <button type="submit" id="btn-login-operator" class="cbt-submit-btn">
                <span>Masuk ke Panel Kontrol</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </button>
        <?php echo form_close(); ?>

        <!-- Footer Signature -->
        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #f1f5f9; text-align: center;">
            <div style="font-size: 12px; color: #64748b;">
                Hak Cipta &copy; <b>Vicky Koroh</b> &bull; SMKN 1 Kupang Barat
            </div>
            <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
                Standar Asesmen TKA Pusmendik Kemdikbud
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-proses" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.3); overflow: hidden;">
            <div class="modal-body text-center" style="padding: 28px 20px;">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem; margin-bottom: 14px;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div style="font-weight: 700; color: #0f172a; font-size: 15px;">Memverifikasi Akun Operator...</div>
                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Mohon tunggu sebentar</div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var slidesData = <?php echo json_encode(!empty($slides) ? $slides : array()); ?>;
    var currentSlide = 0;
    var slideTimer = null;

    function renderSlide(index) {
        if (!slidesData || slidesData.length === 0) return;
        if (index < 0) index = slidesData.length - 1;
        if (index >= slidesData.length) index = 0;
        currentSlide = index;

        $('.cbt-slide-frame').removeClass('active');
        $('#slide-item-' + currentSlide).addClass('active');

        $('.cbt-dot').removeClass('active');
        $('.cbt-dot').eq(currentSlide).addClass('active');

        if (slidesData[currentSlide]) {
            $('#slide-caption-title').text(slidesData[currentSlide].judul || '');
            $('#slide-caption-desc').text(slidesData[currentSlide].deskripsi || '');
        }
    }

    function nextSlide() {
        renderSlide(currentSlide + 1);
        resetTimer();
    }

    function prevSlide() {
        renderSlide(currentSlide - 1);
        resetTimer();
    }

    function gotoSlide(idx) {
        renderSlide(idx);
        resetTimer();
    }

    function resetTimer() {
        if (slideTimer) clearInterval(slideTimer);
        slideTimer = setInterval(function() {
            renderSlide(currentSlide + 1);
        }, 5000);
    }

    function togglePasswordVisibility() {
        var pwd = document.getElementById("password");
        var chk = document.getElementById("show-password-chk");
        if (pwd.type === "password") {
            pwd.type = "text";
            if (chk) chk.checked = true;
            $('#eye-icon').html('<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>');
        } else {
            pwd.type = "password";
            if (chk) chk.checked = false;
            $('#eye-icon').html('<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>');
        }
    }

    $(function () {
        resetTimer();
        $('#username').focus();   
        
        $('#form-login').submit(function(e){
            e.preventDefault();
            $("#modal-proses").modal('show');
            $.ajax({
                url: "<?php echo site_url(); ?>/manager/welcome/login",
                type: "POST",
                data: $('#form-login').serialize(),
                cache: false,
                dataType: "json",
                success: function(obj){
                    if(obj.status == 1){
                        window.location.href = "<?php echo site_url(); ?>/manager/dashboard";
                    } else {
                        $("#modal-proses").modal('hide');
                        $('#form-pesan').html(
                            '<div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 600;">' +
                            'Username atau password operator salah. Silahkan periksa kembali.' +
                            '</div>'
                        );
                        $('#username').focus();
                    }
                },
                error: function() {
                    $("#modal-proses").modal('hide');
                    $('#form-pesan').html(
                        '<div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 600;">' +
                        'Terjadi gangguan komunikasi dengan server.' +
                        '</div>'
                    );
                }
            });
            return false;
        });    
    });
</script>

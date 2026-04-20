<!DOCTYPE html>
<html lang="id">
<head>
    <title>Portal Yayasan Asih Putera</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Pastikan path CSS bawaan template lu bener -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/login-form-20/css/style.css'); ?>">

    <!-- Tambahan GSAP buat Animasi Smooth -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<!-- Hapus js-fullheight bawaan, kita ganti pake class Flexbox Bootstrap aja -->
<body class="img" style="background-image: url('<?= base_url('assets/images/snapedit_1724307822281.jpeg'); ?>'); background-size: cover; background-position: center; background-attachment: fixed;">
    
    <!-- FIX CENTER MIDDLE: Pake min-vh-100, d-flex, align-items-center -->
    <section class="min-vh-100 d-flex align-items-center justify-content-center">
        <div class="container">
            
            <!-- Bagian Logo -->
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-4 gsap-logo">
                    <img src="<?= base_url('assets/images/aplogo.png'); ?>" alt="Logo Asih Putera" class="img-fluid w-50">
                </div>
            </div>

            <!-- Bagian Form -->
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="login-wrap p-0">
                        <h4 class="mb-4 text-center gsap-item" style="color:white; font-weight: 600;">&mdash; MiniPortal YAP &mdash;</h4>
                        
                        <!-- Form Login -->
                        <form action="<?= base_url('auth/login_process'); ?>" method="post" class="signin-form">
                            
                            <div class="form-group gsap-item">
                                <input type="text" class="form-control" placeholder="Username" name="username" required autofocus>
                            </div>
                            
                            <div class="form-group gsap-item">
                                <input id="password-field" type="password" class="form-control" placeholder="Password" name="password" required>
                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>
                            
                            <div class="form-group mt-4 gsap-item">
                                <!-- Efek hover di CSS bawaan mungkin perlu disesuaikan kalau nabrak, tapi styling lu ini udah clean -->
                                <button type="submit" class="form-control btn btn-primary submit px-3" style="background: white !important; border: 1px solid white !important; color: #000 !important; font-weight: bold; transition: 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">Sign In</button>
                            </div>
                            
                            <!-- Link Bantuan -->
                            <div class="form-group d-flex justify-content-center mt-3 gsap-item">
                                <a href="#" data-toggle="modal" data-target="#helpModal" style="color: #fff; text-decoration: underline; font-size: 14px;">Mengalami Kendala?</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Bantuan -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="helpModalLabel" style="color: #333; font-weight: bold;">Bantuan Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="color: #555; text-align: justify; line-height: 1.6;">
                    Username dan Password yang digunakan didapat dari tim IT YAP, hubungi tim jika ingin mendaftarkan user!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    <!-- Script bawaan template login lu -->
    <script src="<?= base_url('assets/plugins/login-form-20/js/main.js'); ?>"></script>

    <!-- Animasi GSAP -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Animasi logo turun dari atas
            gsap.from(".gsap-logo", { 
                y: -40, 
                opacity: 0, 
                duration: 1, 
                ease: "power3.out" 
            });

            // Animasi input dan tombol muncul berurutan dari bawah
            gsap.from(".gsap-item", { 
                y: 30, 
                opacity: 0, 
                duration: 0.8, 
                stagger: 0.15, // Jeda antar elemen
                ease: "power3.out",
                delay: 0.3 // Nunggu logo selesai turun dikit baru jalan
            });
        });
    </script>
</body>
</html>
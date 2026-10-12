<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>Admin Sistem Pembayaran ITB STIKOM Bali</title>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet" />
    
    <!-- reCAPTCHA v2 -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <link rel="stylesheet" href="{{ asset('public/asset/css/themes/lite-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/plugins/perfect-scrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/plugins/smart.wizard/smart_wizard_theme_arrows.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/css/plugins/sweetalert2.min.css') }}">
</head>

<body>

<div class="auth-layout-wrap">
    <div class="auth-content">
        <div class="card o-hidden">
            <div class="row">
                
                <!-- Left -->
                <div class="col-md-6 text-center" style="background-size: cover;background-position: center; background-image: url(public/images/BG_stikom_bali.jpg)">
                    <div class="auth-right">
                        <div class="flex-grow-1 mb-48 mt-48"></div>
                    </div>
                </div>

                <!-- Right -->
                <div class="col-md-6">
                    <div class="p-4">
                        <img src="{{ asset('public/asset/images/logo_stikom.png') }}" style="max-height: 70px;" alt="">
                        <h1 class="mb-3 text-24 font-weight-600 mt-3">Let's Begin</h1>

                        <!-- Loading -->
                        <section id="loading">
                            <div id="loading-content"></div>
                        </section>

                        <form action="{{ url('proses_login') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="nim">E-Mail / Username</label>
                                <input class="form-control form-control-rounded" id="username" type="text" name="email" placeholder="Masukkan username" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input class="form-control form-control-rounded" id="password" type="password" name="password" placeholder="Masukkan Password">
                            </div>

                            <!-- reCAPTCHA v2 -->
                            <div class="g-recaptcha" data-sitekey="6LfIL2UsAAAAAFSb1F9KXLrDZYGI9GYsRZw1Fkpq"></div>

                            <button type="submit" class="btn btn-rounded btn-primary btn-block mt-2" onclick="loading_log()">Login</button>

                            <p style="margin-top: 20px;">© 2021 ITB STIKOM Bali</p>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

</body>
</html>

<!-- Loading Overlay Script -->
<script>
function loading_log(){
    showLoading();
}
function showLoading() {
    document.querySelector('#loading').classList.add('loading');
    document.querySelector('#loading-content').classList.add('loading-content');
}
function hideLoading() {
    document.querySelector('#loading').classList.remove('loading');
    document.querySelector('#loading-content').classList.remove('loading-content');
}
</script>

<style>
.loading {
    z-index: 20;
    position: absolute;
    top: 0;
    left:-5px;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4);
}
.loading-content {
    position: absolute;
    border: 16px solid #f3f3f3;
    border-top: 16px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    top: 40%;
    left:35%;
    animation: spin 2s linear infinite;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

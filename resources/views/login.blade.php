@extends('templatebody')
@section('konten_utama')
<div class="auth-one-bg-position auth-one-bg" id="auth-particles">
    <div class="bg-overlay"></div>
    <div class="shape">
        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
            <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
        </svg>
    </div>
</div>
<!-- auth page content -->
<div class="auth-page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mt-sm-5 mb-4 text-white-50">
                    <img src="{{asset('v1/assets/images/logo_pgri.png')}}" alt="" height="150">
                    <p class="mt-1 fs-15 fw-medium">Portal Masuk Perpustakaan - SMKS PGRI 6 Malang</p>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card mt-1 card-bg-fill">

                    <div class="card-body p-4">
                        <div class="text-center mt-2">
                            <h5 class="text-primary">Hai.. Aku Esemkarinam !</h5>
                            <p class="text-muted">Ketikan informasi kredensial sebelum akses ke Esemkarinam.</p>
                        </div>
                        <div id="loginContainer">
                        <div class="p-2 mt-4">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nama Pengguna</label>
                                <input value="erayadigital" type="text" class="form-control" id="username" placeholder="Masukan nama pengguna terdaftar">
                            </div>

                            <div class="mb-3">
                                <div class="float-end">
                                    <a href="javascript:void()" id="bantuan" class="text-muted">Bantuan !!</a>
                                </div>
                                <label class="form-label" for="password-input">Kata Sandi</label>
                                <div class="position-relative auth-pass-inputgroup mb-3">
                                    <input value="IniPassw0RdYaNkkuaTBeutHzz4083,.@" type="password" class="form-control pe-5 password-input" placeholder="Ketikan Kata Sandi" id="password-input">
                                    <button id="togglePassword" class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button id="btn_login" class="btn btn-success-ids w-100">Masuk Ke Panel Beranda App Esemkarinam</button>
                            </div>
                        </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <div class="mt-4 text-center">
                    <p class="mb-0">Lupa Dengan Akun Anda ? <a href="javascript:void(0)" id="lupa_password_modal" class="fw-semibold text-primary text-decoration-underline"> Buat Ulang Kata Sandi </a> </p>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>

<div class="modal zoomIn" tabindex="-1" id="modal_lupa_password" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Formulir Lupa Kata Sandi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <h5 class="fs-15">Ikuti Langkah Berikut Untuk Lupa Kata Sandi</h5>
                <p class="text-muted">
                    <ol type="1">
                        <li>Ketikan alamat surel yang anda daftarkan pada aplikasi App Esemkarinam. Jika lupa hubungi TIM IT untuk meminta bantuan</li>
                        <li>Klik atau tekan tombol KIRIM PERMINTAAN, kemudian buka email anda untuk mendapatkan LINK TOKEN untuk memperbarui kata sandi anda</li>
                        <li>Ganti kata sandi anda sesuai keinginan. Kami sarankan menggunakan lebih dari 8 - 10 karakter kombinasi huruf (huruf kapital dan kecil), angka-angka, dan simbol-simbol, serta tidak menggunakan kata-kata umum atau informasi pribadi</li>
                    </ol>  
                </p>
                <hr>
            <input type="text" id="email_terkait" placeholder="Ketikan alamat surel (e-mail) terdaftar" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" id="kirim_perimintaan_atur_ulang_sandi" class="btn btn-success-ids "><i class="ri-mail-send-line
                    "></i> KIRIM PERMINTAAN <i class="ri-mail-send-line
                    "></i></button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
  
<!-- end auth page content -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center">
                    <p class="mb-0 text-muted">&copy;
                        <script>document.write(new Date().getFullYear())</script> SMK PGRI 6 Malang. Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://smkpgri6malang.sch.id" target="_BLANK">by Sandi Idris Zulfikar a.k.a TIM IT</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
@endsection
@section('css_load')
@endsection
@section('js_load')
<script src="{{ asset('v1/assets/js/app/login.js')}}"></script>
@endsection
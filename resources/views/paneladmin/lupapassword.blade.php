@extends('templatebody')
@section('konten_utama')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5">
            <div class="card overflow-hidden card-bg-fill galaxy-border-none">
                <div class="card-body p-4">
                    <div class="text-center">
                        <lord-icon class="avatar-xl" src="https://cdn.lordicon.com/etwtznjn.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c"></lord-icon>
                        <h1 class="text-primary mb-4">Yey..... Bintang Jatuh !</h1>
                        <h4 class="text-uppercase">Selamat. Sekarang anda dapat merubah kata sandi anda. Jangan sampai lupa lagi ya..</h4>
                        <span id="email_reset">{{$data['email']}}</span>
                        <hr>
                        <div class="position-relative auth-pass-inputgroup mb-3">
                            <input type="password" id="sandibaru" class="form-control mb-2" placeholder="Ketikan kata sandi anda">
                            <button id="togglePassword_awal" class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                        </div>
                        <div class="position-relative auth-pass-inputgroup mb-3">
                            <input type="password" id="konfirmasisandibaru" class="form-control" placeholder="Ulangi ya buat konfirmasi barang kali typo">
                            <button id="togglePassword_konfirmasi" class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                        </div>
                        <hr>
                        <button class="btn btn-success-ids" id="btn_ubah_katasandi"><i class=" ri-lock-password-line"></i>Ubah Kata Sandi</button>
                        <a href="{{url('')}}" class="btn btn-success"><i class="mdi mdi-home me-1"></i> {{__('common.back_to_login')}}</a>
                    </div>
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</div>
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center">
                    <p class="mb-0 text-muted">&copy;
                        <script>document.write(new Date().getFullYear())</script> BPKAD Kota Tual. Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://inodreamstudio.com" target="_BLANK">InoDreamStudio a.k.a IDS Malang</a>
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
<script src="{{ asset('v1/assets/js/app/forgotpassword.js')}}"></script>
@endsection
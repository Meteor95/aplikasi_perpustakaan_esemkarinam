@extends('paneladmin.templateadmin')
@section('konten_paneladmin')
<div class="page-content">
    <div class="container-fluid">
        @include('includes.breadcumb', ['breadcrumbs' => $breadcrumbs])
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Daftar Hak Akses Aplikasi Esemkarinam</h5>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-justified nav-border-top nav-border-top-success mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#nav-border-justified-home" role="tab" aria-selected="false">
                                    <i class="ri-login-box-line align-middle me-1"></i> Daftar Hak Akses
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#nav-border-justified-profile" role="tab" aria-selected="false">
                                    <i class="ri-user-line me-1 align-middle"></i> Konfigurasi Hak Akses
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#nav-border-justified-messages" role="tab" aria-selected="false">
                                    <i class="ri-lifebuoy-line align-middle me-1"></i>Bantuan
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content text-muted">
                            <div class="tab-pane active" id="nav-border-justified-home" role="tabpanel">
                                <input id="kotak_pencarian" type="text" class="form-control" placeholder="Ketikan Kelompok Akses Pengguna. Ex: SKPD">
                                <table id="daftar_ha" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%"></table>
                            </div>
                            <div class="tab-pane" id="nav-border-justified-profile" role="tabpanel">
                                <form id="form_pendaftaran_ha">
                                <input required type="text" class="form-control" id="nama_hak_akses" placeholder="Masukkan nama hak akses aplikasi. ex: Admin Esemkarinam">
                                <div class="table-responsive">
                                <table id="tabel_hak_akses" class="table table-hover align-middle ">
                                    <thead>
                                        <tr style="text-align:center">
                                            <th scope="col">Menu Akses</th>
                                            <th scope="col">Proses Akses</th>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Beranda</td>
                                            <td>Akses Beranda</td>
                                            <td>Pengguna dapat mengakses beranda pada aplikasi Esemkarinam untuk melihat informasi umum terkait data</td>
                                            <td style="text-align:center"><input id="beranda" class="form-check-input mt-0" type="checkbox" style="font-size: 20px;" aria-label="Checkbox for following text input"></td>
                                        </tr>
                                        <tr>
                                            <td>Kredential dan Pegawai</td>
                                            <td>Akses Kredential dan Pegawai</td>
                                            <td>Pengguna dapat mengakses daftar dan mengelola kredensial dan pengguna atas aplikasi Esemkarinam termasuk informasi</td>
                                            <td style="text-align:center"><input id="kredential_dan_pegawai" class="form-check-input mt-0" type="checkbox" style="font-size: 20px;" aria-label="Checkbox for following text input"></td>
                                        </tr>
                                        <tr>
                                            <td>Surat Masuk</td>
                                            <td>Akses Surat Masuk</td>
                                            <td>Pengguna dapat mengakses informasi berkas / surat masuk dari devisi lain untuk di verifikasi oleh yang bersangkutan</td>
                                            <td style="text-align:center"><input id="surat_masuk" class="form-check-input mt-0" type="checkbox" style="font-size: 20px;" aria-label="Checkbox for following text input"></td>
                                        </tr>
                                        <tr>
                                            <td>Surat Keluar</td>
                                            <td>Akses Surat Keluar</td>
                                            <td>Pengguna dapat menakses sistem dalam pembuatan / pengiriman berkas untuk divisi tujuan agar berkas / surat dapat di validasi</td>
                                            <td style="text-align:center"><input id="surat_keluar" class="form-check-input mt-0" type="checkbox" style="font-size: 20px;" aria-label="Checkbox for following text input"></td>
                                        </tr>
                                        <tr>
                                            <td>Disposisi Surat</td>
                                            <td>Akses Disposisi Surat</td>
                                            <td>Pengguna dapat melihat semua informasi arus / status dari berkas yang ingin dilihat seperti status berkas, posisi berkas saat ini, atau revisi apa saja yang terjadi pada berkas terkait</td>
                                            <td style="text-align:center"><input id="disposisi_surat" class="form-check-input mt-0" type="checkbox" style="font-size: 20px;" aria-label="Checkbox for following text input"></td>
                                        </tr>
                                        <tr>
                                            <td>Hak Akses Sistem</td>
                                            <td>Akses Hak Akses</td>
                                            <td>Hak akses adalah fitur pengelompokan pengguna aplikasi berdasarkan tingkata level. Hak akses menentukan apakah suatu pengguna bisa melakukan akses fitur terpilih atau tidak</td>
                                            <td style="text-align:center"><input id="hak_akses_sistem" class="form-check-input mt-0" type="checkbox" style="font-size: 20px;" aria-label="Checkbox for following text input"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                </form>
                                </div>
                                <div class="card-footer">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button class="btn btn-outline-danger">Bersihkan</a>
                                        <button id="simpan_hak_akses" class="btn btn-success-ids">Simpan Hak Akses</a>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="nav-border-justified-messages" role="tabpanel">
                                <h6>Bantuan</h6>
                                <p class="mb-0">
                                    <ol type="1">
                                        <li>Informasi FULL ADMIN dan FULL NON EMPLOYED tidak bisa dihapus atau diubah karena itu ketentuan sistem Esemkarinam</li>
                                        <li>Jika ingin menambah hak akses, silahkan pergi ke TAB Konfigurasi Hak Akses kemudian masukan NAMA GRUP Hak Akses serta tentukan juga hak atas akses apa saya yang dapat dilakukan oleh pengguna nanti</li>
                                        <li>Jika sudah terbentuk hak akses silahkan tentuka pengguna tersebut memiliki hak akses apa saja pada menu Kredential dan Pegawai</li>
                                        <li>Jika hak akses dihapus tetapi masih di kaitkan dengan pengguna maka hak akses secara otomatis akan diarahkan ke HAK AKSES FULL NON EMPLOTED</li>
                                    </ol>  
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->
@endsection
@section('css_load')
@component('komponen.css.datatables')
@endcomponent
@endsection
@section('js_load')
@component('komponen.js.datatables')
@endcomponent
<script src="{{ asset('v1/assets/js/app/hakakses.js')}}"></script>
@endsection
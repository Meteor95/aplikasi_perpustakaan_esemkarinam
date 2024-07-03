@extends('paneladmin.templateadmin')
@section('konten_paneladmin')
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        @include('includes.breadcumb', ['breadcrumbs' => $breadcrumbs])
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Daftar Seluruh Murid SMK PGRI 6 Malang</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 mb-2">
                                <div class="d-flex justify-content-between gap-2">
                                    <button id="tambah_murid" class="btn btn-outline-primary w-100"><i class="ri-folder-user-fill"></i> Tambah Data</button>
                                    <button id="import_murid_excel" class="btn btn-outline-success w-100"><i class="ri-file-excel-2-line"></i> Import Excel</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <input id="kotak_pencarian" type="text" class="form-control" placeholder="Ketikan informasi murid. Ex: Nama Lengkap, NIS, NISN, Alamat, Nomor Telepn">
                            </div>
                        </div>
                        <div class="row">
                            <table id="daftar_murid" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- container-fluid -->
</div>
<div class="modal zoomIn modal-lg" tabindex="-1" id="modal_tambah_siswa" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Penambahan Informasi Murid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <form id="form_pendaftaran" class="needs-validation" novalidate>
                <ul class="nav nav-tabs nav-justified nav-border-top nav-border-top-success mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#nav-border-justified-home" role="tab" aria-selected="false">
                            <i class="ri-login-box-line align-middle me-1"></i> Kredential
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#nav-border-justified-profile" role="tab" aria-selected="false">
                            <i class="ri-user-line me-1 align-middle"></i> Profil
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
                        <h6>Ini Adalah Informasi Untuk Melakukan Autentifikasi Aplikasi</h6>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="username" class="form-label">Nama Pengguna</label>
                            </div>
                            <div class="col-lg-9">
                                <input required type="text" class="form-control" id="username" placeholder="Ketikan username / nama pengguna ex: erayadigital">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="surel" class="form-label">Surel</label>
                            </div>
                            <div class="col-lg-9">
                                <input required type="email" class="form-control" id="surel" placeholder="Ketikan surel anda ex: hai@erayadigital.co.id">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="katansandi" class="form-label">Kata Sandi</label>
                            </div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <input required type="password" id="katansandi" class="form-control" placeholder="Buat yang sulit ditebak dan mudah diingat">
                                    <button id="togglePassword" class="btn btn-outline-primary"><i class="ri-eye-fill align-middle"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="katansandi" class="form-label">Hak Akses Aplikasi</label>
                            </div>
                            <div class="col-lg-9">
                                <select id="select2_hak_akses" class="form-control"></select>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="nav-border-justified-profile" role="tabpanel">
                        <h6>Informasi Kelengkapan Profil Murid</h6>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="nis" class="form-label">NIS</label>
                            </div>
                            <div class="col-lg-9">
                                <input required type="text" class="form-control" id="nis" placeholder="Masukan NIS dari Siswa">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="nisn" class="form-label">NISN</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" id="nisn" placeholder="Masukan NISN dari pemerintah pusat">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            </div>
                            <div class="col-lg-9">
                                <input required type="text" class="form-control" id="nama_lengkap" placeholder="Ex: Sandi Idris Zulfikar">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="nama_panggilan" class="form-label">Nama Panggilan</label>
                            </div>
                            <div class="col-lg-9">
                                <input required type="text" class="form-control" id="nama_panggilan" placeholder="ex: EDS Malang">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="alamat" class="form-label">Alamat</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" id="alamat" placeholder="ex: Jl. Tarupala Gang 2">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="nomor_telepon" class="form-label">WA Murid</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" id="nomor_telepon" placeholder="ex: 08x257xxx535">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="nomor_telepon_ortu" class="form-label">WA Orang Tua / Wali</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" id="nomor_telepon_ortu" placeholder="ex: 08x257xxx535">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="keringanan" class="form-label">Keringanan</label>
                            </div>
                            <div class="col-lg-9">
                                <input required type="text" class="form-control" id="keringanan" placeholder="Nominal keringanan dalam hitungan tiap bulan">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="select2_kelas" class="form-label">Kelas</label>
                            </div>
                            <div class="col-lg-9">
                                <select id="select2_kelas" class="form-control"></select>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="select2_tahun_ajaran" class="form-label">Tahun Ajaran</label>
                            </div>
                            <div class="col-lg-9">
                                <select id="select2_tahun_ajaran" class="form-control"></select>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="nav-border-justified-messages" role="tabpanel">
                        <h6>Bantuan</h6>
                        <p class="mb-0">
                            <ol type="1">
                                <li>Informasi pada TAB Kredential wajib diisi semua. Karena digunakan untuk masuk kedalam sistem SiFo Esemkarinam</li>
                                <li>Informasi pada TAB Profil tidak wajib diisi semua. Isi sesuai dengan informasi yang dapat anda berikan. Informasi profile digunakan untuk identitas pada LOG transaksi pembayaran</li>
                            </ol>  
                        </p>
                    </div>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <input type="hidden" readonly id="user_id">
                <button type="button" id="simpan_informasi_murid" class="btn btn-success-ids "> Simpan Informasi Murid </button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal zoomIn modal-lg" tabindex="-1" id="modal_konfimasi_upload" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Impor Data Murid <span id="title_name_file"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="alert alert-danger border-0 rounded-top rounded-0 m-0 d-flex align-items-center" role="alert">
                            <i data-feather="alert-triangle" class="text-warning me-2 icon-sm"></i>
                            <div class="flex-grow-1 text-truncate">Format yang diizinkan <b>.xls, .xlsx</b>. Selain itu kami tolak</div>
                            <div class="flex-shrink-0">
                                <a href="javascript:void(0)" class="text-reset text-decoration-underline"><b>Unduh Format Impor Data</b></a>
                            </div>
                        </div>
                        <div class="row align-items-end">
                            <div class="col-sm-8">
                                <div class="p-3">
                                    <p class="fs-16 lh-base">Hai.. <span id="username_detail" class="fw-semibold"></span> kamu mau import data</p>
                                    <p class="fs-16 lh-base">Informasi yang ditampilkan tidak selengkap buku induk. Karena ini adalah sub buku induk jadi informasi yang dilampirkan adalah inforamsi yang dibutuhkan oleh sistem</p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="px-3">
                                    <img src="{{asset('v1/assets/images/user-illustarator-2.png')}}" class="img-fluid" alt="">
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div>
                <div class="card">
                    <div class="card-body">
                        <div id="excel_data"><div style="text-align:center"><h1>Preview Berberapa Data Muncul Disini Untuk Konfirmasi</h1></div></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between gap-2">
                    <input type="file" id="file_murid" name="file_murid" accept=".xlsx, .xls" style="display: none">
                    <button id="unggah_preview" class="btn btn-outline-primary"><i class="ri-book-mark-line"></i> Unggah Data + Lihat</button>
                    <button id="proses_simpan" disabled class="btn btn-outline-success"><i class="ri-book-3-fill"></i> Proses Simpan</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal zoomIn modal-lg" tabindex="-1" id="modal_detail_murid" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Detail Informasi Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="alert alert-danger border-0 rounded-top rounded-0 m-0 d-flex align-items-center" role="alert">
                            <i data-feather="alert-triangle" class="text-warning me-2 icon-sm"></i>
                            <div class="flex-grow-1 text-truncate">Total berkas pembayaran dilakukan <b>1K</b>.</div>
                            <div class="flex-shrink-0">
                                <a href="javascript:void(0)" class="text-reset text-decoration-underline"><b>Cek Log Pembayaran</b></a>
                            </div>
                        </div>

                        <div class="row align-items-end">
                            <div class="col-sm-8">
                                <div class="p-3">
                                    <p class="fs-16 lh-base">Hai.. <span id="username_detail" class="fw-semibold"></span> ini detail informasi kamu</p>
                                    <p class="fs-16 lh-base">Jika terdapat kesalahan atas informasi yang tertera di bawah ini, silahkan hubungi pribadi informan guna memperbarui informasi yang benar dan akurat</p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="px-3">
                                    <img src="{{asset('v1/assets/images/user-illustarator-2.png')}}" class="img-fluid" alt="">
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div>
                <div class="card">
                    <div class="card-body">
                        <table id="tabel_detail_informasi" class="table table-borderless table-hover mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row">Nama Pengguna</th>
                                    <td class="text-muted"><span class="username"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Alamat Surel</th>
                                    <td class="text-muted"><span class="surel"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Kata Sandi</th>
                                    <td class="text-muted">Rahasia Dong [<a href="javascript:void(0)" onclick="return toastr.error('Mohon maaf kata sandi tidak bisa diperlihatkan. Privasi pribadi, hanya App Esemkarinam dan pihak terkait yang mengetahui', 'Persan Kesalahan');">Perlihatkan</span>]</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">NIS [Nomor Induk Siswa]</th>
                                    <td class="text-muted"><span class="nis"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">NISN [Nomor Induk Siswa Nasional]</th>
                                    <td class="text-muted"><span class="nisn"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Nama Lengkap</th>
                                    <td class="text-muted"><span class="nama_lengkap"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Nama Panggilan</th>
                                    <td class="text-muted"><span class="nama_panggilan"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Kelas</th>
                                    <td class="text-muted"><span class="nama_kelas"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Tahun Ajaran</th>
                                    <td class="text-muted"><span class="keterangan_tahun_ajaran"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Alamat</th>
                                    <td class="text-muted"><span class="alamat"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Nomor Kontak</th>
                                    <td class="text-muted"><span class="nomor_telepon"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Nomor Kontak Orang Tua / Wali</th>
                                    <td class="text-muted"><span class="nomor_telepon_orang_tua"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Keringanan</th>
                                    <td class="text-muted"><span class="keringanan"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between gap-2">
                    <button id="hapus_pengguna" class="btn btn-outline-danger"><i class="ri-delete-bin-5-line"></i> Hapus Pengguna</button>
                    <button class="btn btn-outline-primary"><i class="ri-book-mark-line"></i> Non Aktifkan Pengguna</button>
                    <button class="btn btn-outline-success"><i class="ri-book-3-fill"></i> Buka Pembukuan</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- End Page-content -->
@endsection
@section('css_load')
@component('komponen.css.datatables')
@endcomponent
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('js_load')
@component('komponen.js.datatables')
@endcomponent
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script src="{{ asset('v1/assets/js/app/murid.js')}}"></script>
@endsection
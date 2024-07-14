@extends('paneladmin.templateadmin')
@section('konten_paneladmin')
<div class="page-content">
    <div class="container-fluid">
        @include('includes.breadcumb', ['breadcrumbs' => $breadcrumbs])
        <div class="alert alert-danger" role="alert">
            Ini Adalah <strong>Daftar Pengguna dan Kredential</strong> Pada Aplikasi <b>Esemkarinam</b>. Harap berhati hati ketika menambah, mengubah atau menghapus data!
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Daftar Seluruh Pengguna Aplikasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <input id="kotak_pencarian" type="text" class="form-control" placeholder="Ketikan informasi seperti surel, email">
                            <button id="btn_tambah_pengguna" class="btn btn-outline-primary">Tambah Pengguna</button>
                        </div>
                        <table id="daftar_pegawai" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal zoomIn modal-lg" tabindex="-1" id="modal_tambah_pegawai" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Penambahan Informasi Kredential dan Pegawai</h5>
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
                                <input required type="text" class="form-control" id="username" placeholder="Ketikan username / nama pengguna ex: sandiidriz">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="surel" class="form-label">Surel</label>
                            </div>
                            <div class="col-lg-9">
                                <input required type="email" class="form-control" id="surel" placeholder="Ketikan surel anda ex: ids@gmail.com">
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
                        <h6>Informasi Kelengkapan Profil Pegawai</h6>
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="nip" class="form-label">NIP [Nomor Induk Pegawai]</label>
                            </div>
                            <div class="col-lg-9">
                                <input required type="text" class="form-control" id="nip" placeholder="ex: 1990081720200410">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            </div>
                            <div class="col-lg-9">
                                <input required type="text" class="form-control" id="nama_lengkap" placeholder="ex: Ino Dream Studio">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 mt-2">
                                <label for="select2_jeniskelamin" class="form-label">Jenis Kelamain</label>
                            </div>
                            <div class="col-lg-9">
                                <select id="select2_jeniskelamin" class="form-control">
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                    <option value="Alien">Alien</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" id="tempat_lahir" placeholder="ex: Malang">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:-10px">
                            <div class="col-lg-3 mt-2">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" id="tanggal_lahir" placeholder="01-01-9999">
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
                                <label for="nomor_telepon" class="form-label">Nomor Telpon</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" id="nomor_telepon" placeholder="ex: 08x257xxx535">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="catatan_lain" class="form-label">Catatan Lain</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" id="catatan_lain" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="nav-border-justified-messages" role="tabpanel">
                        <h6>Bantuan</h6>
                        <p class="mb-0">
                            <ol type="1">
                                <li>Informasi pada TAB Kredential wajib diisi semua. Karena digunakan untuk masuk kedalam sistem Esemkarinam</li>
                                <li>Penambahan kredensial aplikasi hanya dapat dilakukan melalui aplikasi website pada menu ini. Karena alasan keamanan dan aplikasi bersifat internal</li>
                                <li>Informasi pada TAB Profil tidak wajib diisi semua. Isi sesuai dengan informasi yang dapat anda berikan. Informasi profile digunakan untuk identitas pada LOG pengarsipan dokumen</li>
                            </ol>  
                        </p>
                    </div>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <input type="hidden" readonly id="user_id">
                <button type="button" id="simpan_informasi_kredential" class="btn btn-success-ids "> Simpan Kredential dan Info Pengguna </button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal zoomIn modal-lg" tabindex="-1" id="modal_detail_pegawai" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Detail Informasi Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body p-0">
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
                                    <td class="text-muted">Rahasia Dong [<a href="javascript:void(0)" onclick="return toastr.error('Mohon maaf kata sandi tidak bisa diperlihatkan. Privasi pribadi, hanya Esemkarinam dan pihak terkait yang mengetahui', 'Persan Kesalahan');">Perlihatkan</span>]</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">NIP [Nomor Induk Pegawai]</th>
                                    <td class="text-muted"><span class="nip"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Nama Lengkap</th>
                                    <td class="text-muted"><span class="nama_lengkap"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">TTL</th>
                                    <td class="text-muted"><span class="tempat_lahir"></span>, <span class="tanggal_lahir"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Jenis Kelamin</th>
                                    <td class="text-muted"><span class="jenis_kelamin"></span></td>
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
                                    <th class="ps-0" scope="row">Catatan</th>
                                    <td class="text-muted"><span class="catatan_lain"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between gap-2">
                    <button id="hapus_pengguna" class="btn btn-outline-danger"><i class="ri-delete-bin-5-line"></i> Hapus Pengguna</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
@endsection
@section('css_load')
@component('komponen.css.datatables')
@endcomponent
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<style>#tabel_detail_informasi > :not(caption) > * > * {padding: 0 0 12px 0;}</style>
@endsection
@section('js_load')
@component('komponen.js.datatables')
@endcomponent
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('v1/assets/js/app/listemployed.js')}}"></script>
@endsection
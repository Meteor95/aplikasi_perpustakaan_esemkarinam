@extends('paneladmin.templateadmin')
@section('konten_paneladmin')
<div class="page-content">
    <div class="container-fluid">
        @include('includes.breadcumb', ['breadcrumbs' => $breadcrumbs])
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Daftar Seluruh Buku di Perpus Esemkarinam</h5>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <div class="col-md-12 mb-2" style="padding-right: 10px;">
                                <input id="kotak_pencarian" type="text" class="form-control" placeholder="Ketikan Informaisi Kode, Nama, Nomor Buku">
                            </div>
                            <div class="col-md-3 mb-2" style="padding-right: 10px;">
                                <select id="select_kategori" class="form-control"></select>
                            </div>
                            <div class="col-md-2 mb-2" style="padding-right: 10px;">
                                <select id="select_penerbit" class="form-control"></select>
                            </div>
                            <div class="col-md-3 mb-2" style="padding-right: 10px;">
                                <select id="select_pengarang" class="form-control"></select>
                            </div>
                            <div class="col-md-2 mb-2" style="padding-right: 10px;">
                                <select id="select_laci" class="form-control"></select>
                            </div>
                            <div class="col-md-2 mb-2" style="padding-right: 10px;">
                                <select id="select_rak" class="form-control"></select>
                            </div>
                            <div class="d-flex gap-2  float-end">
                                <button id="filter_tambah_buku" class="btn btn-outline-primary"><i class="ri-delete-bin-5-line"></i> Filter Informasi</button>
                                <button id="tambah_informasi_buku" class="btn btn-outline-success"><i class="ri-book-mark-line"></i> Tambah Informasi</button>
                            </div>
                        </div>
                        <table id="daftar_buku" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal zoomIn modal-lg" tabindex="-1" id="modal_tambah_buku" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Penambahan Informasi Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <video width="100%" id="preview"></video>
                <div class="row" id="camera-buttons"></div>
                <form id="form_pendaftaran_buku" class="needs-validation" novalidate>
                    <div class="row mb-1">
                        <div class="col-lg-3">
                            <label for="id_buku" class="form-label">ID Buku</label>
                        </div>
                        <div class="col-lg-9">
                            <input required type="text" class="form-control" id="id_buku" placeholder="ex: PHP_BUKU_090934233320">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-3">
                            <label for="nomor_buku" class="form-label">Nomor Buku</label>
                        </div>
                        <div class="col-lg-9">
                            <input required type="text" class="form-control" id="nomor_buku" placeholder="ex: 100-650-2024-55-99856">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-3">
                            <label for="nama_buku" class="form-label">Nama Buku</label>
                        </div>
                        <div class="col-lg-9">
                            <input required type="text" class="form-control" id="nama_buku" placeholder="ex: PHP Bahasa Pemograman Yang Dikucilkan Tapi Powerfull">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-3">
                            <label for="select_kategori_form" class="form-label">Kategori</label>
                        </div>
                        <div class="col-lg-9">
                            <select id="select_kategori_form" class="form-control"></select>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-3">
                            <label for="select_pengarang_form" class="form-label">Pengarang</label>
                        </div>
                        <div class="col-lg-9">
                            <select id="select_pengarang_form" class="form-control"></select>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-3">
                            <label for="select_rak_form" class="form-label">Rak</label>
                        </div>
                        <div class="col-lg-9">
                            <select id="select_rak_form" class="form-control"></select>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-3">
                            <label for="select_laci_form" class="form-label">Laci</label>
                        </div>
                        <div class="col-lg-9">
                            <select id="select_laci_form" class="form-control"></select>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-3">
                            <label for="select_penerbit_form" class="form-label">Penerbit</label>
                        </div>
                        <div class="col-lg-9">
                            <select id="select_penerbit_form" class="form-control"></select>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-3">
                            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" id="tahun_terbit" placeholder="">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-3">
                            <label for="stok_tersedia" class="form-label">Stok Tersedia</label>
                        </div>
                        <div class="col-lg-9">
                            <input required type="text" class="form-control" id="stok_tersedia" placeholder="">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-3">
                            <label for="status_buku" class="form-label">Status Buku</label>
                        </div>
                        <div class="col-lg-9">
                            <select class="form-control" id="status_buku">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" id="keterangan" placeholder="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <input type="hidden" readonly id="user_id">
                <button type="button" id="simpan_buku" class="btn btn-success-ids "> Simpan Informasi Buku </button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal zoomIn modal-lg" tabindex="-1" id="modal_detail_buku" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Detail Informasi Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row align-items-end">
                            <div class="col-sm-8">
                                <div class="p-3">
                                    <p class="fs-16 lh-base">Hai.. <span id="username_detail" class="fw-semibold"></span> ini detail informasi buku</p>
                                    <p class="fs-16 lh-base">Berikut adalah informasi lengkap mengenai buku yang anda cari. Silahkan isikan informasi yang akurat agar pembaca atau pengguna lainnya tidak kebingungan</p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="px-3">
                                    <div id="qrcode_buku" class="img-fluid" alt=""></div>
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
                                    <th class="ps-0" scope="row">ID Buku / Nomor Buku</th>
                                    <td class="text-muted"><span class="id_nomor_buku"></span> / <span class="nomor_buku"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Nama Buku</th>
                                    <td class="text-muted"><span class="nama_buku"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Pengarang</th>
                                    <td class="text-muted"><span class="pengarang"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Tahun Terbit</th>
                                    <td class="text-muted"><span class="tahun_terbit"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Kategori Buku</th>
                                    <td class="text-muted"><span class="kategori_buku"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Lokasi Rak</th>
                                    <td class="text-muted"><span class="rak"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Lokasi Laci</th>
                                    <td class="text-muted"><span class="laci"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Stok / Status</th>
                                    <td class="text-muted"><span class="stok"></span> Buku,<span class="status"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Keterangan Buku</th>
                                    <td class="text-muted"><span class="keterangan_buku"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between gap-2">
                    <!--<button id="hapus_pengguna" class="btn btn-outline-success">Log Arus Buku</button>-->
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
<style>#tabel_detail_informasi > :not(caption) > * > * {padding: 0 0 12px 0;}
.qrcode {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}

.qrcode canvas {
    margin: auto;
}
</style>
@endsection
@section('js_load')
@component('komponen.js.datatables')
@endcomponent
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script src="{{ asset('v1/assets/js/vendor/jquery_qrcode/dist/jquery-qrcode.js')}}"></script>
<script src="{{ asset('v1/assets/js/app/buku.js')}}"></script>
@endsection
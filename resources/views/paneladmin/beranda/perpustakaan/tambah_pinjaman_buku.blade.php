@extends('paneladmin.templateadmin')
@section('konten_paneladmin')
<div class="page-content">
    <div class="container-fluid">
        @include('includes.breadcumb', ['breadcrumbs' => $breadcrumbs])
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Daftar Peminjam Di Perpustakaan</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <button id="btn_aktifkan_camera" class="w-100 btn btn-outline-primary"><i class="ri-qr-code-fill"></i> Scan Kartu Anggota</button>
                        </div>
                        <br>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="nomor_trx" class="form-label">Nomor Transaksi</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" id="nomor_trx" placeholder="Dibuatkan Sistem Jika Field Ini Dikosongkan">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="id_member" class="form-label">NIS, NISN atau ID Anggota Perpustakaan</label>
                            </div>
                            <div class="col-lg-9">
                                <input required type="text" class="form-control" id="id_member" placeholder="Masukan ID Member Perpustakaan">
                                <div>NIS Anggota : <span class="form-label" id="list_nis_anggota"></span><br>Nama Anggota : 
                                <span class="form-label" id="list_nama_anggota"></span></div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="tanggal_trx" class="form-label">Tanggal Peminjam</label>
                            </div>
                            <div class="col-lg-9">
                                <input required type="text" class="form-control" id="tanggal_trx" placeholder="Tentukan Tanggal Transaksi">
                            </div>
                        </div>
                        <div class="container mt-1 mb-1"><div class="row"><div class="col"><div class="line-with-text">Daftar Keranjang Peminjaman Buku</div></div></div></div>
                        <div class="col-lg-12 mb-1">
                            <div class="input-group">
                                <input type="text" class="form-control" id="kode_buku" placeholder="Masukan Kode, Nomor, Nama Buku yang akan di pinjam">
                                <button id="tambah_ke_keranjang" class="btn btn-outline-primary"><i class="ri-file-add-line align-middle"></i> Keranjang</button>
                                <button id="scan_qr_buku" class="btn btn-outline-primary"><i class="ri-qr-scan-line align-middle"></i> Scan QR</button>
                            </div>
                        </div>
                        <table id="tabel_peminjaman" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                            <th>Kode Buku</th>
                            <th>Informasi Buku</th>
                            <th>Kategori</th>
                            <th>Total Pinjam</th>
                            <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        </table>
                        <div class="d-flex gap-2">
                            <button id="btn_simpan_peminjaman_buku" class="w-100 btn btn-outline-primary"><i class="ri-database-line"></i> Simpan Informasi Peminjaman Buku</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal zoomIn modal-lg" tabindex="-1" id="scan_qrcode" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Silahkan SCAN QRCode Member / Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row align-items-end">
                            <div class="col-sm-8">
                                <div class="p-3">
                                    <p class="fs-16 lh-base">Hai.. <span id="username_detail" class="fw-semibold"></span> Silahkan Scan QRCode Anda</p>
                                    <p class="fs-16 lh-base">Silahkan scan qrcode untuk memfilter menyaring informasi yang diperlukan baik dari segi QRCode buku atau member</p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="px-3">
                                    <img src="{{asset('v1/assets/images/user-illustarator-2.png')}}" class="img-fluid" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div id="reader" style="width: 100%"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between gap-2">
                    <button id="tutup_kamera" class="btn btn-outline-success">Tutup Kamera</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
@endsection
@section('css_load')
@component('komponen.css.datatables')
@endcomponent
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<style>#tabel_detail_informasi > :not(caption) > * > * {padding: 0 0 12px 0;}
.qrcode-container {
    display: flex;
    justify-content: center; 
    align-items: center;
    width: 100%;
    height: 100%;
}
.qrcode-container canvas {
    display: block;
    margin: auto;
    width: 100%; 
    height: 100%;
}
.line-with-text {
    display: flex;
    align-items: center;
    text-align: center;
}
.line-with-text::before,
.line-with-text::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid #000;
}
.line-with-text:not(:empty)::before {
    margin-right: .25em;
}
.line-with-text:not(:empty)::after {
    margin-left: .25em;
}
</style>
@endsection
@section('js_load')
@component('komponen.js.datatables')
@endcomponent
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="{{ asset('v1/assets/js/vendor/jquery_qrcode/dist/jquery-qrcode.js')}}"></script>
<script src="{{ asset('v1/assets/js/app/tambah_buku_pinjaman.js')}}"></script>
@endsection
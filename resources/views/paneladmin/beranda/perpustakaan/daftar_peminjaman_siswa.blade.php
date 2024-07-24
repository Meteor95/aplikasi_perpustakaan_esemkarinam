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
                        <div class="input-group">
                            <div class="input-group mb-2">
                                <input id="kotak_pencarian" type="text" class="form-control" placeholder="Ketikan / Scan Informasi QRCode, Nama, ID Member, Nama Buku, Kode Buku">
                                <button id="pencarian_data" class="btn btn-outline-primary"><i class="ri-search-line"></i> Filter Informasi </button>
                            </div>
                        </div>
                        <table id="daftar_peminjaman" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%"></table>
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
<div class="modal zoomIn modal-lg" tabindex="-1" id="informasi_detail_peminjaman" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="judulinformasidetail">Informasi Detail Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="alert alert-danger border-0 rounded-top rounded-0 m-0 d-flex align-items-center" role="alert">
                            <i data-feather="alert-triangle" class="text-warning me-2 icon-sm"></i>
                            <div class="flex-grow-1 text-truncate">Total Buku Yang Dipinjam <b><span id="totaljenisbuku"></span></b> Jenis Buku.</div>
                            <div class="flex-shrink-0">
                                <!--<a href="javascript:void(0)" class="text-reset text-decoration-underline"><b>Hapus </b></a>-->
                            </div>
                        </div>

                        <div class="row align-items-end">
                            <div class="col-sm-8">
                                <div class="p-3">
                                    <p class="fs-16 lh-base">Hai.. <span id="username_detail" class="fw-semibold"></span> ini detail pinjaman siswa</p>
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
                                    <th class="ps-0" scope="row">NIS Siswa</th>
                                    <td class="text-muted"><span id="id_siswa"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Nama Siswa</th>
                                    <td class="text-muted"><span id="nama_siswa"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Kontak</th>
                                    <td class="text-muted"><span id="nomor_telp"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Kelas Siswa</th>
                                    <td class="text-muted"><span id="kelas"></span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Tahun Ajaran</th>
                                    <td class="text-muted"><span id="tahun_ajaran"></span></td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="tabeldetail" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Kode Buku</th>
                                    <th>Nama Buku</th>
                                    <th>Dipinjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between gap-2">
                    <!--<button id="hapus_pengguna" class="btn btn-outline-danger"><i class="ri-delete-bin-5-line"></i> Hapus Pengguna</button>
                    <button class="btn btn-outline-primary"><i class="ri-book-mark-line"></i> Non Aktifkan Pengguna</button>
                    <button class="btn btn-outline-success"><i class="ri-book-3-fill"></i> Buka Pemberkasan</button>-->
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
<style>
#tabel_detail_informasi > :not(caption) > * > * {padding: 0 0 12px 0;}
</style>
@endsection
@section('js_load')
@component('komponen.js.datatables')
@endcomponent
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="{{ asset('v1/assets/js/vendor/jquery_qrcode/dist/jquery-qrcode.js')}}"></script>
<script src="{{ asset('v1/assets/js/app/buku_pinjaman_siswa.js')}}"></script>
<script>
</script>
@endsection
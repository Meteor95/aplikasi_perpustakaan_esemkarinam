@extends('paneladmin.templateadmin')
@section('konten_paneladmin')
<div class="page-content">
    <div class="container-fluid">
        @include('includes.breadcumb', ['breadcrumbs' => $breadcrumbs])
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Daftar Pengembalian Di Perpustakaan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-lg-3 mt-2">
                                <label for="id_member" class="form-label">NIS Anggota Perpustakaan</label>
                            </div>
                            <div class="col-lg-9">
                                <input required type="text" class="form-control" id="id_member" placeholder="Masukan ID Member Perpustakaan">
                                <div>
                                NIS Anggota : <span class="form-label" id="list_nis_anggota"></span><br>
                                Nama Anggota : <span class="form-label" id="list_nama_anggota"></span><br>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 mt-2">
                                <label for="tanggal_trx" class="form-label">Tanggal Pengembalian</label>
                            </div>
                            <div class="col-lg-9">
                                <input required type="text" class="form-control" id="tanggal_trx" placeholder="Tentukan Tanggal Transaksi">
                            </div>
                        </div>
                        <div class="container mt-1 mb-1"><div class="row"><div class="col"><div class="line-with-text">Daftar Buku Yang Dipinjam</div></div></div></div>
                        <ul class="nav nav-tabs nav-justified mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#base-justified-home" role="tab" aria-selected="false">
                                    Keranjang
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#base-justified-messages" role="tab" aria-selected="false">
                                    Daftar Buku Dipinjam
                                </a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content text-muted">
                            <div class="tab-pane active" id="base-justified-home" role="tabpanel">
                                <div class="col-lg-12 mb-1">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="kode_buku" placeholder="Masukan Kode, Nomor, Nama Buku yang akan dikembalikan">
                                        <button id="tambah_ke_keranjang" class="btn btn-outline-primary"><i class="ri-file-add-line align-middle"></i>Tambah Ke Keranjang</button>
                                    </div>
                                </div>
                                <table id="tabel_keranjang_pengembalian" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Kode Buku</th>
                                        <th>Informasi Buku</th>
                                        <th>Waktu Transaksi</th>
                                        <th>Lama Kembali</th>
                                        <th>Besaran Denda</th>
                                        <th>Dikembalikan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                </table>
                                <div class="d-flex gap-2">
                                    <button id="btn_simpan_pengembalian_buku" class="w-100 btn btn-outline-primary"><i class="ri-database-line"></i> Simpan Informasi Pengembalian Buku</button>
                                </div>
                            </div>
                            <div class="tab-pane" id="base-justified-messages" role="tabpanel">
                                <table id="tabel_pengembalian" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                    <th>Nomor Transaksi</th>
                                    <th>Peminjaman Buku</th>
                                    <th>Waktu Transaksi</th>
                                    <th>Besaran Denda</th>
                                    <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('css_load')
@component('komponen.css.datatables')
@endcomponent
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<style>#tabel_detail_informasi > :not(caption) > * > * {padding: 0 0 12px 0;}
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
<script src="{{ asset('v1/assets/js/app/tambah_buku_pengembalian.js')}}"></script>
@endsection
@extends('paneladmin.templateadmin')
@section('konten_paneladmin')
<div class="page-content">
    <div class="container-fluid">
        @include('includes.breadcumb', ['breadcrumbs' => $breadcrumbs])
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Pengaturan Sistem Perpustakaan</h5>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-justified nav-border-top nav-border-top-success mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#penerbit_buku" role="tab" aria-selected="false">
                                    <i class="ri-login-box-line align-middle me-1"></i> Penerbit
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#pengarang_buku" role="tab" aria-selected="false">
                                    <i class="ri-user-line me-1 align-middle"></i> Pengarang Buku
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#kategori_buku" role="tab" aria-selected="false">
                                    <i class="ri-user-line me-1 align-middle"></i> Kategori Buku
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#laci_buku" role="tab" aria-selected="false">
                                    <i class="ri-user-line me-1 align-middle"></i> Laci Buku
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#rak_buku" role="tab" aria-selected="false">
                                    <i class="ri-user-line me-1 align-middle"></i> Rak Buku
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#bantuan" role="tab" aria-selected="false">
                                    <i class="ri-lifebuoy-line align-middle me-1"></i>Bantuan
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content text-muted">
                            <div class="tab-pane active" id="penerbit_buku" role="tabpanel">
                                <h6>Informasi Penerbit Buku</h6>
                                <form id="form_penerbit" class="needs-validation" novalidate>
                                <div class="row mb-2">
                                    <div class="col-md-6 mb-2"><input required id="penerbit_nama" type="text" class="form-control" placeholder="Ketikan PENERBIT buku"></div>
                                    <div class="col-md-6 mb-2"><input required id="penerbit_keterangan" type="text" class="form-control" placeholder="Ketikan keterangan PENERBIT buku"></div>
                                    <div class="col-md-12">
                                        <div class="d-grid gap-2">
                                            <button id="btn_tambah_penerbit" class="btn_simpan_atribut btn btn-outline-primary">Simpan Penerbit</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="container mt-1 mb-1"><div class="row"><div class="col"><div class="line-with-text">Penerbit Terdaftar di App</div></div></div></div>
                                </form>
                                <input id="kotak_pencarian_penerbit" type="text" class="form-control" placeholder="Ketikan kode atau keterangan PENERBIT">
                                <table id="tabel_atribut_penerbit" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%"></table>
                            </div>
                            <div class="tab-pane" id="pengarang_buku" role="tabpanel">
                                <h6>Informasi Pengarang Buku</h6>
                                <form id="form_pengarang" class="needs-validation" novalidate>
                                <div class="row mb-2">
                                    <div class="col-md-6 mb-2"><input required id="pengarang_nama" type="text" class="form-control" placeholder="Ketikan PENGARANG buku"></div>
                                    <div class="col-md-6 mb-2"><input required id="pengarang_keterangan" type="text" class="form-control" placeholder="Ketikan keterangan PENGARANG buku"></div>
                                    <div class="col-md-12">
                                        <div class="d-grid gap-2">
                                            <button id="btn_tambah_pengarang" class="btn_simpan_atribut btn btn-outline-primary">Simpan Pengarang</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="container mt-1 mb-1"><div class="row"><div class="col"><div class="line-with-text">Pengarang Terdaftar di App</div></div></div></div>
                                </form>
                                <input id="kotak_pencarian_pengarang" type="text" class="form-control" placeholder="Ketikan kode atau keterangan PENGARANGAN">
                                <table id="tabel_atribut_pengarang" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%"></table>
                            </div>
                            <div class="tab-pane" id="kategori_buku" role="tabpanel">
                                <h6>Informasi Kategori Buku</h6>
                                <form id="form_kategori" class="needs-validation" novalidate>
                                <div class="row mb-2">
                                    <div class="col-md-6 mb-2"><input id="kategori_nama" type="text" class="form-control" placeholder="Ketikan KATEGORI buku"></div>
                                    <div class="col-md-6 mb-2"><input id="kategori_keterangan" type="text" class="form-control" placeholder="Ketikan keterangan KATEGORI buku"></div>
                                    <div class="col-md-12">
                                        <div class="d-grid gap-2">
                                            <button id="btn_tambah_kategori" class="btn_simpan_atribut btn btn-outline-primary">Simpan Kategori Buku</button>
                                        </div>
                                    </div>
                                </div>
                                </form>
                                <div class="container mt-1 mb-1"><div class="row"><div class="col"><div class="line-with-text">Kategori Terdaftar di App</div></div></div></div>
                                <input id="kotak_pencarian_kategori" type="text" class="form-control" placeholder="Masukkan kode dan nama penerbit">
                                <table id="tabel_atribut_kategori" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%"></table>
                            </div>
                            <div class="tab-pane" id="laci_buku" role="tabpanel">
                                <h6>Informasi Letal Laci Buku</h6>
                                <form id="form_laci" class="needs-validation" novalidate>
                                <div class="row mb-2">
                                    <div class="col-md-6 mb-2"><input id="laci_nama" type="text" class="form-control" placeholder="Ketikan nama LACI buku"></div>
                                    <div class="col-md-6 mb-2"><input id="laci_keterangan" type="text" class="form-control" placeholder="Ketikan keterangan LACI buku"></div>
                                    <div class="col-md-12">
                                        <div class="d-grid gap-2">
                                            <button id="btn_tambah_laci" class="btn_simpan_atribut btn btn-outline-primary">Simpan Nama Laci Buku</button>
                                            </div>
                                    </div>
                                </div>
                                </form>
                                <div class="container mt-1 mb-1"><div class="row"><div class="col"><div class="line-with-text">Penerbit Terdaftar di App</div></div></div></div>
                                <input id="kotak_pencarian_laci" type="text" class="form-control" placeholder="Ketikan kode atau keterangan LACI buku">
                                <table id="tabel_atribut_laci" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%"></table>
                            </div>
                            <div class="tab-pane" id="rak_buku" role="tabpanel">
                                <h6>Informasi Rak Buku</h6>
                                <form id="form_rak" class="needs-validation" novalidate>
                                <div class="row mb-2">
                                    <div class="col-md-6 mb-2"><input id="rak_nama" type="text" class="form-control" placeholder="Ketikan nama RAK buku"></div>
                                    <div class="col-md-6 mb-2"><input id="rak_keterangan" type="text" class="form-control" placeholder="Ketikan keterangan RAK buku"></div>
                                    <div class="col-md-12">
                                        <div class="d-grid gap-2">
                                            <button id="btn_tambah_rak" class="btn_simpan_atribut btn btn-outline-primary">Simpan Rak Buku</button>
                                            </div>
                                    </div>
                                </div>
                                </form>
                                <div class="container mt-1 mb-1"><div class="row"><div class="col"><div class="line-with-text">Penerbit Terdaftar di App</div></div></div></div>
                                <input id="kotak_pencarian_rak" type="text" class="form-control" placeholder="Ketikan kode atau keterangan RAK buku">
                                <table id="tabel_atribut_rak" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%"></table>
                            </div>
                            <div class="tab-pane" id="bantuan" role="tabpanel">
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
<style>
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
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('v1/assets/js/app/atribut.js')}}"></script>
@endsection
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
                        <h5 class="card-title">Daftar Kelas</h5>
                    </div>
                    <div class="card-body">
                        <form id="formulir_identitas_kelas">
                        <div class="row">
                            <div class="col-lg-2 mb-2">
                                <label for="kode_kelas"> Kode Kelas </label>
                                <input required id="kode_kelas" type="text" class="form-control" placeholder="Kode Kelas. Ex: 12AK">
                            </div>
                            <div class="col-lg-4 mb-2">
                                <label for="nama_kelas"> Nama Kelas </label>
                                <input required id="nama_kelas" type="text" class="form-control" placeholder="Nama Kelas">
                            </div>
                            <div class="col-lg-4 mb-2">
                                <label for="total_biaya"> Total Biaya Per Bulan </label>
                                <input required id="total_biaya" type="text" value="0" class="form-control" placeholder="Total Biaya Per Bulan">
                            </div>
                            <div class="col-lg-2 mb-2">
                                <label for="jumlah_bulan"> Jumlah Bulan</label>
                                <div class="input-group">
                                    <input required id="jumlah_bulan" type="text" value="0" class="form-control" placeholder="Jumlah Bulan">
                                    <button id="btn_tambah_kelas" class="btn btn-outline-primary">Simpan</button>
                                </div>
                            </div>
                        </div>
                        </form>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12 mb-2">
                            <input type="text" class="form-control" id="kotak_pencarian" placeholder="Ketikan nama kelas">
                            <table id="daftar_kelas" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%"></table>
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
<script>
let total_biaya_,jumlah_bulan_;
total_biaya_ = new AutoNumeric("#total_biaya", {decimalCharacter : ',',digitGroupSeparator : '.',})
jumlah_bulan_ = new AutoNumeric("#jumlah_bulan", {decimalCharacter : ',',digitGroupSeparator : '.',})
</script>
<script src="{{ asset('v1/assets/js/app/kelas.js')}}"></script>
@endsection
@extends('paneladmin.templateadmin')
@section('konten_paneladmin')
<div class="page-content">
    <div class="container-fluid">
        @include('includes.breadcumb', ['breadcrumbs' => $breadcrumbs])
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Informasi Tahun Ajaran</h5>
                    </div>
                    <div class="card-body">
                        <form id="form_tahun_ajaran">
                        <div class="row">
                            <div class="col-lg-6">
                                <input required id="kode_tahun_ajaran" type="text" class="form-control" placeholder="Masukan TA. Ex : 2024/2025">
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <input required id="keterangan_tahun_ajaran" type="text" class="form-control" placeholder="Keterangan Tahun Ajaran">
                                    <button id="btn_simpan_tahunajaran" class="btn btn-outline-primary">Simpan Tahun Ajaran</button>
                                </div>
                            </div>
                        </div>
                        </form><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="text" class="form-control" id="kotak_pencarian" placeholder="Ketikan tahun ajaran. Ex: 2023/2024">
                                <table id="daftar_tahun_ajaran" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%"></table>
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
@endsection
@section('js_load')
@component('komponen.js.datatables')
@endcomponent
<script src="{{ asset('v1/assets/js/app/tahunajaran.js')}}"></script>
@endsection
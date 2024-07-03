@extends('paneladmin.templateadmin')
@section('konten_paneladmin')
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">InoDreamStudio</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                            <li class="breadcrumb-item active">Starter</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mt-sm-5 pt-4 mb-4">
                    <div class="mb-sm-5 pb-sm-4 pb-5">
                        <img src="{{asset('v1/assets/images/comingsoon.png')}}" alt="" height="120" class="move-animation">
                    </div>
                    <div class="mb-1">
                        <h1 class="display-2 coming-soon-text" style="color:red">SEGERA HADIR</h1>
                    </div>
                    <div>
                        <div class="mt-1">
                            <h4>Kami Sedang Berkerja Keras Atas Fitur Ini</h4>
                            <p class="text-muted">Kami akan memberikan informasi kepada anda jika fitur ini telah selesai kami kembangkan.<br>Salam Hangat,<br> Tim IT App Esemkarinam 😊</p>
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
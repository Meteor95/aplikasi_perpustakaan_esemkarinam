<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController,StudentsController,PegawaiController,PerpustakaanController};

Route::get('/', function(){return ResponseHelper::error(401);})->name('login');
Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('pintumasuk', [AuthController::class,"login"]);
        Route::post('keluar', [AuthController::class,"logout"]);
        Route::get('lupapassword', [AuthController::class,"forgotpassword"]);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('cektoken', [AuthController::class,"tokencheck"]);
            Route::post('pendaftaran_web', [AuthController::class,"register"]);
            Route::post('hapuspegawai', [AuthController::class,"delete"]);
            Route::post('tambahhakakses', [AuthController::class,"addpermissionuser"]);
            Route::get('daftarhakakses', [AuthController::class,"listpermissionuser"]);
            Route::delete('hapushakakses', [AuthController::class,"deletepermissionuser"]);
        });
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('perpustakaan')->group(function () {
            Route::post('atribut', [PerpustakaanController::class, "attribut"]);
            Route::get('bacaatribut', [PerpustakaanController::class,"loadatribut"]);
            Route::get('daftar_buku', [PerpustakaanController::class,"listofbook"]); 
            Route::post('tambah_buku', [PerpustakaanController::class,"insertofbook"]); 
            Route::post('proses_pengembalian', [PerpustakaanController::class,"proses_pengembalian"]);
            Route::post('proses_peminjaman', [PerpustakaanController::class,"save_rent_of_books"]);
            Route::get('daftar_peminjam', [PerpustakaanController::class,"listloanofbook"]); 
            Route::get('detail_pinjaman', [PerpustakaanController::class,"detailrentofbook"]); 
            Route::get('daftar_pengembalian', [PerpustakaanController::class,"listloanofbookreturn"]); 
            Route::get('hapus_peminjaman', [PerpustakaanController::class,"hapus_peminjaman"]); 
            Route::get('ambilpeminjaman', [PerpustakaanController::class,"ambilpeminjaman"]);
            Route::post('verifikasipeminjaman', [PerpustakaanController::class,"verifikasipeminjaman"]);
        });
        Route::prefix('pegawai')->group(function () {
            Route::post('pendaftaran', [PegawaiController::class, "addemployed"]);
            Route::get('daftar', [PegawaiController::class, "listemployed"]);
            Route::get('detailpegawai', [PegawaiController::class, "detailemployed"]);
            Route::match(['post', 'put'],'ubahpegawai', [PegawaiController::class, "editemployed"]);
        });
        Route::prefix('murid')->group(function () {
            Route::get('ajaxtabeltabelajaran', [StudentsController::class,"listtableacademicyears"]);
            Route::post('tahunajaran', [StudentsController::class,"academicyears"]);
            Route::delete('hapustahunajaran', [StudentsController::class,"deleteacademicyears"]);
            Route::get('ajaxtabelkelas', [StudentsController::class,"liststudentsclass"]);
            Route::post('kelas', [StudentsController::class,"studentsclass"]);
            Route::delete('hapuskelas', [StudentsController::class,"deletestudentsclass"]);
            Route::post('importmurid', [StudentsController::class,"studentsimports"]);
            Route::get('ajaxtabelmurid', [StudentsController::class,"studentslist"]);
            Route::post('kelolamurid', [StudentsController::class,"managestudents"]);
            Route::delete('hapusinformasimurid', [StudentsController::class,"deletestudents"]);
        });     
    });
});
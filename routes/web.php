<?php

use Illuminate\Support\Facades\{Route,Session};
use App\Http\Middleware\VerifyIsLogin;
use App\Http\Controllers\Web\{StudentsController,PegawaiController,PerpustakaanController};

Route::get('masuk_perpus', function (Request $req) {$data = [ 'tipe_halaman' => 'login'];return view('login', ['data' => $data]);})->name('loginweb');
Route::get('generate-csrf-token', function () {$token = csrf_token();return response()->json(['csrf_token' => $token]);});
Route::get('keluar', [StudentsController::class,"web_logout"]);
Route::get('/', [StudentsController::class,"landingpage"]);
Route::middleware([VerifyIsLogin::class])->group(function () {
    Route::get('beranda', [StudentsController::class,"dashboard"])->name('beranda');
    Route::prefix('perpustakaan')->group(function () {
        Route::get('daftar_buku', [PerpustakaanController::class,"list_of_book"]);
        Route::get('atribut', [PerpustakaanController::class,"atribut"]);
        Route::get('pengaturan', [PerpustakaanController::class,"setting"]);
    });
    Route::prefix('pegawai')->group(function () {
        Route::get('daftarpengguna', [PegawaiController::class,"listemployed"]);
        Route::get('hakakses', [PegawaiController::class,"permissionaccess"])->name('hakakses');
    });
    Route::prefix('murid')->group(function () {
        Route::get('subbukuinduk', [StudentsController::class,"liststudents"]);
        Route::get('tahunajaran', [StudentsController::class,"academicyears"]);
        Route::get('kelas', [StudentsController::class,"studentrooms"]);
    });
});


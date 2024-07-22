<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cookie,Hash};

class StudentsController extends Controller
{
    private function buildData(Request $req, $menu_utama_aktif, $sub_menu_utama_aktif){
        $userInfo = $req->attributes->get('informasi_user');
        return [
            'tipe_halaman' => 'admin',
            'menu_utama_aktif' => $menu_utama_aktif,
            'sub_menu_utama_aktif' => $sub_menu_utama_aktif,
            'userInfo' => $userInfo,
        ];
    }
    public function landingpage(Request $req){
        $data = [ 'tipe_halaman' => 'landingpage'];
        return view('landingpage', ['data' => $data]);
    }
    public function dashboard(Request $req){
        $data = $this->buildData($req, 'dashboard','');
        return view('paneladmin.beranda.beranda_konten', ['data' => $data]);
    }
    public function liststudents(Request $req){
        $data = $this->buildData($req, 'daftarmurid','sub_buku_induk');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/murid/subbukuinduk', 'label' => 'Daftar Murid'],
            ['label' => 'Tabel Murid']
        ];
        return view('paneladmin.beranda.murid.beranda_konten', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
    public function academicyears(Request $req){
        $data = $this->buildData($req, 'daftarmurid','tahun_ajaran');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/murid/tahunajaran', 'label' => 'Tahun Ajaran'],
            ['label' => 'Tabel TA']
        ];
        return view('paneladmin.beranda.murid.tahunajaran_konten', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
    public function studentrooms(Request $req){
        $data = $this->buildData($req, 'daftarmurid','kelas');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/murid/subbukuinduk', 'label' => 'Daftar Kelas'],
            ['label' => 'Tabel Kelas']
        ];
        return view('paneladmin.beranda.murid.kelas_konten', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
    public function siswa_pinjam(Request $req){
        $data = $this->buildData($req, 'daftarmurid','kelas');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/murid/subbukuinduk', 'label' => 'Daftar Kelas'],
            ['label' => 'Tabel Kelas']
        ];
        return view('paneladmin.beranda.murid.kelas_konten', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
    public function web_logout(Request $req)
    {
        \Cookie::queue(Cookie::forget('CookieID'));
        \Cookie::queue(Cookie::forget('HashCookieUUID'));
        return redirect()->route('loginweb');
    }
}

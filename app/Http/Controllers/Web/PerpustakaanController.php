<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PerpustakaanController extends Controller
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
    public function list_of_book(Request $req)
    {
        $data = $this->buildData($req, 'master_perpustakaan','daftar_buku');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/perpustakaan/daftar_buku', 'label' => 'Daftar Buku'],
            ['label' => 'Tabel Buku']
        ];
        return view('paneladmin.beranda.perpustakaan.daftar_buku', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
    public function list_of_rent(Request $req)
    {
        $data = $this->buildData($req, 'master_peminjaman','transaksi_pinjam');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/perpustakaan/daftar_buku', 'label' => 'Tabel Pinjaman'],
        ];
        return view('paneladmin.beranda.perpustakaan.daftar_peminjaman', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
    public function list_of_return(Request $req)
    {
        $data = $this->buildData($req, 'master_peminjaman','transaksi_kembali');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/perpustakaan/daftar_buku', 'label' => 'Tabel Pengembalian'],
        ];
        return view('paneladmin.beranda.perpustakaan.daftar_pengembalian', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
    public function tambah_pinjaman(Request $req)
    {
        $data = $this->buildData($req, 'master_peminjaman','transaksi_pinjam');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/perpustakaan/daftar_buku', 'label' => 'Tambah Pinjaman'],
            ['label' => 'Form Pinjaman']
        ];
        return view('paneladmin.beranda.perpustakaan.tambah_pinjaman_buku', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
    public function tambah_pengembalian(Request $req)
    {
        $data = $this->buildData($req, 'master_peminjaman','transaksi_kembali');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/perpustakaan/daftar_peminjaman', 'label' => 'Tambah Pengembalian'],
            ['label' => 'Form Pengembalian']
        ];
        return view('paneladmin.beranda.perpustakaan.tambah_pengembalian_buku', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
    public function pinjam_buku(Request $req){
        $data = $this->buildData($req, 'master_peminjaman','siswa_pinjam_buku');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/murid/subbukuinduk', 'label' => 'Daftar Kelas'],
            ['label' => 'Tabel Kelas']
        ];
        return view('paneladmin.beranda.murid.siswa_pinjam_buku', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
    public function atribut(Request $req)
    {
        $data = $this->buildData($req, 'master_perpustakaan','atribut');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/perpustakaan/atribut', 'label' => 'Atribut'],
            ['label' => 'Kelola Atribut']
        ];
        return view('paneladmin.beranda.perpustakaan.atribut', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
    public function daftar_pinjam(Request $req)
    {
        $data = $this->buildData($req, 'master_peminjaman','siswa_pinjam_buku_daftar');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/perpustakaan/pengaturan', 'label' => 'Histori Peminjaman'],
            ['label' => 'Tabel Pengaturan']
        ];
        return view('paneladmin.beranda.perpustakaan.daftar_peminjaman_siswa', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
    public function setting(Request $req)
    {
        $data = $this->buildData($req, 'master_perpustakaan','pengaturan');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/perpustakaan/pengaturan', 'label' => 'Pengaturan'],
            ['label' => 'Kelola Pengaturan']
        ];
        return view('paneladmin.beranda.perpustakaan.pengaturan', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
}

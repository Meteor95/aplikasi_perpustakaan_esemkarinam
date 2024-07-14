<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'tms_transaksi_buku';
    protected $fillable = [
        'nomor_transkasi',
        'id_anggota',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'status',
    ];
    public static function getTotalBukuWithPeminjaman($req){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_transaksi_buku')
            ->join('tms_transaksi_buku_detail', 'tms_transaksi_buku_detail.id_transaksi', '=', 'tms_transaksi_buku.nomor_transkasi')
            ->join('users_siswa', 'users_siswa.user_id', '=', 'tms_transaksi_buku.id_anggota')
            ->join('tms_perpustakaan_buku', 'tms_perpustakaan_buku.id_buku', '=', 'tms_transaksi_buku_detail.id_buku')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('users_siswa.nama_lengkap', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users_siswa.nis', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users_siswa.nisn', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku.nama_buku', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_transaksi_buku.nomor_transkasi', 'like', '%' . $parameterpencarian . '%');
            })
            ->groupBy('tms_transaksi_buku.nomor_transkasi')
            ->get();
    }
    public static function getBukuWithPeminjaman($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_transaksi_buku')
            ->join('tms_transaksi_buku_detail', 'tms_transaksi_buku_detail.id_transaksi', '=', 'tms_transaksi_buku.nomor_transkasi')
            ->join('users_siswa', 'users_siswa.user_id', '=', 'tms_transaksi_buku.id_anggota')
            ->join('users_pegawai', 'users_pegawai.id_user', '=', 'tms_transaksi_buku.id_petugas')
            ->join('tms_perpustakaan_buku', 'tms_perpustakaan_buku.id_buku', '=', 'tms_transaksi_buku_detail.id_buku')
            ->select(
                'tms_transaksi_buku.*',
                'tms_transaksi_buku_detail.*',
                'users_siswa.*',
                'users_pegawai.nama_lengkap as nama_lengkap_pegawai',
                'tms_perpustakaan_buku.*',
                DB::raw('COUNT(tms_transaksi_buku_detail.id_buku) as totaljenisbuku_dipinjam'),
                DB::raw('SUM(tms_transaksi_buku_detail.qty_pinjam) as totalbuku_dipinjam'),
            )
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('users_siswa.nama_lengkap', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users_siswa.nis', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users_siswa.nisn', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku.nama_buku', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_transaksi_buku.nomor_transkasi', 'like', '%' . $parameterpencarian . '%');
            })
            ->take($perHalaman)
            ->skip($offset)
            ->groupBy('tms_transaksi_buku.nomor_transkasi')
            ->orderBy('tms_transaksi_buku.id_transaksi')
            ->get();
    }
    public static function getBukuWithPeminjamanDetail($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_transaksi_buku')
            ->join('tms_transaksi_buku_detail', 'tms_transaksi_buku_detail.id_transaksi', '=', 'tms_transaksi_buku.nomor_transkasi')
            ->join('users_siswa', 'users_siswa.user_id', '=', 'tms_transaksi_buku.id_anggota')
            ->join('users_pegawai', 'users_pegawai.id_user', '=', 'tms_transaksi_buku.id_petugas')
            ->join('tms_perpustakaan_buku', 'tms_perpustakaan_buku.id_buku', '=', 'tms_transaksi_buku_detail.id_buku')
            ->select(
                'tms_transaksi_buku.*',
                'tms_transaksi_buku_detail.*',
                'users_siswa.*',
                'users_pegawai.nama_lengkap as nama_lengkap_pegawai',
                'tms_perpustakaan_buku.*',
                DB::raw('COUNT(tms_transaksi_buku_detail.id_buku) as totaljenisbuku_dipinjam'),
                DB::raw('SUM(tms_transaksi_buku_detail.qty_pinjam) as totalbuku_dipinjam'),
            )
            ->where(function ($query) use ($parameterpencarian) {
                $query->Where('tms_transaksi_buku.nomor_transkasi', '=', $parameterpencarian);
            })
            ->take(1)
            ->get();
    }
}

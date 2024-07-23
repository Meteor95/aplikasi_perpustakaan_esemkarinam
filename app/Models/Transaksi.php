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
        'id_transaksi',
        'nomor_transkasi',
        'id_anggota',
        'id_petugas',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'keterangan',
        'status',
        'approval_status',
    ];
    public static function getTotalBukuWithPeminjaman($req){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_transaksi_buku')
            ->join('tms_transaksi_buku_detail', 'tms_transaksi_buku_detail.id_transaksi', '=', 'tms_transaksi_buku.nomor_transkasi')
            ->join('users_siswa', 'users_siswa.nis', '=', 'tms_transaksi_buku.id_anggota')
            ->join('users_pegawai', 'users_pegawai.id_user', '=', 'tms_transaksi_buku.id_petugas')
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
            ->join('users_siswa', 'users_siswa.nis', '=', 'tms_transaksi_buku.id_anggota')
            ->Join('users_pegawai', 'users_pegawai.id_user', '=', 'tms_transaksi_buku.id_petugas')
            ->join('tms_perpustakaan_buku', 'tms_perpustakaan_buku.id_buku', '=', 'tms_transaksi_buku_detail.id_buku')
            ->select(
                'tms_transaksi_buku.id_transaksi as id_transaksi_buku',
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
            ->orderBy('tms_transaksi_buku.tanggal_peminjaman', 'desc') 
            ->get();
    }
    public static function cekBukuSamaDipinjam($req,$idbuku){
        return DB::table('tms_transaksi_buku')
            ->join('tms_transaksi_buku_detail', 'tms_transaksi_buku_detail.id_transaksi', '=', 'tms_transaksi_buku.nomor_transkasi')
            ->join('tms_perpustakaan_buku', 'tms_perpustakaan_buku.id_buku', '=', 'tms_transaksi_buku_detail.id_buku')
            ->join('users_siswa', 'users_siswa.nis', '=', 'tms_transaksi_buku.id_anggota')
            ->select(
                'tms_perpustakaan_buku.*',
                DB::raw('COUNT(*) AS buku_sama'),
            )
            ->where('tms_transaksi_buku_detail.id_buku', '=', $idbuku)
            ->where('users_siswa.nis', '=', $req->id_member)
            ->get()->first();
    }
    public static function getBukuWithPeminjamanDetail($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_transaksi_buku')
            ->join('tms_transaksi_buku_detail', 'tms_transaksi_buku_detail.id_transaksi', '=', 'tms_transaksi_buku.nomor_transkasi')
            ->join('users_siswa', 'users_siswa.nis', '=', 'tms_transaksi_buku.id_anggota')
            ->join('users_pegawai', 'users_pegawai.id_user', '=', 'tms_transaksi_buku.id_petugas')
            ->join('tms_perpustakaan_buku', 'tms_perpustakaan_buku.id_buku', '=', 'tms_transaksi_buku_detail.id_buku')
            ->join('tms_ajaran_kelas', 'tms_ajaran_kelas.id', '=', 'users_siswa.id_kelas')
            ->join('tms_ajaran_tahun', 'tms_ajaran_tahun.id', '=', 'users_siswa.id_tahun_ajaran')
            ->select(
                'tms_transaksi_buku.*',
                'tms_transaksi_buku_detail.*',
                'users_siswa.*',
                'users_pegawai.nama_lengkap as nama_lengkap_pegawai',
                'tms_perpustakaan_buku.*',
                'tms_ajaran_kelas.*',
                'tms_ajaran_tahun.*',
            )
            ->where(function ($query) use ($parameterpencarian) {
                $query->Where('tms_transaksi_buku.nomor_transkasi', '=', $parameterpencarian)
                ->orWhere('tms_transaksi_buku.id_transaksi', '=', $parameterpencarian);
            })
            ->get();
    }
    public static function keranjangPengembalianBuku($req){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_transaksi_buku')
            ->join('tms_transaksi_buku_detail', 'tms_transaksi_buku_detail.id_transaksi', '=', 'tms_transaksi_buku.nomor_transkasi')
            ->join('users_siswa', 'users_siswa.nis', '=', 'tms_transaksi_buku.id_anggota')
            ->join('tms_perpustakaan_buku', 'tms_perpustakaan_buku.id_buku', '=', 'tms_transaksi_buku_detail.id_buku')
            ->select(
                'tms_transaksi_buku.*',
                'tms_transaksi_buku_detail.*',
                'users_siswa.*',
                'tms_perpustakaan_buku.*',
                DB::raw('DATEDIFF(CURDATE(), tms_transaksi_buku.tanggal_pengembalian) AS keterlambatan_hari')
            )
            ->where(function ($query) use ($parameterpencarian) {
                $query->where(function ($subQuery) use ($parameterpencarian) {
                    $subQuery->where('users_siswa.user_id', '=', $parameterpencarian)
                             ->orWhere('users_siswa.nis', '=', $parameterpencarian);
                })
                ->where('tms_transaksi_buku_detail.status', '<>', '0');
            })
            ->orderBy('tms_transaksi_buku.tanggal_peminjaman', 'desc') 
            ->get();
    }
    public static function keranjangPengembalianBukuCek($req,$idbuku){
        return DB::table('tms_transaksi_buku')
            ->join('tms_transaksi_buku_detail', 'tms_transaksi_buku_detail.id_transaksi', '=', 'tms_transaksi_buku.nomor_transkasi')
            ->join('users_siswa', 'users_siswa.nis', '=', 'tms_transaksi_buku.id_anggota')
            ->join('tms_perpustakaan_buku', 'tms_perpustakaan_buku.id_buku', '=', 'tms_transaksi_buku_detail.id_buku')
            ->select(
                'tms_transaksi_buku.*',
                'tms_transaksi_buku_detail.*',
                'users_siswa.*',
                'tms_perpustakaan_buku.*',
                DB::raw('DATEDIFF(CURDATE(), tms_transaksi_buku.tanggal_pengembalian) AS keterlambatan_hari')
            )
            ->where('tms_transaksi_buku_detail.id_buku', '=', $idbuku)
            ->where('users_siswa.nis', '=', $req->id_member)
            ->get()->first();
    }
}

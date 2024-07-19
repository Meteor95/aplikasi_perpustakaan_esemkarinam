<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Buku extends Model
{
    protected $table = 'tms_perpustakaan_buku';
    protected $fillable = [
        'id_buku',
        'nomor_buku',
        'nama_buku',
        'id_pengarang',
        'id_rak',
        'id_laci',
        'id_kategori',
        'tahun_terbit',
        'id_penerbit',
        'stok',
        'status',
        'keterangan',
        'id_penerima',
    ];
    public static function getTotalBuku($req){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_perpustakaan_buku')
            ->join('tms_perpustakaan_buku_kategori', 'tms_perpustakaan_buku_kategori.id', '=', 'tms_perpustakaan_buku.id_kategori')
            ->join('tms_perpustakaan_buku_laci', 'tms_perpustakaan_buku_laci.id', '=', 'tms_perpustakaan_buku.id_laci')
            ->join('tms_perpustakaan_buku_penerbit', 'tms_perpustakaan_buku_penerbit.id', '=', 'tms_perpustakaan_buku.id_penerbit')
            ->join('tms_perpustakaan_buku_pengarang', 'tms_perpustakaan_buku_pengarang.id', '=', 'tms_perpustakaan_buku.id_pengarang')
            ->join('tms_perpustakaan_buku_rak', 'tms_perpustakaan_buku_rak.id', '=', 'tms_perpustakaan_buku.id_rak')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('tms_perpustakaan_buku.id_buku', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku.nomor_buku', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku.nama_buku', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_kategori.keterangan', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_laci.keterangan', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_penerbit.keterangan', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_pengarang.keterangan', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_rak.keterangan', 'like', '%' . $parameterpencarian . '%');
            })->get();
    }
    public static function getBuku($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_perpustakaan_buku')
            ->join('tms_perpustakaan_buku_kategori', 'tms_perpustakaan_buku_kategori.id', '=', 'tms_perpustakaan_buku.id_kategori')
            ->join('tms_perpustakaan_buku_laci', 'tms_perpustakaan_buku_laci.id', '=', 'tms_perpustakaan_buku.id_laci')
            ->join('tms_perpustakaan_buku_penerbit', 'tms_perpustakaan_buku_penerbit.id', '=', 'tms_perpustakaan_buku.id_penerbit')
            ->join('tms_perpustakaan_buku_pengarang', 'tms_perpustakaan_buku_pengarang.id', '=', 'tms_perpustakaan_buku.id_pengarang')
            ->join('tms_perpustakaan_buku_rak', 'tms_perpustakaan_buku_rak.id', '=', 'tms_perpustakaan_buku.id_rak')
            ->select(
                'tms_perpustakaan_buku.*',
                'tms_perpustakaan_buku.id as id_buku_id',
                'tms_perpustakaan_buku_kategori.*',
                'tms_perpustakaan_buku_laci.*',
                'tms_perpustakaan_buku_penerbit.*',
                'tms_perpustakaan_buku_pengarang.*',
                'tms_perpustakaan_buku_rak.*',
            )->where(function ($query) use ($parameterpencarian) {
                $query->where('tms_perpustakaan_buku.id_buku', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku.nomor_buku', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku.nama_buku', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_kategori.keterangan', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_laci.keterangan', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_penerbit.keterangan', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_pengarang.keterangan', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_rak.keterangan', 'like', '%' . $parameterpencarian . '%');
            })
            ->take($perHalaman)
            ->skip($offset)
            ->orderBy('tms_perpustakaan_buku.nama_buku')
            ->get();
    }
    public static function detailOneBookDetail($id_buku){
        return DB::table('tms_perpustakaan_buku')
            ->join('tms_perpustakaan_buku_kategori', 'tms_perpustakaan_buku_kategori.id', '=', 'tms_perpustakaan_buku.id_kategori')
            ->join('tms_perpustakaan_buku_laci', 'tms_perpustakaan_buku_laci.id', '=', 'tms_perpustakaan_buku.id_laci')
            ->join('tms_perpustakaan_buku_penerbit', 'tms_perpustakaan_buku_penerbit.id', '=', 'tms_perpustakaan_buku.id_penerbit')
            ->join('tms_perpustakaan_buku_pengarang', 'tms_perpustakaan_buku_pengarang.id', '=', 'tms_perpustakaan_buku.id_pengarang')
            ->join('tms_perpustakaan_buku_rak', 'tms_perpustakaan_buku_rak.id', '=', 'tms_perpustakaan_buku.id_rak')
            ->select(
                'tms_perpustakaan_buku.*',
                'tms_perpustakaan_buku.id as id_buku_id',
                'tms_perpustakaan_buku_kategori.*',
                'tms_perpustakaan_buku_laci.*',
                'tms_perpustakaan_buku_penerbit.*',
                'tms_perpustakaan_buku_pengarang.*',
                'tms_perpustakaan_buku_rak.*',
                'tms_perpustakaan_buku_kategori.id as id_kategori',
                'tms_perpustakaan_buku_laci.id as id_laci',
                'tms_perpustakaan_buku_penerbit.id as id_penerbit',
                'tms_perpustakaan_buku_pengarang.id as id_pengarang',
                'tms_perpustakaan_buku_rak.id as id_rak',
            )->where('tms_perpustakaan_buku.id', '=', $id_buku)
            ->orWhere('tms_perpustakaan_buku.id_buku', '=', $id_buku)
            ->first();
    }
}

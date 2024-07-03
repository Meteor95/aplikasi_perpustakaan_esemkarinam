<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kategori extends Model
{
    protected $table = 'tms_perpustakaan_buku_kategori';
    protected $fillable = [
        'nama_kategori',
        'keterangan',
    ];
    public static function getTotalPengarang($req){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_perpustakaan_buku_kategori')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('tms_perpustakaan_buku_kategori.nama_kategori', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_kategori.keterangan', 'like', '%' . $parameterpencarian . '%');
            })->get();
    }
    public static function getPengarang($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_perpustakaan_buku_kategori')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('tms_perpustakaan_buku_kategori.nama_kategori', 'like', '%' . $parameterpencarian . '%')
                ->orWhere('tms_perpustakaan_buku_kategori.keterangan', 'like', '%' . $parameterpencarian . '%');
            })
            ->take($perHalaman)
            ->skip($offset)
            ->orderBy('tms_perpustakaan_buku_kategori.nama_kategori')
            ->get();
    }
}

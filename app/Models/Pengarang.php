<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Pengarang extends Model
{
    protected $table = 'tms_perpustakaan_buku_pengarang';
    protected $fillable = [
        'nama_pengarang',
        'keterangan',
    ];
    public static function getTotalPengarang($req){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_perpustakaan_buku_pengarang')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('tms_perpustakaan_buku_pengarang.nama_pengarang', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_pengarang.keterangan', 'like', '%' . $parameterpencarian . '%');
            })->get();
    }
    public static function getPengarang($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_perpustakaan_buku_pengarang')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('tms_perpustakaan_buku_pengarang.nama_pengarang', 'like', '%' . $parameterpencarian . '%')
                ->orWhere('tms_perpustakaan_buku_pengarang.keterangan', 'like', '%' . $parameterpencarian . '%');
            })
            ->take($perHalaman)
            ->skip($offset)
            ->orderBy('tms_perpustakaan_buku_pengarang.nama_pengarang')
            ->get();
    }
}

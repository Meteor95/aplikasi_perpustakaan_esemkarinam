<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Rak extends Model
{
    protected $table = 'tms_perpustakaan_buku_rak';
    protected $fillable = [
        'nama_rak',
        'keterangan',
    ];
    public static function getTotalPengarang($req){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_perpustakaan_buku_rak')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('tms_perpustakaan_buku_rak.nama_rak', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_rak.keterangan', 'like', '%' . $parameterpencarian . '%');
            })->get();
    }
    public static function getPengarang($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_perpustakaan_buku_rak')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('tms_perpustakaan_buku_rak.nama_rak', 'like', '%' . $parameterpencarian . '%')
                ->orWhere('tms_perpustakaan_buku_rak.keterangan', 'like', '%' . $parameterpencarian . '%');
            })
            ->take($perHalaman)
            ->skip($offset)
            ->orderBy('tms_perpustakaan_buku_rak.nama_rak')
            ->get();
    }
}

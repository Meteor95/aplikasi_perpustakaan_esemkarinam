<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Laci extends Model
{
    protected $table = 'tms_perpustakaan_buku_laci';
    protected $fillable = [
        'nama_laci',
        'keterangan',
    ];
    public static function getTotalPengarang($req){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_perpustakaan_buku_laci')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('tms_perpustakaan_buku_laci.nama_laci', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_laci.keterangan', 'like', '%' . $parameterpencarian . '%');
            })->get();
    }
    public static function getPengarang($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_perpustakaan_buku_laci')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('tms_perpustakaan_buku_laci.nama_laci', 'like', '%' . $parameterpencarian . '%')
                ->orWhere('tms_perpustakaan_buku_laci.keterangan', 'like', '%' . $parameterpencarian . '%');
            })
            ->take($perHalaman)
            ->skip($offset)
            ->orderBy('tms_perpustakaan_buku_laci.nama_laci')
            ->get();
    }
}

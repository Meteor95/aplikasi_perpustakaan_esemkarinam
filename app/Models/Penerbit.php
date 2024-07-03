<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Penerbit extends Model
{
    protected $table = 'tms_perpustakaan_buku_penerbit';
    protected $fillable = [
        'nama_penerbit',
        'keterangan',
    ];
    public static function getTotalPenerbit($req){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_perpustakaan_buku_penerbit')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('tms_perpustakaan_buku_penerbit.nama_penerbit', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('tms_perpustakaan_buku_penerbit.keterangan', 'like', '%' . $parameterpencarian . '%');
            })->get();
    }
    public static function getPenerbit($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_perpustakaan_buku_penerbit')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('tms_perpustakaan_buku_penerbit.nama_penerbit', 'like', '%' . $parameterpencarian . '%')
                ->orWhere('tms_perpustakaan_buku_penerbit.keterangan', 'like', '%' . $parameterpencarian . '%');
            })
            ->take($perHalaman)
            ->skip($offset)
            ->orderBy('tms_perpustakaan_buku_penerbit.nama_penerbit')
            ->get();
    }
}

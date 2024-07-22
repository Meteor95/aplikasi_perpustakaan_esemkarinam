<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKembali extends Model
{
    use HasFactory;
    protected $table = 'tms_transaksi_buku_kembali';
    protected $fillable = [
        'id',
        'id_transkasi_pengembalian',
        'id_petugas',
        'id_siswa',
        'tanggal_dikembalikan',
        'total_denda',
    ];
}

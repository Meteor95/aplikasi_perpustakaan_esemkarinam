<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKembaliDetail extends Model
{
    use HasFactory;
    protected $table = 'tms_transaksi_buku_kembali_detail';
    protected $fillable = [
        'id',
        'id_transaksi_pengembalian',
        'id_buku',
        'qty_dikembalikan',
        'keterangan',
        'denda',
        'terlambat',
    ];
}

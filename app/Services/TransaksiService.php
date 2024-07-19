<?php

namespace App\Services;

use Str;
use Carbon\Carbon;
use App\Models\{Transaksi,TransaksiDetail,Buku};
use Illuminate\Support\Facades\{DB,Hash};
use Illuminate\Support\Facades\Log;

class TransaksiService
{
    public function addTransaksiPeminjamanBuku($databuku,$datarequest)
    {
        return DB::transaction(function () use ($databuku,$datarequest) {
            $data = json_decode($databuku, true);
            $prefix = 'PB/BUKU/';
            $currentDate = Carbon::now()->format('Ymd');
            $timestamp = time();
            $nota = $prefix.$currentDate.$timestamp;
            $transaksiData[] = [
                'nomor_transkasi' => $nota,
                'id_petugas' => $datarequest['id_petugas'],
                'id_anggota' => $datarequest['id_member'],
                'tanggal_peminjaman' => Carbon::parse($datarequest['tanggal_transaksi']." ".date('H:i:s'))->format('Y-m-d H:i:s'),
                'tanggal_pengembalian' => Carbon::parse($datarequest['tanggal_transaksi']." ".date('H:i:s'))->addDays(7)->format('Y-m-d H:i:s'),
                'keterangan' => $datarequest['keterangan'],
                'status' => "dipinjam",
            ];
            foreach ($data as $item) {
                $informasibuku = Buku::where('id_buku', $item['id_buku'])->first();
                $transaksiDetailData[] = [
                    'id_transaksi' => $nota,
                    'id_buku' => $item['id_buku'],
                    'qty_pinjam' => $item['total_yang_dipinjam'],
                    'denda' => $informasibuku->denda,
                    'keterangan' => "",
                    'dikembalikan' => "0",
                    'status' => "1",
                ];
            }
            Transaksi::insert($transaksiData);
            TransaksiDetail::insert($transaksiDetailData);
        });
    }
    public function hapusTranskasiPeminjaman($req)
    {
        return DB::transaction(function() use ($req) {
            DB::table('tms_transaksi_buku')->where('nomor_transkasi', '=', $req->notapeminjaman)->delete();
            DB::table('tms_transaksi_buku_detail')->where('id_transaksi', '=', $req->notapeminjaman)->delete();
        });
    }
}

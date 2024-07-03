<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', length: 36)->index();
            $table->string('username')->unique()->index();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('id_hakakses');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('users_access_permission', function (Blueprint $table) {
            $table->id();
            $table->string('permission_name');
            $table->string('permission_json');
            $table->timestamps();
        });
        Schema::create('users_tmp_token', function (Blueprint $table) {
            $table->id();
            $table->string('id_user');
            $table->string('username');
            $table->string('email');
            $table->string('token');
        });
        Schema::create('users_tmp_token_email', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('token');
        });
        Schema::create('users_hakakses', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->text('nama_hak_akses');
            $table->text('hakakses_json');
            $table->timestamps();
        });
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
        Schema::create('tms_jenis_pembayaran', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->string('kode_jenis_pembayaran')->index();
            $table->string('nama_jenis_pembayaran');
            $table->timestamps();
        });
        Schema::create('tms_ajaran_tahun', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->string('kode_tahun_ajaran')->index();
            $table->string('keterangan_tahun_ajaran');
            $table->timestamps();
        });
        Schema::create('tms_ajaran_kelas', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->string('kode_kelas')->index();
            $table->string('nama_kelas');
            $table->double('total_biaya', 15, 2);
            $table->integer('jumlah_bulan');
            $table->timestamps();
        });
        Schema::create('users_siswa', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->index(); 
            $table->string('nisn')->index();
            $table->string('nis')->index();
            $table->string('id_kelas')->index();
            $table->string('id_tahun_ajaran')->index();
            $table->string('nama_lengkap');
            $table->string('nama_panggilan');
            $table->string('alamat');
            $table->string('nomor_kontak');
            $table->string('nomor_kontak_orang_tua');
            $table->double('keringanan', 15, 2);
            $table->timestamps();
        });
        Schema::create('users_pegawai', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->bigInteger('id_user')->index();
            $table->string('nip');
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan', 'Alien']);
            $table->text('alamat');
            $table->string('nomor_telepon');
            $table->text('catatan_lain');
            $table->timestamps();
        });
        Schema::create('tms_perpustakaan_buku', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->bigInteger('id_buku')->index();
            $table->string('nomor_buku');
            $table->string('nama_buku');
            $table->string('id_pengarang');
            $table->string('id_rak');
            $table->string('id_laci');
            $table->string('id_kategori');
            $table->string('tahun_terbit');
            $table->string('id_penerbit');
            $table->string('stok');
            $table->string('status');
            $table->string('keterangan');
            $table->string('id_penerima');
            $table->timestamps();
        });
        Schema::create('tms_perpustakaan_buku_pengarang', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->string('nama_pengarang');
            $table->string('keterangan');
            $table->timestamps();
        });
        Schema::create('tms_perpustakaan_buku_rak', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->string('nama_rak');
            $table->string('keterangan');
            $table->timestamps();
        });
        Schema::create('tms_perpustakaan_buku_laci', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->string('nama_laci');
            $table->string('keterangan');
            $table->timestamps();
        });
        Schema::create('tms_perpustakaan_buku_kategori', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->string('nama_kategori');
            $table->string('keterangan');
            $table->timestamps();
        });
        Schema::create('tms_perpustakaan_buku_penerbit', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->string('nama_penerbit');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('users_access_permission');
        Schema::dropIfExists('users_tmp_token');
        Schema::dropIfExists('users_tmp_token_email');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('tms_jenis_pembayaran');
        Schema::dropIfExists('users_siswa');
        Schema::dropIfExists('users_pegawai');
        Schema::dropIfExists('tms_perpustakaan_buku_pengarang');
        Schema::dropIfExists('tms_perpustakaan_buku_rak');
        Schema::dropIfExists('tms_perpustakaan_buku_laci');
        Schema::dropIfExists('tms_perpustakaan_buku_kategori');
        Schema::dropIfExists('tms_perpustakaan_buku_penerbit');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            // Tambahkan kolom metode_ttd (digital/manual)
            $table->string('metode_ttd')->nullable()->after('status');
            
            // Sekalian tambahkan kolom nomor_surat jika belum ada (opsional, tapi biasanya dibutuhkan)
            // $table->string('nomor_surat')->nullable()->after('metode_ttd');
        });
    }

    public function down()
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->dropColumn('metode_ttd');
        });
    }
};

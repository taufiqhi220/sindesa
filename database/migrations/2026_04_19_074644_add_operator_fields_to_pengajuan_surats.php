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
            // nomor_surat sudah ada di migration create, jadi tidak perlu ditambahkan lagi
            if (!Schema::hasColumn('pengajuan_surats', 'nomor_surat')) {
                $table->string('nomor_surat')->nullable()->after('jenis_surat');
            }
            if (!Schema::hasColumn('pengajuan_surats', 'keterangan_operator')) {
                $table->text('keterangan_operator')->nullable()->after('status');
            }
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            //
        });
    }
};

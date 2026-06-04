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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan nik jika belum ada
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik')->nullable()->after('email');
            }
            
            // Tambahkan no_hp jika belum ada
            if (!Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp')->nullable()->after('nik');
            }

            // Tambahkan foto_profil jika belum ada
            if (!Schema::hasColumn('users', 'foto_profil')) {
                $table->string('foto_profil')->nullable()->after('no_hp');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('users', 'nik')) $columns[] = 'nik';
            if (Schema::hasColumn('users', 'no_hp')) $columns[] = 'no_hp';
            if (Schema::hasColumn('users', 'foto_profil')) $columns[] = 'foto_profil';
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

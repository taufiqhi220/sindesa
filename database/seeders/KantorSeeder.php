<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KantorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kantor')->insert([
            'id' => 1,
            'nama' => 'Kantor Kelurahan Tamalanrea Indah',
            'jenis' => 'Kantor Pemerintah',
            'alamat' => 'Jl. Perintis Kemerdekaan III',
        ]);
    }
}
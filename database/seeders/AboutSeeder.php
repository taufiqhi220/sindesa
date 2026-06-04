<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\About;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        About::create([
            'key' => 'paragraph_1',
            'content' => 'ServanaID merupakan salah satu produk dari Teaching Factory (TEFA) Jurusan Teknik Informatika dan Komputer sebagai bentuk model pembelajaran berbasis produksi software dengan mengadopsi standar dan prosedur industri pada kampus Politeknik Negeri Ujung Pandang.'
        ]);

        About::create([
            'key' => 'paragraph_2',
            'content' => 'ServanaID berfokus pada proses produksi software untuk memberikan layanan publik yang dapat dimanfaatkan oleh instansi pemerintah dari desa sampai kota. Aplikasi software yang dibuat mencakup layanan administrasi kependudukan, layanan perizinan, layanan surat keterangan umum, layanan sosial, maupun layanan pemantauan aset kantor.'
        ]);

        About::create([
            'key' => 'paragraph_3',
            'content' => 'ServanaID lahir untuk dapat melakukan efisiensi birokrasi, digitalisasi layanan publik yang dapat mendukung efisiensi kerja aparat pemerintah, serta optimalisasi layanan publik dengan integrasi layanan untuk mempercepat proses. ServanaID memanfaatkan teknologi cloud dengan konsep Software as a Service (SaaS) sehingga memudahkan pengaksesan dan perawatan aplikasi.'
        ]);
    }
}

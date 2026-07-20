<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Throwable;

class ManualIndonesiaSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting final attempt manual Indonesia seeder...');

        $csvPath = base_path('vendor/laravolt/indonesia/resources/csv');

        Schema::disableForeignKeyConstraints();

        try {
            // Kolom disesuaikan dengan struktur file CSV yang sebenarnya
            $this->seedFromCsv($csvPath . DIRECTORY_SEPARATOR . 'provinces.csv', 'indonesia_provinces', ['code', 'name']);
            $this->seedFromCsv($csvPath . DIRECTORY_SEPARATOR . 'cities.csv', 'indonesia_cities', ['code', 'province_code', 'name']);
            $this->seedFromCsv($csvPath . DIRECTORY_SEPARATOR . 'districts.csv', 'indonesia_districts', ['code', 'city_code', 'name']);
            $this->seedVillagesFromFolder($csvPath . DIRECTORY_SEPARATOR . 'villages', 'indonesia_villages');
        } catch (Throwable $e) {
            $this->command->error('An error occurred during seeding: ' . $e->getMessage());
            $this->command->error('In file: ' . $e->getFile() . ' on line ' . $e->getLine());
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        $this->command->info('Manual Indonesia seeder finished.');
    }

    private function seedFromCsv(string $path, string $table, array $columnsToInsert): void
    {
        if (!File::exists($path)) {
            $this->command->error("[{$table}] CSV file not found at: {$path}");
            return;
        }

        $this->command->info("Seeding table: {$table}");
        DB::table($table)->truncate();

        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->command->error("[{$table}] Could not open file: {$path}");
            return;
        }

        // PENTING: CSV Laravolt tidak memiliki header, baris 1 adalah data (misal: 11,ACEH)
        // Jadi fgetcsv() untuk lewati header TIDAK dipanggil agar baris 1 (ACEH) tidak terlewat!

        $data = [];
        while (($row = fgetcsv($handle)) !== false) {
            // Jika baris bukan data valid (misal berisi text 'code'), lewati
            if (isset($row[0]) && strtolower($row[0]) === 'code') {
                continue;
            }
            
            // Mengambil hanya kolom yang dibutuhkan
            $rowData = array_slice($row, 0, count($columnsToInsert));
            if (count($rowData) === count($columnsToInsert)) {
                $data[] = array_combine($columnsToInsert, $rowData);
            }
        }
        fclose($handle);

        $totalRows = count($data);
        $this->command->comment("[{$table}] Found {$totalRows} valid rows for table {$table}. Inserting...");

        if ($totalRows > 0) {
            DB::transaction(function () use ($data, $table) {
                foreach (array_chunk($data, 500) as $chunk) {
                    DB::table($table)->insert($chunk);
                }
            });
        }
        
        $this->command->info("Finished seeding table: {$table}");
    }

    private function seedVillagesFromFolder(string $folderPath, string $table): void
    {
        if (!File::isDirectory($folderPath)) {
            $this->command->error("Villages directory not found at: {$folderPath}");
            return;
        }

        $this->command->info("Seeding table: {$table}");
        DB::table($table)->truncate();

        $files = File::glob($folderPath . '/*.csv');
        $columnsToInsert = ['code', 'district_code', 'name'];
        $totalRows = 0;

        foreach ($files as $file) {
            $handle = fopen($file, 'r');
            if ($handle === false) {
                $this->command->error("Could not open file: {$file}");
                continue;
            }

            $data = [];
            while (($row = fgetcsv($handle)) !== false) {
                if (isset($row[0]) && strtolower($row[0]) === 'code') {
                    continue;
                }

                // Mengambil hanya kolom yang dibutuhkan
                $rowData = array_slice($row, 0, count($columnsToInsert));
                if (count($rowData) === count($columnsToInsert)) {
                    $data[] = array_combine($columnsToInsert, $rowData);
                }
            }
            fclose($handle);
            
            $rowCount = count($data);
            $totalRows += $rowCount;

            if ($rowCount > 0) {
                DB::transaction(function () use ($data, $table) {
                    foreach (array_chunk($data, 1000) as $chunk) {
                        DB::table($table)->insert($chunk);
                    }
                });
            }
        }
        $this->command->comment("Total villages inserted: {$totalRows}");
        $this->command->info("Finished seeding table: {$table}");
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 6) as $nomorKelas) {
            Kelas::updateOrCreate(
                ['nama_kelas' => 'Kelas ' . $nomorKelas . ' SD'],
                []
            );
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kelas::truncate();
        for ($i=1; $i <= 6 ; $i++) { 
            Kelas::insert(['name' => 'Kelas '.$i, 'created_at' => now(), 'updated_at' => now()]);
        }
    }
}

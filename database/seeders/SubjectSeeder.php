<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Extracurricular;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subject::truncate();
        Extracurricular::truncate();
        $data = ['Pendidikan Agama dan Budi Pekerti', 'Pendidikan Pancasila dan Kewarganegaran', 'Bahasa Indonesia', 'Matematika', 'Ilmu Pengetahuan Alam', 'Ilmu Pengetahuan Sosial', 'Seni Budaya dan Prakarya', 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'Pendidikan IPA', 'Pendidikan IPS'];

        $dataExtra = ['Pramuka', 'Quran Club', 'Sains Club', 'English Club', 'Sanggar Mewarnai'];

        foreach ($data as $key => $value) {
            Subject::insert(['name' => $value, 'created_at' => now(), 'updated_at' => now()]);
        }

        foreach ($dataExtra as $key => $val) {
            Extracurricular::insert(['name' => $val, 'created_at' => now(), 'updated_at' => now()]);
        }
    }
}

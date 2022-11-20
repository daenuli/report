<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudentClass;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Kelas;
use App\Models\Period;
use Faker\Factory as Faker;

class StudentClassSeeder extends Seeder
{
    public function run()
    {
        // StudentClass::truncate();

        $period = Period::all();
        $kelas = Kelas::all();
        $mapel = Subject::all();
        $student = Student::all();

        // $this->command->getOutput()->progressStart(count($student));
        foreach ($period as $i => $per) {
            foreach ($student as $l => $stu) {
                foreach ($kelas as $j => $kel) {
                    // foreach ($mapel as $k => $map) {
                        StudentClass::insert([
                            'period_id' => $per->id,
                            'kelas_id' => $kel->id,
                            'student_id' => $stu->id,
                            'status' => rand(0, 1),
                        ]);
                    // }
                }
            }
            // $this->command->getOutput()->progressAdvance();
        }
        // $this->command->getOutput()->progressFinish();
    }
}

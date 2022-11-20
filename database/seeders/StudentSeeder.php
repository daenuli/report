<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Student::truncate();
        $faker = Faker::create('id_ID');

        for ($i=1; $i <= 30; $i++) {
            $gender = rand(0, 1);
            $data[] = [
                'nis' => $faker->unique()->numberBetween(0001,9999),
                'name' => ($gender) ? $faker->firstNameMale.' '.$faker->lastNameMale : $faker->firstNameFemale.' '.$faker->lastNameFemale,
                'birth_place' => $faker->city,
                'date_of_birth' => $faker->dateTimeBetween($startDate = '-15 years', $endDate = '-10 years', $timezone = null)->format('Y-m-d'),
                'phone' =>  $faker->phoneNumber,
                'gender' => ($gender) ? 'male' : 'female',
                'address' => $faker->streetAddress,
                'photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Student::insert($data);
    }
}

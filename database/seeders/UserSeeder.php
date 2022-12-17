<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        User::insert([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => bcrypt(111111),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $faker = Faker::create('id_ID');

        for ($i=1; $i <= 6; $i++) {
            $gender = rand(0, 1);
            $data[] = [
                'name' => ($gender) ? $faker->firstNameMale.' '.$faker->lastNameMale : $faker->firstNameFemale.' '.$faker->lastNameFemale,
                'email' => $faker->email,
                'password' => bcrypt(111111),
                'role' => 'teacher',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        User::insert($data);
    }
}

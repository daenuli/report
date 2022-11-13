<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Period;
use Carbon\Carbon;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $years = date('Y');
        $i = range($years-2, $years);
        // $i = range($years-5, $years);
        // $i = range($years-2, $years+3);
        $now = $years.'/'.($years+1);
        foreach ($i as $key => $value) {
            $name = $value.'/'.($value+1);
            $data[] = [
                'name' => $name,
                'status' => ($name != $now) ? 0 : 1,
                'created_at' => Carbon::now()->setYear($value),
                'updated_at' => Carbon::now()->setYear($value)
            ];
        }
        Period::truncate();
        Period::insert($data);
    }
}

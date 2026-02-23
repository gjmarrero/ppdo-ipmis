<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class ECPambTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('e_c_pamb_types')->insert([
            [
                'id' => Str::uuid(),
                'type' => 'N/A',
            ],
            [
                'id' => Str::uuid(),
                'type' => 'Mt. Data',
            ],
            [
                'id' => Str::uuid(),
                'type' => 'Upper Agno',
            ],
            [
                'id' => Str::uuid(),
                'type' => 'Lower Agno',
            ],
            [
                'id' => Str::uuid(),
                'type' => 'Marcos Highway',
            ],
            [
                'id' => Str::uuid(),
                'type' => 'Mt. Pulag',
            ]
        ]);
    }
}

<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class ECCertificateTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('e_c_certificate_types')->insert([
            [
                'id' => Str::uuid(),
                'type' => 'CNC-OL',
            ],
            [
                'id' => Str::uuid(),
                'type' => 'CNC-CatC',
            ],
            [
                'id' => Str::uuid(),
                'type' => 'ECC'
            ]
        ]);
    }
}

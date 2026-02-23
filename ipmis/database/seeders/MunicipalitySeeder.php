<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('municipalities')->insert(
            [
                [
                    'id' => Str::uuid(),
                    'municipality' => 'Atok',
                ],
                [
                    'id' => Str::uuid(),
                    'municipality' => 'Bakun',
                ],
                [
                    'id' => Str::uuid(),
                    'municipality' => 'Bokod'
                ],
                [
                    'id' => Str::uuid(),
                    'municipality' => 'Buguias'
                ],
                [
                    'id' => Str::uuid(),
                    'municipality' => 'Itogon'
                ],
                [
                    'id' => Str::uuid(),
                    'municipality' => 'Kabayan'
                ],
                [
                    'id' => Str::uuid(),
                    'municipality' => 'Kapangan'
                ],
                [
                    'id' => Str::uuid(),
                    'municipality' => 'Kibungan'
                ],
                [
                    'id' => Str::uuid(),
                    'municipality' => 'La Trinidad'
                ],
                [
                    'id' => Str::uuid(),
                    'municipality' => 'Mankayan'
                ],
                [
                    'id' => Str::uuid(),
                    'municipality' => 'Sablan'
                ],
                [
                    'id' => Str::uuid(),
                    'municipality' => 'Tuba'
                ],
                [
                    'id' => Str::uuid(),
                    'municipality' => 'Tublay'
                ]
            ]
        );

        DB::table('barangays')->insert(
            [
                [
                    'id' => Str::uuid(),
                    'municipality_id' => '06fd6c79-6208-4820-98b0-1727a66a394f',
                    'barangay' => 'Abiang',
                ],
            ]
        );
    }
}

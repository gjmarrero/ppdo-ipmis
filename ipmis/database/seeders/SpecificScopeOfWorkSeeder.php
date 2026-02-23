<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class SpecificScopeOfWorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('specific_scope_of_works')->insert(
            [
                [
                    'id' => Str::uuid(),
                    'scope' => 'PCCP',
                ],
                [
                    'id' => Str::uuid(),
                    'scope' => 'Drainage(Canal, Curb & Gutter)',
                ],
                [
                    'id' => Str::uuid(),
                    'scope' => 'Slope Protection',
                ],
                [
                    'id' => Str::uuid(),
                    'scope' => 'Flood Control',
                ],
                [
                    'id' => Str::uuid(),
                    'scope' => 'Footpath',
                ],
                [
                    'id' => Str::uuid(),
                    'scope' => 'Railings/Fencing',
                ],
                [
                    'id' => Str::uuid(),
                    'scope' => 'Waterworks',
                ],
                [
                    'id' => Str::uuid(),
                    'scope' => 'Irrigation System',
                ],
                [
                    'id' => Str::uuid(),
                    'scope' => 'Tower',
                ],
            ]
        );
    }
}

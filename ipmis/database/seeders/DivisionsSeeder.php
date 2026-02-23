<?php

namespace Database\Seeders;

use Carbon\Traits\Timestamp;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class DivisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('divisions')->insert([
            [
                'id' => Str::uuid(),
                'division' => 'General Administrative',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Jail',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Library',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Disaster Risk Reduction and Management',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Tourism',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Plans and Programs',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Monitoring and Evaluation',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Research and Statistics',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Project Development',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Planning, Programming, and Design',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Quality Control',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Motorpool',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Construction',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Maintenance',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Quality Control',
            ],
            [
                'id' => Str::uuid(),
                'division' => 'Mines and Geosciences Management',
            ]
            ]);
    }
}

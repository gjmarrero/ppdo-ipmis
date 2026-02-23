<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('project_types')->insert(
            [
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Agriculture Services',
                    'project_type_code' => 1,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Agriculture Support Facilities/Irrigation System',
                    'project_type_code' => 2,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Barangay Covered Court',
                    'project_type_code' => 3,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Bridge',
                    'project_type_code' => 4,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Building','project_type_code' => 5,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Child Development Center',
                    'project_type_code' => 6,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Comfort Room/Public Toilet',
                    'project_type_code' => 7,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Community Road',
                    'project_type_code' => 8,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Concrete Pavement',
                    'project_type_code' => 9,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Day Care Center',
                    'project_type_code' => 10,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Demolition',
                    'project_type_code' => 11,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Disaster Preparedness',
                    'project_type_code' => 12,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Drainage Canal/Sewage',
                    'project_type_code' => 13,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Environment Development Program(Flood Control/Drainage Canal)',
                    'project_type_code' => 14,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Footbridge',
                    'project_type_code' => 15,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Footpath/Foot Trail/Walk way',
                    'project_type_code' => 16,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Ground Improvement',
                    'project_type_code' => 17,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Health Infrastructure',
                    'project_type_code' => 18,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Health Stations',
                    'project_type_code' => 18,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Hospital Development',
                    'project_type_code' => 19,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Local Access Road/Farm to Market Road',
                    'project_type_code' => 19,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Multi-purpose Building/Senio Citizens Building/Barangay Hall',
                    'project_type_code' => 20,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Multi-purpose Evacuation Center',
                    'project_type_code' => 21,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Multi-purpose Gym/Basketball Court',
                    'project_type_code' => 22,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Multi-purpose Shed/Waiting Shed',
                    'project_type_code' => 23,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Municipal Road',
                    'project_type_code' => 24,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Municipal/Public Cemetery',
                    'project_type_code' => 25,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Oil Depot and Other Projects',
                    'project_type_code' => 26,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Provincial Government Properties',
                    'project_type_code' => 27,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Provincial Roads',
                    'project_type_code' => 28,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Public Market/Hall/Outpost',
                    'project_type_code' => 29,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Retaining Wall/Slope Protection',
                    'project_type_code' => 30,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Road Opening/Improvement FMR',
                    'project_type_code' => 31,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'Rural Health Unit/Barangay Health Station',
                    'project_type_code' => 32,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'School Building/Dormitory/Teachers Quarters/Comfort Room',
                    'project_type_code' => 33,
                ],
                [
                    'id' => Str::uuid(),
                    'project_type' => 'School Fencing/Covered Court',
                    'project_type_code' => 34,
                ]
            ]
        );
    }
}

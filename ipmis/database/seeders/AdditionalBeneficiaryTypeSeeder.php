<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class AdditionalBeneficiaryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('beneficiary_types')->insert(
            [
                [
                    'id' => (string) Str::uuid(),
                    'beneficiary_type' => 'No. School/s',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'beneficiary_type' => 'No. Health Facilities',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'beneficiary_type' => 'No. of Cooperatives',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'beneficiary_type' => 'No. of Church',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'beneficiary_type' => 'No. of Organizations',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'beneficiary_type' => 'Others',
                ],
            ]
        );
    }
}

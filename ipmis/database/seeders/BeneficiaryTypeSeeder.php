<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BeneficiaryTypeSeeder extends Seeder
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
                    'beneficiary_type' => 'No. of Households',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'beneficiary_type' => 'No. of Farmers',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'beneficiary_type' => 'Area of Agricultural Land in Hectares',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'beneficiary_type' => 'Institutions/Groups',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'beneficiary_type' => 'No. of Students',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'beneficiary_type' => 'No. of Teaching Personnel',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'beneficiary_type' => 'No. of Non-Teaching Personnel',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'beneficiary_type' => 'Population of the Community',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'beneficiary_type' => 'Other Areas',
                ],
            ]
        );
    }
}

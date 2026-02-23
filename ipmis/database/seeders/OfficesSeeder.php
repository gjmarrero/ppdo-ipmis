<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class OfficesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offices')->insert([
            [
                'id' => Str::uuid(),
                'office' => 'Provincial Governors Office',
                'office_acc' => 'PGO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Sangguniang Panlalawigan ng Benguet',
                'office_acc' => 'SPO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Provincial Accountants Office',
                'office_acc' => 'PAccO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Provincial Agriculturists Office',
                'office_acc' => 'PAgO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Provincial Assessors Office',
                'office_acc' => 'PAssO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Provincial Budget Office',
                'office_acc' => 'PBO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Provincial Engineers Office',
                'office_acc' => 'PEO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Benguet Environment and Natural Resources Office',
                'office_acc' => 'BENRO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Provincial General Services Office',
                'office_acc' => 'PGSO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Provincial Human Resource Management Office',
                'office_acc' => 'PHRMO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Provincial Legal Office',
                'office_acc' => 'PLO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Provincial Social Welfare and Development Office',
                'office_acc' => 'PSWDO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Provincial Treasury Office',
                'office_acc' => 'PTO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Provincial Veterinary Office',
                'office_acc' => 'PVO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Atok District Hospital',
                'office_acc' => 'ADH'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Dennis Molintas District Hospital',
                'office_acc' => 'DMDH'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Itogon District Hospital',
                'office_acc' => 'IDH'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Kapangan District Hospital',
                'office_acc' => 'KDH'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Northern Benguet District Hospital',
                'office_acc' => 'NBDH'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Provincial Health Office',
                'office_acc' => 'PHO'
            ],
            [
                'id' => Str::uuid(),
                'office' => 'Benguet General Hospital',
                'office_acc' => 'BeGH'
            ]
        ]);
    }
}

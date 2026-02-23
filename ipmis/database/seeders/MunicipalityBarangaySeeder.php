<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class MunicipalityBarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $municipalities = [
            [
                'name' => 'Atok',
                'barangays' => ['Abiang', 'Caliking', 'Cattubo', 'Naguey', 'Paoay', 'Pasdong', 'Poblacion', 'Topdac'],
            ],
            [
                'name' => 'Bakun',
                'barangays' => ['Ampusongan', 'Bagu', 'Dalipey', 'Gambang', 'Kayapa', 'Poblacion', 'Sinacbat'],
            ],
            [
                'name' => 'Bokod',
                'barangays' => ['Ambuclao', 'Bila', 'Bobok-Bisal', 'Daclan', 'Ekip', 'Karao', 'Nawal', 'Pito', 'Poblacion', 'Tikey'],
            ],
            [
                'name' => 'Buguias',
                'barangays' => ['Abatan', 'Amgaleyguey', 'Amlimay', 'Baculongan Norte', 'Baculongan Sur', 'Bangao', 'Buyacaoan', 'Calamagan', 'Catlubong', 'Lengaoan', 'Loo', 'Natubleng', 'Poblacion', 'Sebang'],
            ],
            [
                'name' => 'Itogon',
                'barangays' => ['Ampucao', 'Dalupirip', 'Gumatdang', 'Loacan', 'Poblacion', 'Tinongdan', 'Tuding', 'Ucab', 'Virac'],
            ],
            [
                'name' => 'Kabayan',
                'barangays' => ['Adaoay', 'Anchukey', 'Ballay', 'Bashoy', 'Batan', 'Duacan', 'Eddet', 'Gusaran', 'Kabayan Barrio', 'Lusod', 'Pacso', 'Poblacion', 'Tawangan'],
            ],
            [
                'name' => 'Kapangan',
                'barangays' => ['Balakbak', 'Beleng-Belis', 'Boklaoan', 'Cayapes', 'Cuba', 'Datakan', 'Gadang', 'Gasweling', 'Labueg', 'Paykek', 'Poblacion Central', 'Pongayan', 'Pudong', 'Sagubo', 'Taba-ao'],
            ],
            [
                'name' => 'Kibungan',
                'barangays' => ['Badeo', 'Lubo', 'Madaymen', 'Palina', 'Poblacion', 'Sagpat', 'Tacadang'],
            ],
            [
                'name' => 'La Trinidad',
                'barangays' => ['Alapang', 'Alno', 'Ambiong', 'Bahong', 'Balili', 'Beckel', 'Betag', 'Bineng', 'Cruz', 'Lubas', 'Pico', 'Poblacion', 'Puguis', 'Shilan', 'Tawang', 'Wangal'],
            ],
            [
                'name' => 'Mankayan',
                'barangays' => ['Balili', 'Bedbed', 'Bulalacao', 'Cabiten', 'Colalo', 'Guinaoang', 'Paco', 'Palasaan', 'Poblacion', 'Sapid', 'Tabio', 'Taneg'],
            ],
            [
                'name' => 'Sablan',
                'barangays' => ['Bagong', 'Balluay', 'Banangan', 'Banengbeng', 'Bayabas', 'Camisong', 'Kamog', 'Poblacion'],
            ],
            [
                'name' => 'Tuba',
                'barangays' => ['Ansagan', 'Camp 1', 'Camp 3', 'Camp 4', 'Nangalisan', 'Poblacion', 'San Pascual', 'Taloy Norte', 'Taloy Sur', 'Tadiangan', 'Twin Peaks', 'Tabaan Norte', 'Tabaan Sur'],
            ],
            [
                'name' => 'Tublay',
                'barangays' => ['Ambassador', 'Caponga (Central)', 'Daclan', 'Tuel', 'Basil', 'Acop', 'Tuel Proper', 'Poblacion'],
            ],
        ];

        foreach ($municipalities as $municipalityData) {
            $municipalityId = Str::uuid();

            DB::table('municipalities')->insert([
                'id' => $municipalityId,
                'municipality' => $municipalityData['name'],
            ]);

            $barangays = collect($municipalityData['barangays'])->map(fn($barangay) => [
                'id' => Str::uuid(),
                'municipality_id' => $municipalityId,
                'barangay' => $barangay,
            ])->toArray();

            DB::table('barangays')->insert($barangays);
        }
    }
}

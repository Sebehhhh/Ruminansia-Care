<?php

namespace Database\Seeders;

use App\Models\Symptom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SymptomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $symptoms = [
            [
                'code' => 'RS001',
                'name' => 'Demam Tinggi',
            ],
            [
                'code' => 'RS002',
                'name' => 'Batuk Kering',
            ],
            [
                'code' => 'RS003',
                'name' => 'Diare Berat',
            ],
            [
                'code' => 'RS004',
                'name' => 'Nafsu Makan Menurun',
            ],
            [
                'code' => 'RS005',
                'name' => 'Penurunan Produksi Susu',
            ],
            [
                'code' => 'RS006',
                'name' => 'Lesu dan Lemas',
            ],
            [
                'code' => 'RS007',
                'name' => 'Nafas Cepat',
            ],
            [
                'code' => 'RS008',
                'name' => 'Pembengkakan Kelenjar Limfa',
            ],
            [
                'code' => 'RS009',
                'name' => 'Pendarahan Mulut',
            ],
            [
                'code' => 'RS010',
                'name' => 'Lameness',
            ],
        ];

        foreach ($symptoms as $symptom) {
            Symptom::create($symptom);
        }
    }
}

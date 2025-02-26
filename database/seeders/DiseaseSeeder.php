<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Disease;
use App\Models\Rule;
use App\Models\Symptom;

class DiseaseSeeder extends Seeder
{
    public function run()
    {
        // Data penyakit beserta relasi gejala dengan nilai MB dan MD
        $diseases = [
            [
                'name' => 'Infeksi Saluran Pernapasan',
                'description' => 'Penyakit pernapasan yang disebabkan oleh infeksi bakteri atau virus.',
                'recommendation' => 'Pemberian antibiotik dan istirahat yang cukup.',
                'rules' => [
                    ['symptom_code' => 'RS001', 'mb' => 0.8, 'md' => 0.1],
                    ['symptom_code' => 'RS007', 'mb' => 0.7, 'md' => 0.2],
                ],
            ],
            [
                'name' => 'Penyakit Pencernaan',
                'description' => 'Gangguan pencernaan yang dapat menyebabkan diare dan penurunan nafsu makan.',
                'recommendation' => 'Pemberian cairan elektrolit dan pakan yang mudah dicerna.',
                'rules' => [
                    ['symptom_code' => 'RS003', 'mb' => 0.75, 'md' => 0.15],
                    ['symptom_code' => 'RS004', 'mb' => 0.6, 'md' => 0.2],
                ],
            ],
            [
                'name' => 'Infeksi Sistem Imun',
                'description' => 'Infeksi yang mempengaruhi sistem kekebalan tubuh ternak.',
                'recommendation' => 'Pemberian imunostimulan dan penanganan suportif.',
                'rules' => [
                    ['symptom_code' => 'RS005', 'mb' => 0.8, 'md' => 0.1],
                    ['symptom_code' => 'RS008', 'mb' => 0.7, 'md' => 0.2],
                ],
            ],
        ];

        foreach ($diseases as $data) {
            // Buat record penyakit
            $disease = Disease::create([
                'name'           => $data['name'],
                'description'    => $data['description'],
                'recommendation' => $data['recommendation'],
            ]);

            // Buat record rule yang menghubungkan penyakit dengan gejala
            foreach ($data['rules'] as $ruleData) {
                $symptom = Symptom::where('code', $ruleData['symptom_code'])->first();
                if ($symptom) {
                    Rule::create([
                        'disease_id' => $disease->id,
                        'symptom_id' => $symptom->id,
                        'mb'         => $ruleData['mb'],
                        'md'         => $ruleData['md'],
                    ]);
                }
            }
        }
    }
}

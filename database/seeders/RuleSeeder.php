<?php

namespace Database\Seeders;

use App\Models\Disease;
use App\Models\Rule;
use App\Models\Symptom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [
            // P21
            ['symptom_code' => 'G121', 'disease_code' => 'P21', 'mb' => 1.0, 'md' => 0.2],
        
            // P22
            ['symptom_code' => 'G01',  'disease_code' => 'P22', 'mb' => 1.0, 'md' => 0.8],
            ['symptom_code' => 'G122', 'disease_code' => 'P22', 'mb' => 1.0, 'md' => 0.6],
            ['symptom_code' => 'G123', 'disease_code' => 'P22', 'mb' => 1.0, 'md' => 0.6],
            ['symptom_code' => 'G124', 'disease_code' => 'P22', 'mb' => 1.0, 'md' => 0.2],
            ['symptom_code' => 'G125', 'disease_code' => 'P22', 'mb' => 1.0, 'md' => 0.2],
        
            // P23
            ['symptom_code' => 'G24',  'disease_code' => 'P23', 'mb' => 0.8, 'md' => 0.6],
            ['symptom_code' => 'G07',  'disease_code' => 'P23', 'mb' => 0.8, 'md' => 0.6],
            ['symptom_code' => 'G126', 'disease_code' => 'P23', 'mb' => 1.0, 'md' => 0.8],
            ['symptom_code' => 'G127', 'disease_code' => 'P23', 'mb' => 0.8, 'md' => 0.6],
            ['symptom_code' => 'G128', 'disease_code' => 'P23', 'mb' => 1.0, 'md' => 0.6],
            ['symptom_code' => 'G129', 'disease_code' => 'P23', 'mb' => 1.0, 'md' => 0.2],
            ['symptom_code' => 'G121', 'disease_code' => 'P23', 'mb' => 1.0, 'md' => 0.4],
            ['symptom_code' => 'G36',  'disease_code' => 'P23', 'mb' => 1.0, 'md' => 0.4],
        
            // P24
            ['symptom_code' => 'G130', 'disease_code' => 'P24', 'mb' => 1.0, 'md' => 0.4],
            ['symptom_code' => 'G131', 'disease_code' => 'P24', 'mb' => 1.0, 'md' => 0.2],
            ['symptom_code' => 'G132', 'disease_code' => 'P24', 'mb' => 1.0, 'md' => 0.4],
            ['symptom_code' => 'G133', 'disease_code' => 'P24', 'mb' => 1.0, 'md' => 0.4],
            ['symptom_code' => 'G134', 'disease_code' => 'P24', 'mb' => 1.0, 'md' => 0.4],
            ['symptom_code' => 'G07',  'disease_code' => 'P24', 'mb' => 0.8, 'md' => 0.6],
        
            // P25
            ['symptom_code' => 'G135', 'disease_code' => 'P25', 'mb' => 1.0, 'md' => 0.0],
            ['symptom_code' => 'G136', 'disease_code' => 'P25', 'mb' => 1.0, 'md' => 0.8],
            ['symptom_code' => 'G137', 'disease_code' => 'P25', 'mb' => 1.0, 'md' => 0.2],
            ['symptom_code' => 'G138', 'disease_code' => 'P25', 'mb' => 1.0, 'md' => 0.2],
            ['symptom_code' => 'G139', 'disease_code' => 'P25', 'mb' => 1.0, 'md' => 0.8],
            ['symptom_code' => 'G07',  'disease_code' => 'P25', 'mb' => 1.0, 'md' => 0.8],
            ['symptom_code' => 'G12',  'disease_code' => 'P25', 'mb' => 1.0, 'md' => 0.8],
        
            // P26
            ['symptom_code' => 'G01',  'disease_code' => 'P26', 'mb' => 1.0, 'md' => 0.8],
            ['symptom_code' => 'G13',  'disease_code' => 'P26', 'mb' => 0.8, 'md' => 0.6],
            ['symptom_code' => 'G07',  'disease_code' => 'P26', 'mb' => 0.8, 'md' => 0.6],
            ['symptom_code' => 'G122', 'disease_code' => 'P26', 'mb' => 1.0, 'md' => 0.8],
            ['symptom_code' => 'G140', 'disease_code' => 'P26', 'mb' => 1.0, 'md' => 0.8],
            ['symptom_code' => 'G141', 'disease_code' => 'P26', 'mb' => 1.0, 'md' => 0.4],
            ['symptom_code' => 'G142', 'disease_code' => 'P26', 'mb' => 1.0, 'md' => 0.2],
            ['symptom_code' => 'G143', 'disease_code' => 'P26', 'mb' => 1.0, 'md' => 0.0],
            ['symptom_code' => 'G144', 'disease_code' => 'P26', 'mb' => 1.0, 'md' => 0.0],
            ['symptom_code' => 'G125', 'disease_code' => 'P26', 'mb' => 1.0, 'md' => 0.0],
            ['symptom_code' => 'G116', 'disease_code' => 'P26', 'mb' => 1.0, 'md' => 0.0],
            ['symptom_code' => 'G145', 'disease_code' => 'P26', 'mb' => 0.8, 'md' => 0.4],
            ['symptom_code' => 'G146', 'disease_code' => 'P26', 'mb' => 1.0, 'md' => 0.2],
        
            // P27
            ['symptom_code' => 'G147', 'disease_code' => 'P27', 'mb' => 1.0, 'md' => 0.2],
            ['symptom_code' => 'G148', 'disease_code' => 'P27', 'mb' => 1.0, 'md' => 0.4],
            ['symptom_code' => 'G149', 'disease_code' => 'P27', 'mb' => 0.8, 'md' => 0.4],
            ['symptom_code' => 'G150', 'disease_code' => 'P27', 'mb' => 0.0, 'md' => 0.6],
            ['symptom_code' => 'G112', 'disease_code' => 'P27', 'mb' => 1.0, 'md' => 0.2],
        
            // P28
            ['symptom_code' => 'G130', 'disease_code' => 'P28', 'mb' => 1.0, 'md' => 0.2],
            ['symptom_code' => 'G145', 'disease_code' => 'P28', 'mb' => 1.0, 'md' => 0.8],
            ['symptom_code' => 'G151', 'disease_code' => 'P28', 'mb' => 1.0, 'md' => 0.2],
            ['symptom_code' => 'G152', 'disease_code' => 'P28', 'mb' => 1.0, 'md' => 0.8],
            ['symptom_code' => 'G148', 'disease_code' => 'P28', 'mb' => 1.0, 'md' => 0.2],
            ['symptom_code' => 'G153', 'disease_code' => 'P28', 'mb' => 1.0, 'md' => 0.2],
            ['symptom_code' => 'G154', 'disease_code' => 'P28', 'mb' => 1.0, 'md' => 0.2],
        ];

        foreach ($rules as $data) {
            $symptom = Symptom::where('code', $data['symptom_code'])->first();
            $disease = Disease::where('code', $data['disease_code'])->first();

            if ($symptom && $disease) {
                Rule::updateOrCreate(
                    [
                        'symptom_id' => $symptom->id,
                        'disease_id' => $disease->id,
                    ],
                    [
                        'mb' => $data['mb'],
                        'md' => $data['md'],
                    ]
                );
            }
        }
    }
}

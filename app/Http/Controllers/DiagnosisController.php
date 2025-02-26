<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Symptom;
use App\Models\Rule;
use App\Models\Disease;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class DiagnosisController extends Controller
{
    /**
     * Menampilkan halaman diagnosis dengan daftar gejala.
     */
    public function index()
    {
        // Ambil semua data gejala untuk ditampilkan
        $symptoms = Symptom::all();
        return view('diagnosis.index', compact('symptoms'));
    }

    /**
     * Memproses perhitungan CF berdasarkan gejala yang dipilih.
     */
    public function process(Request $request)
    {
        // Validasi input: pastikan kategori dan jawaban (answers) tersedia
        $request->validate([
            'category' => 'required|string',
            'answers'  => 'required|array'
        ]);

        // Ambil kategori (misalnya: Sapi, Kerbau, Kambing, Domba)
        $category = $request->input('category');

        // Ambil jawaban: array dengan key = symptom_id dan value = tingkat keyakinan (0, 0.2, ... 1)
        $answers = $request->input('answers');

        // Filter hanya gejala dengan nilai keyakinan > 0 (misalnya, jika 0 = Tidak Tahu)
        $selectedSymptomIds = [];
        foreach ($answers as $symptomId => $value) {
            if ((float) $value > 0) {
                $selectedSymptomIds[] = $symptomId;
            }
        }

        if (empty($selectedSymptomIds)) {
            return redirect()->back()->withErrors(['answers' => 'Anda harus memilih minimal satu gejala dengan keyakinan lebih dari "Tidak Tahu".']);
        }

        // Ambil semua rule yang relevan dengan gejala yang dipilih
        $rules = Rule::whereIn('symptom_id', $selectedSymptomIds)->get();

        // Kelompokkan rule berdasarkan disease_id, dengan perhitungan CF disesuaikan dengan tingkat keyakinan user
        $groupedRules = [];
        foreach ($rules as $rule) {
            if (isset($answers[$rule->symptom_id])) {
                // Kalikan nilai CF rule (mb - md) dengan tingkat keyakinan user
                $cf_rule = ($rule->mb - $rule->md) * (float) $answers[$rule->symptom_id];
                $groupedRules[$rule->disease_id][] = $cf_rule;
            }
        }

        // Proses perhitungan CF untuk setiap penyakit
        $results = [];
        foreach ($groupedRules as $diseaseId => $cf_rules) {
            $cf = null;
            foreach ($cf_rules as $cf_rule) {
                if ($cf === null) {
                    $cf = $cf_rule;
                } else {
                    // Kombinasi CF menggunakan aturan:
                    if ($cf >= 0 && $cf_rule >= 0) {
                        $cf = $cf + $cf_rule * (1 - $cf);
                    } elseif ($cf < 0 && $cf_rule < 0) {
                        $cf = $cf + $cf_rule * (1 + $cf);
                    } else {
                        $cf = ($cf + $cf_rule) / (1 - min(abs($cf), abs($cf_rule)));
                    }
                }
            }
            $results[$diseaseId] = $cf;
        }

        // Urutkan hasil diagnosis berdasarkan nilai CF tertinggi
        arsort($results);

        // Siapkan data diagnosis untuk ditampilkan
        $diagnosisResults = [];
        foreach ($results as $diseaseId => $cfValue) {
            $disease = Disease::find($diseaseId);
            if ($disease) {
                $diagnosisResults[] = [
                    'disease' => $disease,
                    'cf'      => $cfValue
                ];
            }
        }

        // Simpan hasil diagnosis ke tabel history, ambil penyakit dengan nilai CF tertinggi (jika ada)
        if (!empty($diagnosisResults)) {
            $topDiagnosis = $diagnosisResults[0];
            History::create([
                'user_id'           => Auth::id(),
                'disease_id'        => $topDiagnosis['disease']->id,
                'category'          => $category, // Simpan kategori ke history
                'confidence'        => round($topDiagnosis['cf'], 2),
                'selected_symptoms' => json_encode($answers)
            ]);
        }

        return redirect()->route('history.index')
            ->with('success', 'Diagnosis berhasil disimpan.');
    }
}

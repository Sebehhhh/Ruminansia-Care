<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalSymptom;
use App\Models\Disease;
use App\Models\History;
use App\Models\Rule;
use App\Models\Symptom;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    /**
     * Menampilkan halaman awal diagnosis dengan daftar hewan dan gejala.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $animals = Animal::all();
        $symptoms = [];

        // Jika ada animal_id, ambil gejala sesuai hewan
        if ($request->has('animal_id') && $request->animal_id) {
            $symptomIds = AnimalSymptom::where('animal_id', $request->animal_id)
                ->pluck('symptom_id');
            $symptoms = Symptom::whereIn('id', $symptomIds)->get();
        }

        return view('diagnosis.index', compact('animals', 'symptoms'));
    }


    public function getSymptoms(Request $request)
    {
        $animalId = $request->animal_id;
        // Ambil symptom_id dari animal_symptom yang sesuai animal_id
        $symptomIds = AnimalSymptom::where('animal_id', $animalId)->pluck('symptom_id');
        // Ambil detail symptom-nya
        $symptoms = Symptom::whereIn('id', $symptomIds)->get();

        return response()->json($symptoms);
    }

    public function process(Request $request)
    {
        $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'symptoms' => 'required|array',
            'symptoms.*' => 'nullable|numeric|min:0|max:1',
        ]);

        $animalId = $request->input('animal_id');
        $inputSymptoms = $request->input('symptoms');

        // Ambil semua penyakit yang mungkin pada hewan ini
        $diseaseIds = \App\Models\AnimalDisease::where('animal_id', $animalId)
            ->pluck('disease_id')->toArray();
        $diseases = Disease::whereIn('id', $diseaseIds)->get();

        $results = [];
        foreach ($diseases as $disease) {
            // Ambil rules yang berhubungan dengan penyakit ini
            $rules = Rule::where('disease_id', $disease->id)
                ->whereIn('symptom_id', array_keys($inputSymptoms))
                ->get();

            $CFs = [];
            foreach ($rules as $rule) {
                $userCF = floatval($inputSymptoms[$rule->symptom_id]);
                // Rumus: CF = (MB - MD) * userCF
                $cf = ($rule->mb - $rule->md) * $userCF;
                $CFs[] = $cf;
            }

            // Kombinasikan CF
            $cfCombine = null;
            foreach ($CFs as $cf) {
                if (is_null($cfCombine)) {
                    $cfCombine = $cf;
                } else {
                    $cfCombine = $cfCombine + $cf * (1 - $cfCombine);
                }
            }
            $results[] = [
                'disease' => $disease,
                'confidence' => round($cfCombine ?? 0, 3),
            ];
        }

        // Urutkan hasil berdasarkan confidence tertinggi
        usort($results, fn($a, $b) => $b['confidence'] <=> $a['confidence']);
        $top = $results[0] ?? null;

        // Simpan ke history (opsional)
        if ($top) {
            History::create([
                'user_id'           => auth()->id(),
                'animal_id'         => $animalId, // <--- tambah ini!
                'disease_id'        => $top['disease']->id,
                'category'          => $top['disease']->name,
                'confidence'        => $top['confidence'],
                'selected_symptoms' => $inputSymptoms, // ga perlu json_encode, sudah pakai casts
            ]);
        }

        // Kirim ke view hasil diagnosis
        return view('diagnosis.result', [
            'results' => $results,
            'top' => $top,
            'inputSymptoms' => $inputSymptoms,
        ]);
    }

    public function result(Request $request)
    {
        // Ambil data hasil diagnosis dari session atau parameter (tergantung implementasi proses sebelumnya)
        $results = session('results');
        $top = session('top');
        $inputSymptoms = session('inputSymptoms');

        // Kalau tidak ada hasil, redirect ke halaman awal diagnosis
        if (!$results || !$top) {
            return redirect()->route('diagnosis')->with('error', 'Data hasil diagnosis tidak ditemukan.');
        }

        return view('diagnosis.result', compact('results', 'top', 'inputSymptoms'));
    }
}

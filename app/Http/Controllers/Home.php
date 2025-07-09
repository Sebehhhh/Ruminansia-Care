<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalSymptom;
use App\Models\Disease;
use App\Models\Rule;
use App\Models\Symptom;
use Illuminate\Http\Request;

class Home extends Controller
{
    public function index()
    {
        $jumlahHewan = Animal::count();
        $jumlahPenyakit = Disease::count();
        $jumlahGejala = Symptom::count();
        $jumlahDiagnosa = \App\Models\History::count();

        return view('welcome', compact(
            'jumlahHewan',
            'jumlahPenyakit',
            'jumlahGejala',
            'jumlahDiagnosa'
        ));
    }

    /**
     * Form diagnosa untuk guest user.
     */
    public function diagnosa(Request $request)
    {
        $animals = Animal::all();
        $symptoms = [];

        if ($request->has('animal_id') && $request->animal_id) {
            $symptomIds = AnimalSymptom::where('animal_id', $request->animal_id)->pluck('symptom_id');
            $symptoms = Symptom::whereIn('id', $symptomIds)->get();
        }

        return view('diagnosa.index', compact('animals', 'symptoms'));
    }

    /**
     * Proses diagnosa untuk guest user (tanpa menyimpan riwayat).
     */
    public function process(Request $request)
    {
        $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'symptoms' => 'required|array',
            'symptoms.*' => 'nullable|numeric|min:0|max:1',
        ]);

        $animalId = $request->input('animal_id');
        $inputSymptoms = $request->input('symptoms');

        // Ambil penyakit yang mungkin pada hewan ini
        $diseaseIds = \App\Models\AnimalDisease::where('animal_id', $animalId)
            ->pluck('disease_id')->toArray();
        $diseases = Disease::whereIn('id', $diseaseIds)->get();

        $results = [];

        foreach ($diseases as $disease) {
            $rules = Rule::where('disease_id', $disease->id)
                ->whereIn('symptom_id', array_keys($inputSymptoms))
                ->get();

            $CFs = [];

            foreach ($rules as $rule) {
                $userCF = floatval($inputSymptoms[$rule->symptom_id]);
                $cf = ($rule->mb - $rule->md) * $userCF;
                $CFs[] = $cf;
            }

            $cfCombine = null;
            foreach ($CFs as $cf) {
                $cfCombine = is_null($cfCombine)
                    ? $cf
                    : $cfCombine + $cf * (1 - $cfCombine);
            }

            $results[] = [
                'disease' => $disease,
                'confidence' => round($cfCombine ?? 0, 3),
            ];
        }

        usort($results, fn($a, $b) => $b['confidence'] <=> $a['confidence']);
        $top = $results[0] ?? null;

        return view('diagnosa.result', [
            'results' => $results,
            'top' => $top,
            'inputSymptoms' => $inputSymptoms,
        ]);
    }
}

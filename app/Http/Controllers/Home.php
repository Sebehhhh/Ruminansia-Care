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
        $totalSymptoms = 0;

        if ($request->has('animal_id') && $request->animal_id) {
            $symptomIds = AnimalSymptom::where('animal_id', $request->animal_id)->pluck('symptom_id');
            
            // Hitung total gejala untuk informasi paginasi
            $totalSymptoms = Symptom::whereIn('id', $symptomIds)->count();
            
            // Ambil gejala dengan paginasi (10 per halaman)
            $symptoms = Symptom::whereIn('id', $symptomIds)
                ->paginate(10)
                ->withQueryString(); // Mempertahankan parameter URL lainnya
        }

        return view('diagnosa.index', compact('animals', 'symptoms', 'totalSymptoms'));
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

        // Filter gejala: hanya yang > 0 (tidak 0 dan tidak null)
        $filteredSymptoms = [];
        foreach ($inputSymptoms as $symptomId => $value) {
            if ($value !== null && floatval($value) > 0) {
                $filteredSymptoms[$symptomId] = $value;
            }
        }
        
        // Validasi apakah ada gejala yang dipilih (nilai > 0)
        if (empty($filteredSymptoms)) {
            return redirect()->back()->with('error', 'Anda harus memilih setidaknya satu gejala dengan tingkat keyakinan lebih dari 0 (Tidak Tahu).');
        }

        // Ambil penyakit yang mungkin pada hewan ini
        $diseaseIds = \App\Models\AnimalDisease::where('animal_id', $animalId)
            ->pluck('disease_id')->toArray();
        $diseases = Disease::whereIn('id', $diseaseIds)->get();

        $results = [];

        foreach ($diseases as $disease) {
            $rules = Rule::where('disease_id', $disease->id)
                ->whereIn('symptom_id', array_keys($filteredSymptoms))
                ->get();

            $CFs = [];

            foreach ($rules as $rule) {
                $userCF = floatval($filteredSymptoms[$rule->symptom_id]);
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
            'inputSymptoms' => $filteredSymptoms, // hanya gejala > 0 yang dikirimkan
        ]);
    }
}

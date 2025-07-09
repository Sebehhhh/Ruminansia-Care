<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Symptom;
use App\Models\AnimalSymptom;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class AnimalSymptomController extends Controller
{
    public function index(Request $request)
    {
        $animals = Animal::all();
    
        // Pastikan join, select, dan where pakai nama tabel: animal_symptom (singular)
        $query = AnimalSymptom::with(['animal', 'symptom'])
            ->join('animals', 'animal_symptom.animal_id', '=', 'animals.id')
            ->join('symptoms', 'animal_symptom.symptom_id', '=', 'symptoms.id')
            ->orderBy('animals.name') // Atau 'animals.code' jika ingin urut kode hewan
            ->orderByRaw('CAST(SUBSTRING(symptoms.code, 2) AS UNSIGNED)');
    
        if ($request->filled('animal_id')) {
            $query->where('animal_symptom.animal_id', $request->animal_id);
        }
    
        // select kolom utama dari tabel animal_symptom
        $animalSymptoms = $query->select('animal_symptom.*')->paginate(5)->withQueryString();
    
        return view('animal_symptoms.index', compact('animalSymptoms', 'animals'));
    }

    public function create()
    {
        $animals = Animal::all();
        $symptoms = Symptom::all();
        return view('animal_symptoms.create', compact('animals', 'symptoms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id'    => 'required|exists:animals,id',
            'symptom_ids'  => 'required|array|min:1',
            'symptom_ids.*' => 'exists:symptoms,id',
        ]);

        try {
            foreach ($validated['symptom_ids'] as $symptomId) {
                AnimalSymptom::create([
                    'animal_id'  => $validated['animal_id'],
                    'symptom_id' => $symptomId,
                ]);
            }

            return redirect()->route('animal_symptoms.index')
                ->with('success', 'Data gejala hewan berhasil ditambahkan.');
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->withInput()
                    ->with('error', 'Data gejala hewan tersebut sudah ada.');
            }
            throw $e;
        }
    }

    public function edit($id)
    {
        $animal = Animal::findOrFail($id);
        $symptoms = Symptom::all();
        $selectedSymptoms = AnimalSymptom::where('animal_id', $animal->id)->pluck('symptom_id')->toArray();

        return view('animal_symptoms.edit', compact('animal', 'symptoms', 'selectedSymptoms'));
    }

    public function update(Request $request, $id)
    {
        $animal = Animal::findOrFail($id);

        $validated = $request->validate([
            'symptom_ids'   => 'required|array|min:1',
            'symptom_ids.*' => 'exists:symptoms,id',
        ]);

        AnimalSymptom::where('animal_id', $animal->id)->delete();

        foreach ($validated['symptom_ids'] as $symptomId) {
            AnimalSymptom::create([
                'animal_id'  => $animal->id,
                'symptom_id' => $symptomId,
            ]);
        }

        return redirect()->route('animal_symptoms.index')
            ->with('success', 'Data gejala hewan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $animalSymptom = AnimalSymptom::findOrFail($id);
        $animalSymptom->delete();

        return redirect()->route('animal_symptoms.index')
            ->with('success', 'Data gejala hewan berhasil dihapus.');
    }
}

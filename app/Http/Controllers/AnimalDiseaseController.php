<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Disease;
use App\Models\AnimalDisease;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AnimalDiseaseController extends Controller
{
    /**
     * Display a listing of the animal diseases.
     */
    public function index(Request $request)
    {
        $animals = Animal::all(); // untuk dropdown filter & edit

        $query = AnimalDisease::with(['animal', 'disease'])->latest();

        if ($request->filled('animal_id')) {
            $query->where('animal_id', $request->animal_id);
        }

        $animalDiseases = $query->paginate(5)->withQueryString(); // withQueryString agar filter tetap saat pagination

        return view('animal_diseases.index', compact('animalDiseases', 'animals'));
    }

    /**
     * Show the form for creating a new animal disease entry.
     */
    public function create()
    {
        $animals = Animal::all();
        $diseases = Disease::all();

        return view('animal_diseases.create', compact('animals', 'diseases'));
    }

    /**
     * Store a newly created animal disease in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id'    => 'required|exists:animals,id',
            'disease_ids'  => 'required|array|min:1',
            'disease_ids.*' => 'exists:diseases,id',
        ]);

        try {
            foreach ($validated['disease_ids'] as $diseaseId) {
                AnimalDisease::create([
                    'animal_id'  => $validated['animal_id'],
                    'disease_id' => $diseaseId,
                ]);
            }

            return redirect()->route('animal_diseases.index')
                ->with('success', 'Data penyakit hewan berhasil ditambahkan.');
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Data penyakit hewan tersebut sudah pernah ditambahkan.');
            }

            throw $e; // lempar ulang jika bukan error duplikat
        }
    }

    /**
     * Show the form for editing diseases of a specific animal (supports multiple diseases).
     * $id is the animal_id.
     */
    public function edit($animal_id)
    {
        $animal = Animal::findOrFail($animal_id);
        $diseases = Disease::all();
        $selectedDiseases = AnimalDisease::where('animal_id', $animal->id)->pluck('disease_id')->toArray();

        return view('animal_diseases.edit', compact('animal', 'diseases', 'selectedDiseases'));
    }

    /**
     * Update all diseases for a specific animal.
     * $id is the animal_id.
     */
    public function update(Request $request, $id)
    {
        $animal = Animal::findOrFail($id);

        $validated = $request->validate([
            'disease_ids'   => 'required|array|min:1',
            'disease_ids.*' => 'exists:diseases,id',
        ]);

        // Hapus semua relasi lama
        AnimalDisease::where('animal_id', $animal->id)->delete();

        // Tambahkan relasi baru
        foreach ($validated['disease_ids'] as $diseaseId) {
            AnimalDisease::create([
                'animal_id'  => $animal->id,
                'disease_id' => $diseaseId,
            ]);
        }

        return redirect()->route('animal_diseases.index')
            ->with('success', 'Data penyakit hewan berhasil diperbarui.');
    }

    /**
     * Remove the specified animal disease from storage.
     */
    public function destroy($id)
    {
        $animalDisease = AnimalDisease::findOrFail($id);
        $animalDisease->delete();

        return redirect()->route('animal_diseases.index')
            ->with('success', 'Data penyakit hewan berhasil dihapus.');
    }
}

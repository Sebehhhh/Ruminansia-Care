<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalDisease;
use App\Models\AnimalSymptom;
use App\Models\Rule;
use App\Models\Disease;
use App\Models\Symptom;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    /**
     * Menampilkan daftar rule dengan paginasi.
     */
    public function index(Request $request)
    {
        $animalId = $request->query('animal_id');
        $animals = Animal::all();
    
        $query = Rule::with(['disease', 'symptom']);
    
        if ($animalId) {
            $diseaseIds = AnimalDisease::where('animal_id', $animalId)->pluck('disease_id');
            $symptomIds = AnimalSymptom::where('animal_id', $animalId)->pluck('symptom_id');
    
            $query->where(function ($q) use ($diseaseIds, $symptomIds) {
                $q->whereIn('disease_id', $diseaseIds)
                  ->orWhereIn('symptom_id', $symptomIds);
            });
        }
    
        $rules = $query->paginate(10);
    
        return view('rules.index', compact('rules', 'animals', 'animalId'));
    }

    /**
     * Menampilkan form untuk membuat rule baru.
     */
    public function create()
    {
        // Mengambil data penyakit dan gejala untuk dropdown
        $diseases = Disease::all();
        $symptoms = Symptom::all();
        return view('rules.create', compact('diseases', 'symptoms'));
    }

    /**
     * Menyimpan data rule baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'disease_id' => 'required|exists:diseases,id',
            'symptom_id' => 'required|exists:symptoms,id',
            'mb'         => 'required|numeric|min:0|max:1',
            'md'         => 'required|numeric|min:0|max:1',
        ]);

        Rule::create($validated);

        return redirect()->route('rules.index')
            ->with('success', 'Rule berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail rule berdasarkan ID yang telah dienkripsi.
     */
    public function show(string $encryptedId)
    {
        $id = decrypt($encryptedId);
        $rule = Rule::findOrFail($id);
        return view('rules.show', compact('rule'));
    }

    /**
     * Menampilkan form untuk mengedit rule.
     */
    public function edit(string $encryptedId)
    {
        // $id = decrypt($encryptedId);
        $rule = Rule::findOrFail($encryptedId);
        $diseases = Disease::all();
        $symptoms = Symptom::all();

        return view('rules.edit', compact('rule', 'diseases', 'symptoms'));
    }

    /**
     * Memperbarui data rule berdasarkan ID yang telah dienkripsi.
     */
    public function update(Request $request, string $encryptedId)
    {
        // $id = decrypt($encryptedId);
        $rule = Rule::findOrFail($encryptedId);

        $validated = $request->validate([
            'disease_id' => 'required|exists:diseases,id',
            'symptom_id' => 'required|exists:symptoms,id',
            'mb'         => 'required|numeric|min:0|max:1',
            'md'         => 'required|numeric|min:0|max:1',
        ]);

        $rule->update($validated);

        return redirect()->route('rules.index')
            ->with('success', 'Rule berhasil diperbarui.');
    }

    /**
     * Menghapus rule berdasarkan ID yang telah dienkripsi.
     */
    public function destroy(string $encryptedId)
    {
        // $id = decrypt($encryptedId);
        $rule = Rule::findOrFail($encryptedId);
        $rule->delete();

        return redirect()->route('rules.index')
            ->with('success', 'Rule berhasil dihapus.');
    }
}

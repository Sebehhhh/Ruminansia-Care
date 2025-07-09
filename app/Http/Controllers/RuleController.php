<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalDisease;
use App\Models\AnimalSymptom;
use App\Models\Rule;
use App\Models\Disease;
use App\Models\Symptom;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule as ValidationRule;

class RuleController extends Controller
{
    /**
     * Menampilkan daftar rule dengan filter berdasarkan hewan.
     */
    public function index(Request $request)
{
    $animalId = $request->query('animal_id');
    $diseaseId = $request->query('disease_id');
    $symptomId = $request->query('symptom_id');

    $animals = Animal::all();
    $diseases = Disease::orderBy('code')->get();
    $symptoms = Symptom::orderBy('code')->get();

    $query = Rule::with(['disease', 'symptom'])
        ->join('diseases', 'rules.disease_id', '=', 'diseases.id');

    if ($animalId) {
        $diseaseIds = AnimalDisease::where('animal_id', $animalId)->pluck('disease_id');
        $symptomIds = AnimalSymptom::where('animal_id', $animalId)->pluck('symptom_id');
        $query->whereIn('rules.disease_id', $diseaseIds)
              ->whereIn('rules.symptom_id', $symptomIds);
    }

    if ($diseaseId) {
        $query->where('rules.disease_id', $diseaseId);
    }

    if ($symptomId) {
        $query->where('rules.symptom_id', $symptomId);
    }

    $query->orderByRaw("CAST(SUBSTRING(diseases.code, 2) AS UNSIGNED)");

    $rules = $query->select('rules.*')->paginate(10)->withQueryString();

    return view('rules.index', compact('rules', 'animals', 'animalId', 'diseases', 'symptoms', 'diseaseId', 'symptomId'));
}

    /**
     * Menampilkan form untuk membuat rule baru.
     */
    public function create()
    {
        $diseases = Disease::all();
        $symptoms = Symptom::all();
        return view('rules.create', compact('diseases', 'symptoms'));
    }

    /**
     * Menyimpan rule baru ke database dengan validasi kombinasi unik.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'disease_id' => ['required', 'exists:diseases,id'],
            'symptom_id' => [
                'required',
                'exists:symptoms,id',
                ValidationRule::unique('rules')->where(function ($query) use ($request) {
                    return $query->where('disease_id', $request->disease_id);
                }),
            ],
            'mb' => 'required|numeric|min:0|max:1',
            'md' => 'required|numeric|min:0|max:1',
        ]);

        Rule::create($validated);

        return redirect()->route('rules.index')->with('success', 'Rule berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit rule berdasarkan ID.
     */
    public function edit(string $id)
    {
        $rule = Rule::findOrFail($id);
        $diseases = Disease::all();
        $symptoms = Symptom::all();

        return view('rules.edit', compact('rule', 'diseases', 'symptoms'));
    }

    /**
     * Memperbarui rule dengan validasi kombinasi unik kecuali dirinya sendiri.
     */
    public function update(Request $request, string $id)
    {
        $rule = Rule::findOrFail($id);

        $validated = $request->validate([
            'disease_id' => ['required', 'exists:diseases,id'],
            'symptom_id' => [
                'required',
                'exists:symptoms,id',
                ValidationRule::unique('rules')
                    ->where(function ($query) use ($request) {
                        return $query->where('disease_id', $request->disease_id);
                    })
                    ->ignore($rule->id),
            ],
            'mb' => 'required|numeric|min:0|max:1',
            'md' => 'required|numeric|min:0|max:1',
        ]);

        $rule->update($validated);

        return redirect()->route('rules.index')->with('success', 'Rule berhasil diperbarui.');
    }

    /**
     * Menghapus rule berdasarkan ID.
     */
    public function destroy(string $id)
    {
        $rule = Rule::findOrFail($id);
        $rule->delete();

        return redirect()->route('rules.index')->with('success', 'Rule berhasil dihapus.');
    }
}

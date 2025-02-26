<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use App\Models\Disease;
use App\Models\Symptom;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    /**
     * Menampilkan daftar rule dengan paginasi.
     */
    public function index()
    {
        // Mengambil data rule terbaru dengan paginasi 5 per halaman
        $rules = Rule::latest()->paginate(5);

        // Menambahkan properti encrypted_id pada setiap rule
        foreach ($rules as $rule) {
            $rule->encrypted_id = encrypt($rule->id);
        }

        return view('rules.index', compact('rules'));
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
        $id = decrypt($encryptedId);
        $rule = Rule::findOrFail($id);
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
        $id = decrypt($encryptedId);
        $rule = Rule::findOrFail($id);
        $rule->delete();

        return redirect()->route('rules.index')
            ->with('success', 'Rule berhasil dihapus.');
    }
}
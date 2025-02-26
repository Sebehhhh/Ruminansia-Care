<?php

namespace App\Http\Controllers;

use App\Models\Symptom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SymptomController extends Controller
{
    /**
     * Display a listing of the symptoms.
     */
    public function index()
    {
        // Mengambil data gejala terbaru dengan paginasi 5 per halaman
        $symptoms = Symptom::latest()->paginate(5);

        // Menambahkan properti encrypted_id pada setiap symptom
        foreach ($symptoms as $symptom) {
            $symptom->encrypted_id = encrypt($symptom->id);
        }

        return view('symptoms.index', compact('symptoms'));
    }

    /**
     * Show the form for creating a new symptom.
     */
    public function create()
    {
        return view('symptoms.create');
    }

    /**
     * Store a newly created symptom in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:symptoms,code',
            'name' => 'required|string|max:255|unique:symptoms,name',
        ]);

        Symptom::create($validated);

        return redirect()->route('symptoms.index')
            ->with('success', 'Gejala berhasil ditambahkan.');
    }

    /**
     * Display the specified symptom.
     */
    public function show(string $encryptedId)
    {
        $id = decrypt($encryptedId);
        $symptom = Symptom::findOrFail($id);
        return view('symptoms.show', compact('symptom'));
    }

    /**
     * Show the form for editing the specified symptom.
     */
    public function edit(string $encryptedId)
    {
        $id = decrypt($encryptedId);
        $symptom = Symptom::findOrFail($id);
        return view('symptoms.edit', compact('symptom'));
    }

    /**
     * Update the specified symptom in storage.
     */
    public function update(Request $request, string $encryptedId)
    {
        // $id = decrypt($encryptedId);
        $symptom = Symptom::findOrFail($encryptedId);

        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:symptoms,code,' . $symptom->id,
            'name' => 'required|string|max:255|unique:symptoms,name,' . $symptom->id,
        ]);

        $symptom->update($validated);

        return redirect()->route('symptoms.index')
            ->with('success', 'Gejala berhasil diperbarui.');
    }

    /**
     * Remove the specified symptom from storage.
     */
    public function destroy(string $encryptedId)
    {
        // $id = decrypt($encryptedId);
        $symptom = Symptom::findOrFail($encryptedId);
        $symptom->delete();

        return redirect()->route('symptoms.index')
            ->with('success', 'Gejala berhasil dihapus.');
    }
}

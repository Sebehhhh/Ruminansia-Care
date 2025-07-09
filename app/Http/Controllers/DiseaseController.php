<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class DiseaseController extends Controller
{
    /**
     * Display a listing of the diseases.
     */
    public function index()
    {
        $diseases = Disease::select('*')
            ->orderByRaw("CAST(SUBSTRING(code, 2) AS UNSIGNED)") // Ambil angka setelah 'P' dan urutkan numerik
            ->paginate(5);
    
        foreach ($diseases as $disease) {
            $disease->encrypted_id = encrypt($disease->id);
        }
    
        return view('diseases.index', compact('diseases'));
    }

    /**
     * Show the form for creating a new disease.
     */
    public function create()
    {
        return view('diseases.create');
    }

    /**
     * Store a newly created disease in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'           => 'nullable|string|max:255|unique:diseases,code',
            'name'           => 'required|string|max:255|unique:diseases,name',
            'description'    => 'required|string',
            'recommendation' => 'required|string',
        ]);

        Disease::create($validated);

        return redirect()->route('diseases.index')
            ->with('success', 'Penyakit berhasil ditambahkan.');
    }

    /**
     * Display the specified disease.
     */
    public function show(string $encryptedId)
    {
        $id = decrypt($encryptedId);
        $disease = Disease::findOrFail($id);
        return view('diseases.show', compact('disease'));
    }

    /**
     * Show the form for editing the specified disease.
     */
    public function edit(string $encryptedId)
    {
        $id = decrypt($encryptedId);
        $disease = Disease::findOrFail($id);
        return view('diseases.edit', compact('disease'));
    }

    /**
     * Update the specified disease in storage.
     */
    public function update(Request $request, string $encryptedId)
    {
        // $id = decrypt($encryptedId);
        $disease = Disease::findOrFail($encryptedId);

        $validated = $request->validate([
            'code'           => 'nullable|string|max:255|unique:diseases,code,' . $disease->id,
            'name'           => 'required|string|max:255|unique:diseases,name,' . $disease->id,
            'description'    => 'required|string',
            'recommendation' => 'required|string',
        ]);

        $disease->update($validated);

        return redirect()->route('diseases.index')
            ->with('success', 'Penyakit berhasil diperbarui.');
    }

    /**
     * Remove the specified disease from storage.
     */
    public function destroy(string $encryptedId)
    {
        $id = decrypt($encryptedId);
        $disease = Disease::findOrFail($id);
        $disease->delete();

        return redirect()->route('diseases.index')
            ->with('success', 'Penyakit berhasil dihapus.');
    }
}

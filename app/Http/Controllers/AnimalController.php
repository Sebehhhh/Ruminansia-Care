<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $animals = Animal::latest()->paginate(5);

        foreach ($animals as $animal) {
            $animal->encrypted_id = encrypt($animal->id);
        }

        return view('animals.index', compact('animals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('animals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:animals,name',
        ]);

        Animal::create($validated);

        return redirect()->route('animals.index')
            ->with('success', 'Hewan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $encryptedId)
    {
        $id = decrypt($encryptedId);
        $animal = Animal::findOrFail($id);
        return view('animals.edit', compact('animal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $encryptedId)
    {
        $id = decrypt($encryptedId);
        $animal = Animal::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:animals,name,' . $animal->id,
        ]);

        $animal->update($validated);

        return redirect()->route('animals.index')
            ->with('success', 'Hewan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $encryptedId)
    {
        $id = decrypt($encryptedId);
        $animal = Animal::findOrFail($id);
        $animal->delete();

        return redirect()->route('animals.index')
            ->with('success', 'Hewan berhasil dihapus.');
    }
}

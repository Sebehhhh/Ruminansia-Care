<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalSymptom;
use Illuminate\Http\Request;
use App\Models\Symptom;
use App\Models\Rule;
use App\Models\Disease;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class DiagnosisController extends Controller
{
    /**
     * Menampilkan halaman diagnosis dengan daftar gejala.
     */
    public function index(Request $request)
    {
        $animals = Animal::all();
        $symptoms = [];

        if ($request->filled('animal_id')) {
            $animalId = $request->input('animal_id');
            $symptoms = AnimalSymptom::with('symptom')
                ->where('animal_id', $animalId)
                ->get()
                ->pluck('symptom')
                ->sortBy('name')
                ->values();
        }
        // dd($symptoms);

        return view('diagnosis.index', compact('animals', 'symptoms'));
    }

   
}

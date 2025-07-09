<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Disease;
use App\Models\Symptom;
use App\Models\Rule;
use App\Models\History;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik total
        $jumlahHewan     = Animal::count();
        $jumlahPenyakit  = Disease::count();
        $jumlahGejala    = Symptom::count();
        $jumlahRule      = Rule::count();
        $jumlahDiagnosa  = History::count();
        $jumlahUsers     = User::count(); // Jika menggunakan multi-user login

        // Statistik Diagnosa Bulanan (12 bulan terakhir)
        $diagnosisPerBulan = History::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as jumlah")
            ->groupBy('bulan')
            ->orderBy('bulan', 'desc')
            ->limit(12)
            ->get()
            ->reverse();

        $chartLabels = $diagnosisPerBulan->pluck('bulan')->map(function ($bulan) {
            return date('M Y', strtotime($bulan . '-01'));
        });

        $chartData = $diagnosisPerBulan->pluck('jumlah');

        // Penyakit dengan jumlah diagnosis terbanyak (optional, tidak digunakan di view ini)
        $penyakitTeratas = History::select('disease_id', DB::raw('COUNT(*) as total'))
            ->groupBy('disease_id')
            ->orderByDesc('total')
            ->with('disease')
            ->limit(5)
            ->get();

        // Riwayat diagnosa terbaru
        $recentDiagnoses = History::with(['user', 'animal', 'disease'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'jumlahHewan',
            'jumlahPenyakit',
            'jumlahGejala',
            'jumlahRule',
            'jumlahDiagnosa',
            'jumlahUsers',
            'chartLabels',
            'chartData',
            'recentDiagnoses'
        ));
    }
}

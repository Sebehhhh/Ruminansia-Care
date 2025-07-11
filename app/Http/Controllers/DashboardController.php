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

        // Grafik Harian (7 hari terakhir)
        $diagnosisPerHari = History::selectRaw("DATE(created_at) as hari, COUNT(*) as jumlah")
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('hari')
            ->orderBy('hari', 'asc')
            ->get();

        // Pastikan label harian selalu 7 hari terakhir (termasuk hari ini)
        $chartLabelsHarian = [];
        $chartDataHarian = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            $label = date('d M', strtotime($tanggal));
            $chartLabelsHarian[] = $label;
            $found = $diagnosisPerHari->firstWhere('hari', $tanggal);
            $chartDataHarian[] = $found ? $found->jumlah : 0;
        }

        // Grafik Mingguan (7 minggu terakhir)
        $diagnosisPerMinggu = History::selectRaw("YEARWEEK(created_at, 1) as minggu, COUNT(*) as jumlah")
            ->where('created_at', '>=', now()->subWeeks(6)->startOfWeek())
            ->groupBy('minggu')
            ->orderBy('minggu', 'asc')
            ->get();

        $chartLabelsMingguan = [];
        $chartDataMingguan = [];
        for ($i = 6; $i >= 0; $i--) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $endOfWeek = now()->subWeeks($i)->endOfWeek();
            $minggu = $startOfWeek->format('oW'); // o = ISO year, W = ISO week number
            // YEARWEEK in MySQL returns like 202401, so we need to match
            $mysqlYearWeek = $startOfWeek->format('o') . $startOfWeek->format('W');
            $label = $startOfWeek->format('d M') . ' - ' . $endOfWeek->format('d M');
            $chartLabelsMingguan[] = $label;
            $found = $diagnosisPerMinggu->first(function ($item) use ($startOfWeek) {
                // YEARWEEK in MySQL: year + week number, e.g. 202401
                return $item->minggu == $startOfWeek->format('o') . $startOfWeek->format('W');
            });
            $chartDataMingguan[] = $found ? $found->jumlah : 0;
        }

        // Grafik Bulanan (12 bulan terakhir)
        $diagnosisPerBulan = History::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as jumlah")
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

        $chartLabelsBulanan = [];
        $chartDataBulanan = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan = now()->subMonths($i)->format('Y-m');
            $label = date('M Y', strtotime($bulan . '-01'));
            $chartLabelsBulanan[] = $label;
            $found = $diagnosisPerBulan->firstWhere('bulan', $bulan);
            $chartDataBulanan[] = $found ? $found->jumlah : 0;
        }

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
            'chartLabelsHarian',
            'chartDataHarian',
            'chartLabelsMingguan',
            'chartDataMingguan',
            'chartLabelsBulanan',
            'chartDataBulanan',
            'recentDiagnoses'
        ));
    }
}

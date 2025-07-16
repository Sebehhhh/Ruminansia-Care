<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Disease;
use App\Models\Symptom;
use App\Models\Rule;
use App\Models\History;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // Top 5 diseases - simple approach
        $topDiseases = History::with('disease:id,name')
            ->get()
            ->groupBy('disease_id')
            ->map(function ($histories) {
                return [
                    'disease' => $histories->first()->disease,
                    'total' => $histories->count()
                ];
            })
            ->sortByDesc('total')
            ->take(5)
            ->values();

        // Average confidence by disease - simple approach
        $confidenceByDisease = History::with('disease:id,name')
            ->get()
            ->groupBy('disease_id')
            ->map(function ($histories) {
                return [
                    'disease' => $histories->first()->disease,
                    'avg_confidence' => $histories->avg('confidence')
                ];
            })
            ->sortByDesc('avg_confidence')
            ->take(5)
            ->values();

        // User activity analytics - simple approach
        $userActivity = History::with('user:id,name')
            ->whereNotNull('user_id')
            ->get()
            ->groupBy('user_id')
            ->map(function ($histories) {
                return [
                    'user' => $histories->first()->user,
                    'diagnosis_count' => $histories->count(),
                    'avg_confidence' => $histories->avg('confidence')
                ];
            })
            ->sortByDesc('diagnosis_count')
            ->take(10)
            ->values();

        // Symptom effectiveness (most used symptoms) - simplified
        $topSymptoms = collect();
        
        try {
            $historyRecords = History::whereNotNull('selected_symptoms')
                ->where('selected_symptoms', '!=', '')
                ->get();
            
            $allSymptoms = [];
            foreach ($historyRecords as $record) {
                if (is_array($record->selected_symptoms)) {
                    $allSymptoms = array_merge($allSymptoms, array_keys($record->selected_symptoms));
                }
            }
            
            $symptomCounts = collect($allSymptoms)->countBy()->sortDesc()->take(5);
            
            if ($symptomCounts->isNotEmpty()) {
                $symptomIds = $symptomCounts->keys()->toArray();
                $symptoms = Symptom::whereIn('id', $symptomIds)->get()->keyBy('id');
                $topSymptoms = $symptomCounts->map(function ($count, $id) use ($symptoms) {
                    return [
                        'name' => $symptoms[$id]->name ?? 'Unknown Symptom',
                        'count' => $count
                    ];
                });
            }
        } catch (\Exception $e) {
            $topSymptoms = collect();
        }

        // System performance metrics - simplified
        $totalHistories = History::count();
        $systemMetrics = [
            'avg_confidence' => History::avg('confidence') ?? 0,
            'total_sessions' => $totalHistories,
            'unique_users' => History::distinct('user_id')->count('user_id'),
            'diseases_detected' => History::distinct('disease_id')->count('disease_id'),
            'high_confidence_rate' => $totalHistories > 0 ? (History::where('confidence', '>=', 0.8)->count() / $totalHistories * 100) : 0
        ];

        // Disease by animal type for donut chart - simplified
        $diseaseByAnimal = History::with('animal:id,name')
            ->get()
            ->groupBy('animal_id')
            ->map(function ($histories) {
                return [
                    'animal' => $histories->first()->animal,
                    'count' => $histories->count()
                ];
            })
            ->values();

        // Recent diagnoses
        $recentDiagnoses = History::with(['user', 'animal', 'disease'])
            ->latest()
            ->take(10)
            ->get();

        // Low confidence diagnoses (alerts)
        $lowConfidenceDiagnoses = History::where('confidence', '<', 0.6)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

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
            'recentDiagnoses',
            'topDiseases',
            'confidenceByDisease',
            'userActivity',
            'topSymptoms',
            'systemMetrics',
            'diseaseByAnimal',
            'lowConfidenceDiagnoses'
        ));
    }
}

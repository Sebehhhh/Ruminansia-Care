@extends('layouts.master')
@section('title', 'Dashboard')

@section('content')
<style>
    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 30px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.05); opacity: 0.8; }
    }
    .welcome-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .welcome-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    .welcome-icon {
        font-size: 4rem;
        opacity: 0.3;
    }

    /* Modern Stats Cards */
    .stat-card {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        border-radius: 15px;
        background: white;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.1) !important;
    }
    .stat-icon-modern {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        position: relative;
        z-index: 2;
    }
    .stat-overlay {
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }
    .stat-number {
        font-size: 2.5rem !important;
        font-weight: 700;
        line-height: 1;
    }
    .stat-label {
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .progress-sm {
        height: 4px;
        border-radius: 2px;
        background-color: #f8f9fa;
    }
    .progress-bar {
        border-radius: 2px;
        transition: width 0.6s ease;
    }

    /* Enhanced Chart Card */
    .chart-card, .table-card {
        border-radius: 15px;
        overflow: hidden;
    }
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .chart-container {
        position: relative;
        padding: 20px 0;
    }

    /* Enhanced Table */
    .modern-table {
        font-size: 0.9rem;
    }
    .modern-table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 15px;
        background: #f8f9fa;
    }
    .modern-table td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f4;
    }
    .table-row-hover:hover {
        background-color: #f8f9fb;
        transition: all 0.2s ease;
    }
    .empty-state {
        padding: 20px;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .empty-state .icon-lg {
        font-size: 2.5rem;
        display: block;
    }

    /* Color Enhancements */
    .flat-color-1-bg { background: linear-gradient(135deg, #00c292 0%, #00b894 100%); }
    .flat-color-2-bg { background: linear-gradient(135deg, #ab8ce4 0%, #9b59b6 100%); }
    .flat-color-3-bg { background: linear-gradient(135deg, #03a9f3 0%, #0984e3 100%); }
    .flat-color-4-bg { background: linear-gradient(135deg, #fb9678 0%, #e17055 100%); }

    /* Performance Cards */
    .performance-card {
        transition: all 0.3s ease;
        border-radius: 15px;
    }
    .performance-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.1) !important;
    }
    .performance-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto;
    }
    .performance-value {
        font-size: 2rem !important;
        font-weight: 700;
    }
    .performance-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Analytics Cards */
    .analytics-card {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        min-height: 300px;
        max-height: 400px;
    }
    .analytics-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    }
    .analytics-card .card-body {
        padding: 1.5rem;
        height: calc(100% - 60px);
        overflow-y: auto;
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
    }
    .bg-gradient-warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    }

    /* Disease List */
    .disease-list {
        max-height: 240px;
        overflow-y: auto;
    }
    .disease-item {
        padding: 8px;
        border-radius: 6px;
        transition: all 0.2s ease;
        margin-bottom: 8px;
    }
    .disease-item:hover {
        background-color: #f8f9fa;
    }
    .disease-rank {
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
        margin-right: 15px;
    }
    .disease-name {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 2px;
    }
    .disease-count {
        font-size: 0.8rem;
        color: #6c757d;
    }
    .disease-progress {
        width: 60px;
    }

    /* Symptom List */
    .symptom-list {
        max-height: 240px;
        overflow-y: auto;
    }
    .symptom-item {
        padding: 8px;
        border-radius: 6px;
        transition: all 0.2s ease;
        margin-bottom: 8px;
    }
    .symptom-item:hover {
        background-color: #f8f9fa;
    }
    .symptom-icon {
        margin-right: 12px;
        font-size: 1.2rem;
    }
    .symptom-name {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 2px;
    }
    .symptom-usage {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* Confidence List */
    .confidence-list {
        max-height: 240px;
        overflow-y: auto;
    }
    .confidence-item {
        padding: 8px;
        border-radius: 6px;
        margin-bottom: 8px;
    }
    .confidence-disease {
        font-weight: 600;
        font-size: 0.9rem;
    }
    .confidence-value {
        font-weight: 700;
        font-size: 0.9rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .welcome-banner {
            flex-direction: column;
            text-align: center;
        }
        .welcome-icon {
            margin-top: 20px;
        }
        .stat-number {
            font-size: 2rem !important;
        }
        .analytics-card {
            min-height: 250px;
            max-height: 350px;
        }
        .disease-list, .symptom-list, .confidence-list {
            max-height: 200px;
        }
    }
    
    /* Scrollbar Styling */
    .disease-list::-webkit-scrollbar,
    .symptom-list::-webkit-scrollbar,
    .confidence-list::-webkit-scrollbar {
        width: 4px;
    }
    .disease-list::-webkit-scrollbar-track,
    .symptom-list::-webkit-scrollbar-track,
    .confidence-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }
    .disease-list::-webkit-scrollbar-thumb,
    .symptom-list::-webkit-scrollbar-thumb,
    .confidence-list::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 2px;
    }
    .disease-list::-webkit-scrollbar-thumb:hover,
    .symptom-list::-webkit-scrollbar-thumb:hover,
    .confidence-list::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
<div class="animated fadeIn">
    <div class="row">
        @php
        $cards = [
        ['icon' => 'pe-7s-users', 'color' => 'flat-color-1', 'count' => $jumlahUsers, 'label' => 'Pengguna'],
        ['icon' => 'pe-7s-science', 'color' => 'flat-color-2', 'count' => $jumlahHewan, 'label' => 'Jenis Hewan'],
        ['icon' => 'pe-7s-attention', 'color' => 'flat-color-3', 'count' => $jumlahPenyakit, 'label' => 'Penyakit'],
        ['icon' => 'pe-7s-bandaid', 'color' => 'flat-color-4', 'count' => $jumlahGejala, 'label' => 'Gejala'],
        ['icon' => 'pe-7s-network', 'color' => 'flat-color-1', 'count' => $jumlahRule, 'label' => 'Rules'],
        ['icon' => 'pe-7s-note2', 'color' => 'flat-color-2', 'count' => $jumlahDiagnosa, 'label' => 'Diagnosa'],
        ];
        
        $performanceCards = [
        ['icon' => 'pe-7s-graph1', 'color' => 'flat-color-3', 'value' => number_format($systemMetrics['avg_confidence'] * 100, 1) . '%', 'label' => 'Akurasi Rata-rata'],
        ['icon' => 'pe-7s-alarm', 'color' => 'flat-color-4', 'value' => $lowConfidenceDiagnoses, 'label' => 'Alert Confidence Rendah'],
        ['icon' => 'pe-7s-check', 'color' => 'flat-color-1', 'value' => number_format($systemMetrics['high_confidence_rate'], 1) . '%', 'label' => 'Diagnosis Akurat'],
        ['icon' => 'pe-7s-target', 'color' => 'flat-color-2', 'value' => $systemMetrics['diseases_detected'], 'label' => 'Penyakit Terdeteksi'],
        ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-md-4 col-lg-4 mb-4">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon dib {{ $card['color'] }} me-3">
                        <i class="{{ $card['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="h4 mb-0">{{ $card['count'] }}</div>
                        <small class="text-muted">{{ $card['label'] }}</small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Performance Metrics Cards --}}
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3"><i class="pe-7s-graph1 me-2"></i>Performance Metrics</h5>
        </div>
        @foreach ($performanceCards as $card)
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="performance-card card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="performance-icon {{ $card['color'] }}-bg mb-3">
                        <i class="{{ $card['icon'] }} text-white"></i>
                    </div>
                    <div class="performance-value h3 mb-1 fw-bold {{ $card['color'] }}">{{ $card['value'] }}</div>
                    <div class="performance-label text-muted small">{{ $card['label'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Analytics Row --}}
    <div class="row mb-4">
        {{-- Disease Distribution Donut Chart --}}
        <div class="col-lg-4 mb-4">
            <div class="card analytics-card border-0 shadow-sm">
                <div class="card-header bg-gradient-success text-white border-0">
                    <h5 class="mb-0 text-white">🐄 Distribusi Hewan</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    @if($diseaseByAnimal->count() > 0)
                    <div class="chart-wrapper" style="width: 100%; height: 240px;">
                        <canvas id="animalDistributionChart"></canvas>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="pe-7s-science icon-lg text-muted mb-2"></i>
                        <p class="mb-0">Belum ada data distribusi hewan.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Top Diseases --}}
        <div class="col-lg-4 mb-4">
            <div class="card analytics-card border-0 shadow-sm">
                <div class="card-header bg-gradient-danger text-white border-0">
                    <h5 class="mb-0 text-white">🦠 Penyakit Teratas</h5>
                </div>
                <div class="card-body">
                    <div class="disease-list">
                        @forelse($topDiseases as $index => $disease)
                        <div class="disease-item d-flex align-items-center mb-3">
                            <div class="disease-rank">{{ $index + 1 }}</div>
                            <div class="disease-info flex-grow-1">
                                <div class="disease-name">{{ $disease['disease']->name ?? 'Unknown' }}</div>
                                <div class="disease-count">{{ $disease['total'] }} kasus</div>
                            </div>
                            <div class="disease-progress">
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-danger" style="width: {{ $topDiseases->count() > 0 ? ($disease['total'] / $topDiseases->first()['total']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted empty-state">
                            <i class="pe-7s-attention icon-lg text-muted mb-2"></i>
                            <p class="mb-0">Belum ada data penyakit.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Symptoms --}}
        <div class="col-lg-4 mb-4">
            <div class="card analytics-card border-0 shadow-sm">
                <div class="card-header bg-gradient-warning text-white border-0">
                    <h5 class="mb-0 text-white">🩺 Gejala Populer</h5>
                </div>
                <div class="card-body">
                    <div class="symptom-list">
                        @forelse($topSymptoms->take(5) as $symptom)
                        <div class="symptom-item d-flex align-items-center mb-3">
                            <div class="symptom-icon">
                                <i class="pe-7s-bandaid text-warning"></i>
                            </div>
                            <div class="symptom-info flex-grow-1">
                                <div class="symptom-name">{{ $symptom['name'] }}</div>
                                <div class="symptom-usage">{{ $symptom['count'] }} kali dipilih</div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted empty-state">
                            <i class="pe-7s-bandaid icon-lg text-muted mb-2"></i>
                            <p class="mb-0">Belum ada data gejala.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- User Activity & Confidence Analytics --}}
    <div class="row mb-4">
        {{-- User Activity --}}
        <div class="col-lg-6 mb-4">
            <div class="card analytics-card border-0 shadow-sm">
                <div class="card-header bg-gradient-info text-white border-0">
                    <h5 class="mb-0 text-white">👥 Aktivitas Pengguna</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Pengguna</th>
                                    <th>Diagnosis</th>
                                    <th>Rata-rata Akurasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userActivity->take(5) as $activity)
                                <tr>
                                    <td>{{ $activity['user']->name ?? 'Unknown' }}</td>
                                    <td><span class="badge bg-primary">{{ $activity['diagnosis_count'] }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                <div class="progress-bar" style="width: {{ $activity['avg_confidence'] * 100 }}%"></div>
                                            </div>
                                            <small>{{ number_format($activity['avg_confidence'] * 100, 1) }}%</small>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        <div class="empty-state">
                                            <i class="pe-7s-users icon-lg text-muted mb-2"></i>
                                            <p class="mb-0">Belum ada aktivitas pengguna.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Confidence by Disease --}}
        <div class="col-lg-6 mb-4">
            <div class="card analytics-card border-0 shadow-sm">
                <div class="card-header bg-gradient-secondary text-white border-0">
                    <h5 class="mb-0 text-white">📊 Tingkat Kepercayaan</h5>
                </div>
                <div class="card-body">
                    <div class="confidence-list">
                        @forelse($confidenceByDisease->take(5) as $confidence)
                        <div class="confidence-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="confidence-disease">{{ $confidence['disease']->name ?? 'Unknown' }}</span>
                                <span class="confidence-value">{{ number_format($confidence['avg_confidence'] * 100, 1) }}%</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar 
                                    @if($confidence['avg_confidence'] >= 0.8) bg-success 
                                    @elseif($confidence['avg_confidence'] >= 0.6) bg-warning 
                                    @else bg-danger @endif" 
                                    style="width: {{ $confidence['avg_confidence'] * 100 }}%">
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted empty-state">
                            <i class="pe-7s-graph1 icon-lg text-muted mb-2"></i>
                            <p class="mb-0">Belum ada data kepercayaan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart Grafik Diagnosa --}}
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Statistik Diagnosa</h4>
                <div>
                    <select id="chartFilter" class="form-select form-select-sm" style="width:auto;display:inline-block;">
                        <option value="harian" selected>Harian</option>
                        <option value="mingguan">Mingguan</option>
                        <option value="bulanan">Bulanan</option>
                    </select>
                </div>
            </div>
            <canvas id="diagnosisChart" height="100"></canvas>
        </div>
    </div>

    {{-- Tabel Riwayat Diagnosa Terbaru --}}
    <div class="card">
        <div class="card-body">
            <h4 class="mb-3">Riwayat Diagnosa Terbaru</h4>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Pasien</th>
                            <th>Hewan</th>
                            <th>Penyakit</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentDiagnoses as $index => $diagnosis)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $diagnosis->user->name ?? 'Guest' }}</td>
                            <td>{{ $diagnosis->animal->name }}</td>
                            <td>{{ $diagnosis->disease->name }}</td>
                            <td>{{ $diagnosis->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data diagnosa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ChartJS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data awal dari server
    const chartDataSets = {
        harian: {
            labels: {!! json_encode($chartLabelsHarian ?? []) !!},
            data: {!! json_encode($chartDataHarian ?? []) !!}
        },
        mingguan: {
            labels: {!! json_encode($chartLabelsMingguan ?? []) !!},
            data: {!! json_encode($chartDataMingguan ?? []) !!}
        },
        bulanan: {
            labels: {!! json_encode($chartLabelsBulanan ?? []) !!},
            data: {!! json_encode($chartDataBulanan ?? []) !!}
        }
    };

    let currentFilter = 'harian';

    const ctx = document.getElementById('diagnosisChart').getContext('2d');
    let diagnosisChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartDataSets[currentFilter].labels,
            datasets: [{
                label: 'Jumlah Diagnosa',
                data: chartDataSets[currentFilter].data,
                fill: true,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.4,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    document.getElementById('chartFilter').addEventListener('change', function() {
        currentFilter = this.value;
        diagnosisChart.data.labels = chartDataSets[currentFilter].labels;
        diagnosisChart.data.datasets[0].data = chartDataSets[currentFilter].data;
        diagnosisChart.update();
    });

    // Animal Distribution Donut Chart
    @if($diseaseByAnimal->count() > 0)
    const animalCtx = document.getElementById('animalDistributionChart').getContext('2d');
    const animalData = {
        labels: {!! json_encode($diseaseByAnimal->map(function($item) { return $item['animal']->name ?? 'Unknown'; })) !!},
        datasets: [{
            data: {!! json_encode($diseaseByAnimal->map(function($item) { return $item['count']; })) !!},
            backgroundColor: [
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(255, 205, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(255, 159, 64, 0.8)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 205, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 2
        }]
    };

    const animalChart = new Chart(animalCtx, {
        type: 'doughnut',
        data: animalData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '60%',
            animation: {
                animateRotate: true,
                animateScale: true
            }
        }
    });
    @endif
</script>
@endsection
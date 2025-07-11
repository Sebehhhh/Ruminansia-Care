@extends('layouts.master')
@section('title', 'Dashboard')

@section('content')
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
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.3,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointRadius: 4
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
</script>
@endsection
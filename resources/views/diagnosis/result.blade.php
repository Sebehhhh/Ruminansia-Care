@extends('layouts.master')

@section('title', 'Hasil Diagnosis Ternak Ruminansia')

@section('content')
<style>
    .result-badge {
        font-size: 1.5rem;
        font-weight: bold;
        padding: 0.5rem 1.5rem;
        border-radius: 2rem;
        background: #e8f5e9;
        color: #2e7d32;
        display: inline-block;
        margin-bottom: 1rem;
    }
    .confidence-bar {
        background: #e3f2fd;
        border-radius: 10px;
        overflow: hidden;
        height: 1.25rem;
        margin-bottom: 0.75rem;
    }
    .confidence-inner {
        background: #1976d2;
        height: 100%;
        color: #fff;
        text-align: center;
        font-size: 1rem;
        line-height: 1.25rem;
        transition: width 0.6s;
    }
    .possible-list {
        list-style: decimal inside;
        padding-left: 0;
    }
</style>
<div class="container py-4">
    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif
    <div class="text-center mb-5">
        <h3 class="mb-2 text-success"><i class="fas fa-stethoscope me-2"></i>Hasil Diagnosis Ternak Ruminansia</h3>
        <p class="lead text-secondary">Berikut hasil analisis berdasarkan gejala dan keyakinan yang Anda input:</p>
    </div>

    @if(isset($top) && $top)
    <div class="card shadow mb-4 border-success">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Diagnosis Utama</h5>
        </div>
        <div class="card-body text-center">
            <div class="result-badge">
                <i class="fas fa-virus me-2"></i>{{ $top['disease']->name }}
            </div>
            <div class="mb-2 text-muted">{{ $top['disease']->description ?? '-' }}</div>
            <div class="mb-3">
                <div class="confidence-bar">
                    <div class="confidence-inner" style="width:{{ max(0, min(100, $top['confidence'] * 100)) }}%;">
                        <span>{{ round($top['confidence'] * 100, 2) }}%</span>
                    </div>
                </div>
                <div class="fw-bold small text-primary">Tingkat Keyakinan Diagnosis</div>
            </div>
            @if(!empty($top['disease']->recommendation))
            <div class="alert alert-info mt-3 text-start mx-auto" style="max-width: 550px;">
                <i class="fas fa-lightbulb me-2"></i>
                <b>Saran Penanganan:</b> {!! nl2br(e($top['disease']->recommendation)) !!}
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow border-primary">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">Ranking Kemungkinan Penyakit</h6>
                </div>
                <div class="card-body">
                    @if(isset($results) && count($results))
                        <ol class="possible-list">
                            @foreach($results as $rank => $result)
                                <li class="{{ $rank == 0 ? 'fw-bold text-success' : '' }}">
                                    {{ $result['disease']->name }}
                                    <span class="badge bg-light text-dark ms-2">
                                        {{ round($result['confidence'] * 100, 2) }}%
                                    </span>
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <div class="text-muted">Tidak ada data kemungkinan penyakit.</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">Gejala yang Dipilih</h6>
                </div>
                <div class="card-body">
                    @if(isset($inputSymptoms) && is_array($inputSymptoms) && count($inputSymptoms))
                        <ul class="possible-list">
                            @foreach($inputSymptoms as $symptomId => $value)
                                @php
                                    $symptom = \App\Models\Symptom::find($symptomId);
                                    $label = '';
                                    switch (floatval($value)) {
                                        case 1.0: $label = 'Sangat Yakin'; break;
                                        case 0.8: $label = 'Yakin'; break;
                                        case 0.6: $label = 'Cukup Yakin'; break;
                                        case 0.4: $label = 'Tidak Yakin'; break;
                                        case 0.2: $label = 'Sangat Tidak Yakin'; break;
                                        default: $label = 'Tidak Tahu';
                                    }
                                @endphp
                                <li>
                                    <b>{{ $symptom ? $symptom->name : 'Gejala #' . $symptomId }}</b>
                                    <span class="badge bg-info text-dark ms-2">
                                        {{ $label }} ({{ $value }})
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-muted">Tidak ada gejala yang dipilih.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('diagnosis') }}" class="btn btn-outline-primary btn-lg shadow">
            <i class="fas fa-undo me-2"></i>Lakukan Diagnosis Lagi
        </a>
        <a href="{{ route('history.index') }}" class="btn btn-outline-success btn-lg shadow ms-2">
            <i class="fas fa-history me-2"></i>Riwayat Diagnosis
        </a>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
@endsection

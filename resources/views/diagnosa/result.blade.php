@extends('welcome.app')
@section('title', 'Hasil Diagnosa Penyakit Ternak')

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

<section class="section" id="hasil">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="text-success"><i class="fas fa-stethoscope me-2"></i>Hasil Diagnosa</h2>
            <p class="lead text-muted">Berikut adalah hasil analisa berdasarkan gejala dan tingkat keyakinan yang Anda masukkan.</p>
        </div>

        @if(isset($top) && $top)
        <div class="card shadow mb-4 border-success">
            <div class="card-header bg-success text-white text-center">
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
                    <div class="fw-bold small text-primary">Tingkat Keyakinan</div>
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
                                        $label = match(floatval($value)) {
                                            1.0 => 'Sangat Yakin',
                                            0.8 => 'Yakin',
                                            0.6 => 'Cukup Yakin',
                                            0.4 => 'Tidak Yakin',
                                            0.2 => 'Sangat Tidak Yakin',
                                            default => 'Tidak Tahu',
                                        };
                                    @endphp
                                    <li>
                                        <b>{{ $symptom ? $symptom->name : 'Gejala #' . $symptomId }}</b>
                                        <span class="badge bg-info text-dark ms-2">
                                            {{ $label }} ({{ $value }})
                                        </span>
                                        @if($symptom)
                                            <div class="small text-muted mt-1">
                                                <b>Kode:</b> {{ $symptom->code ?? '-' }}<br>
                                                <b>Deskripsi:</b> {{ $symptom->description ?? '-' }}
                                            </div>
                                        @endif
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
            <a href="{{ route('diagnosa.index') }}" class="btn btn-outline-primary btn-lg shadow">
                <i class="fas fa-undo me-2"></i>Diagnosa Ulang
            </a>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
@endsection
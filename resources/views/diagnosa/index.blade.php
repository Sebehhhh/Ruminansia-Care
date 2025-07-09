@extends('welcome.app')
@section('title', 'Diagnosa Penyakit Ternak')

@section('content')
<style>
    .option-box {
        cursor: pointer;
        transition: background-color 0.2s, color 0.2s, border-color 0.2s;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        display: inline-block;
        text-align: center;
        user-select: none;
    }

    .option-box-container input[type="radio"] {
        display: none;
    }

    .option-box-container input[type="radio"]:checked+.option-box {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .option-box:hover {
        background-color: #e2e6ea;
        border-color: #adb5bd;
    }

    .option-box-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
    }
</style>

<section class="section" id="diagnosa">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Form Diagnosa Penyakit Ternak</h2>
            <p>Silakan pilih jenis ternak dan gejala yang muncul untuk melihat kemungkinan penyakit.</p>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Form pilih hewan --}}
        <form method="GET" action="{{ route('diagnosa.index') }}">
            <div class="mb-4">
                <label for="animal_id" class="form-label">Pilih Hewan</label>
                <select name="animal_id" id="animal_id" class="form-select" onchange="this.form.submit()" required>
                    <option value="">-- Pilih Hewan --</option>
                    @foreach($animals as $animal)
                    <option value="{{ $animal->id }}" {{ request('animal_id')==$animal->id ? 'selected' : '' }}>
                        {{ $animal->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </form>

        {{-- Form pilih gejala --}}
        @if(request('animal_id') && count($symptoms) > 0)
        <form method="POST" action="{{ route('diagnosa.process') }}">
            @csrf
            <input type="hidden" name="animal_id" value="{{ request('animal_id') }}">

            <div class="card mb-4 shadow-sm border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Pilih Gejala dan Tingkat Keyakinan</h5>
                </div>
                <div class="card-body">
                    @php
                    $fuzzyValues = [0, 0.2, 0.4, 0.6, 0.8, 1.0];
                    $fuzzyLabels = [
                    'Tidak Tahu',
                    'Sangat Tidak Yakin',
                    'Tidak Yakin',
                    'Cukup Yakin',
                    'Yakin',
                    'Sangat Yakin'
                    ];
                    @endphp

                    @foreach ($symptoms as $symptom)
                    <div class="mb-4 p-3 border rounded bg-light">
                        <p class="fw-bold mb-2">{{ $loop->iteration }}. {{ $symptom->name }} <span
                                class="text-muted">({{ $symptom->code }})</span></p>
                        <div class="option-box-container">
                            @foreach ($fuzzyValues as $key => $value)
                            <label class="option-box-container">
                                <input type="radio" name="symptoms[{{ $symptom->id }}]" value="{{ $value }}" {{
                                    old('symptoms.' . $symptom->id, '0') == $value ? 'checked' : '' }}>
                                <span class="option-box">
                                    {{ $fuzzyLabels[$key] }} <br> ({{ $value }})
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center mb-5">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-stethoscope me-2"></i>Proses Diagnosa
                </button>
            </div>
        </form>
        @elseif(request('animal_id'))
        <div class="alert alert-warning text-center">Tidak ditemukan gejala untuk hewan yang dipilih.</div>
        @endif
    </div>
</section>

{{-- Font Awesome (untuk ikon jika belum include di layout) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>
@endsection
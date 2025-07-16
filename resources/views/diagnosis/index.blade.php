@extends('layouts.master')

@section('title', 'Diagnosis Ternak Ruminansia')

@section('content')
<style>
    /* Highlight selected option-box */
    .option-box {
        cursor: pointer;
        transition: background-color 0.2s, color 0.2s, border-color 0.2s;
        border: 1px solid #ced4da;
        /* Default border */
        border-radius: 0.375rem;
        /* Bootstrap's default border-radius for inputs */
        padding: 0.5rem 1rem;
        display: inline-block;
        text-align: center;
        user-select: none;
        /* Prevent text selection */
    }

    /* Hide the actual radio input */
    .option-box-container input[type="radio"] {
        display: none;
    }

    /* Style when the radio input is checked */
    .option-box-container input[type="radio"]:checked+.option-box {
        background-color: #0d6efd;
        /* Primary blue */
        color: white;
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        /* Focus-like shadow */
    }

    /* Hover effect for option-box */
    .option-box:hover {
        background-color: #e2e6ea;
        /* Light grey on hover */
        border-color: #adb5bd;
    }

    .option-box-container {
        display: flex;
        flex-wrap: wrap;
        /* Allow wrapping on small screens */
        gap: 0.5rem;
        /* Space between options */
        justify-content: center;
        /* Center the options */
    }
</style>
<div class="container py-4">
    @if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    html: "{!! implode('<br>', $errors->all()) !!}"
                });
            });
    </script>
    @endif
    
    @if (session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: "{{ session('error') }}"
                });
            });
    </script>
    @endif
    <h3 class="mb-4 text-center text-primary">Diagnosis Ternak Ruminansia</h3>
    <p class="lead text-center mb-5">Silakan pilih kategori ruminansia terlebih dahulu, kemudian pilih gejala yang
        dialami ternak Anda dan tentukan tingkat keyakinan untuk setiap gejala.</p>

    <form method="GET" action="{{ route('diagnosis') }}">
        <div class="card mb-4 shadow-sm border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-paw me-2"></i>Pilih Kategori Ruminansia</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="animal_id" class="form-label mb-2">Jenis Ruminansia:</label>
                    <select name="animal_id" id="animal_id" class="form-select form-select-lg"
                        onchange="this.form.submit()" required>
                        <option value="">-- Pilih Hewan --</option>
                        @foreach ($animals as $animal)
                        <option value="{{ $animal->id }}" {{ request('animal_id')==$animal->id ? 'selected' : '' }}>
                            {{ $animal->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('animal_id')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </form>

    @if (request('animal_id') && count($symptoms) > 0)
    <form method="POST" action="{{ route('diagnosis.process') }}">
        @csrf
        <input type="hidden" name="animal_id" value="{{ request('animal_id') }}">
        {{-- Tampilkan pilihan gejala --}}
        <div class="card mb-4 shadow-sm border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Pilih Gejala dan Tingkat Keyakinan</h5>
            </div>
            <div class="card-body">
                <!-- Informasi paginasi -->
                <div class="alert alert-info mb-3">
                    <p class="mb-0">Menampilkan {{ $symptoms->count() }} dari {{ $totalSymptoms }} gejala (Halaman {{ $symptoms->currentPage() }} dari {{ $symptoms->lastPage() }})</p>
                </div>
                
                @foreach ($symptoms as $symptom)
                <div class="mb-4 p-3 border rounded-3 bg-light">
                    <p class="fw-bold mb-3">{{ ($symptoms->currentPage() - 1) * 10 + $loop->iteration }}. {{ $symptom->name }} ({{ $symptom->code }})</p>
                    <div class="option-box-container">
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
                        @foreach ($fuzzyValues as $key => $value)
                        <label class="option-box-container">
                            <input type="radio" name="symptoms[{{ $symptom->id }}]" value="{{ $value }}" {{
                                old('symptoms.' . $symptom->id, 0) == $value ? 'checked' : '' }}>
                            <span class="option-box">
                                {{ $fuzzyLabels[$key] }} ({{ $value }})
                            </span>
                        </label>
                        @endforeach
                    </div>
                    @error('symptoms.' . $symptom->id)
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                @endforeach
                
                <!-- Pagination links -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $symptoms->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>

        <div class="text-center mb-5">
            <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                <i class="fas fa-stethoscope me-2"></i>Lakukan Diagnosis
            </button>
        </div>
    </form>
    @elseif(request('animal_id') && count($symptoms) == 0)
    <div class="alert alert-warning text-center shadow-sm" role="alert">
        <i class="fas fa-info-circle me-2"></i>Tidak ada gejala terdaftar untuk ruminansia ini.
    </div>
    @endif

</div>

{{-- Font Awesome for icons --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" crossorigin="anonymous"></script>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
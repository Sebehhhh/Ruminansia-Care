@extends('layouts.master')
@section('title', 'Edit Gejala')

@section('content')
<div class="container">
    @if (session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
            });
        });
    </script>
    @endif
    <h3 class="mb-4">Tambah Gejala</h3>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('animal_symptoms.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="animal_id" class="form-label">Nama Hewan</label>
                    <select name="animal_id" id="animal_id"
                        class="form-control @error('animal_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Hewan --</option>
                        @foreach ($animals as $animal)
                        <option value="{{ $animal->id }}" {{ old('animal_id')==$animal->id ? 'selected' : '' }}>
                            {{ $animal->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('animal_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Pilih Gejala</label>
                    <div class="@error('symptom_ids') is-invalid @enderror">
                        @foreach ($symptoms as $symptom)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="symptom_ids[]"
                                value="{{ $symptom->id }}" id="symptom_{{ $symptom->id }}" {{
                                (is_array(old('symptom_ids')) && in_array($symptom->id, old('symptom_ids'))) ? 'checked'
                            : '' }}>
                            <label class="form-check-label" for="symptom_{{ $symptom->id }}">
                                {{ $symptom->name }}
                            </label>
                        </div>
                        @endforeach
                        @error('symptom_ids')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('animal_symptoms.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
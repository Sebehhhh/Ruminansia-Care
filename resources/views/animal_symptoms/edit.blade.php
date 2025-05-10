@extends('layouts.master')
@section('title', 'Edit Gejala')

@section('content')
    <div class="container">
        <h3 class="mb-4">Edit Gejala Hewan: {{ $animal->name }}</h3>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('animal_symptoms.update', $animal->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Pilih Gejala</label>
                        <div class="@error('symptom_ids') is-invalid @enderror">
                            @foreach ($symptoms as $symptom)
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="symptom_ids[]"
                                           value="{{ $symptom->id }}"
                                           id="symptom_{{ $symptom->id }}"
                                           {{ in_array($symptom->id, $selectedSymptoms) ? 'checked' : '' }}>
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
@endsection

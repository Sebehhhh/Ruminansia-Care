@extends('layouts.master')
@section('title', 'Edit Penyakit')

@section('content')
    <div class="container">
        <h3 class="mb-4">Edit Penyakit Hewan: {{ $animal->name }}</h3>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('animal_diseases.update', $animal->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Pilih Penyakit</label>
                        <div class="@error('disease_ids') is-invalid @enderror">
                            @foreach ($diseases as $disease)
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="disease_ids[]"
                                           value="{{ $disease->id }}"
                                           id="disease_{{ $disease->id }}"
                                           {{ in_array($disease->id, $selectedDiseases) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="disease_{{ $disease->id }}">
                                        {{ $disease->name }}
                                    </label>
                                </div>
                            @endforeach
                            @error('disease_ids')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('animal_diseases.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection

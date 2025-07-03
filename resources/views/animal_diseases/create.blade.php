@extends('layouts.master')
@section('title', 'Tambah Penyakit Hewan')

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
    <h3 class="mb-4">Tambah Penyakit Hewan</h3>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('animal_diseases.store') }}" method="POST">
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
                    <label class="form-label">Pilih Penyakit</label>
                    <div class="@error('disease_ids') is-invalid @enderror">
                        @foreach ($diseases as $disease)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="disease_ids[]"
                                value="{{ $disease->id }}" id="disease_{{ $disease->id }}" {{
                                (is_array(old('disease_ids')) && in_array($disease->id, old('disease_ids'))) ? 'checked'
                            : '' }}>
                            <label class="form-check-label" for="disease_{{ $disease->id }}">
                                {{ $disease->code ? $disease->code . ' - ' : '' }}{{ $disease->name }}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
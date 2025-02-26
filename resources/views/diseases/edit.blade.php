@extends('layouts.master')

@section('content')
    <div class="container">
        <h3 class="mb-4">Edit Penyakit</h3>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('diseases.update', $disease->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Penyakit</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $disease->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="3" required>{{ old('description', $disease->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="recommendation" class="form-label">Rekomendasi</label>
                        <textarea class="form-control @error('recommendation') is-invalid @enderror" id="recommendation" name="recommendation"
                            rows="3" required>{{ old('recommendation', $disease->recommendation) }}</textarea>
                        @error('recommendation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('diseases.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection

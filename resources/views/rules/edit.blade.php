@extends('layouts.master')

@section('content')
    <div class="container">
        <h3 class="mb-3">Edit Aturan</h3>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('rules.update', $rule->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="disease_id">Penyakit</label>
                        <select name="disease_id" class="form-control" required>
                            <option value="">-- Pilih Penyakit --</option>
                            @foreach ($diseases as $disease)
                                <option value="{{ $disease->id }}"
                                    {{ $rule->disease_id == $disease->id ? 'selected' : '' }}>
                                    {{ $disease->code ? $disease->code . ' - ' : '' }}{{ $disease->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="symptom_id">Gejala</label>
                        <select name="symptom_id" class="form-control" required>
                            <option value="">-- Pilih Gejala --</option>
                            @foreach ($symptoms as $symptom)
                                <option value="{{ $symptom->id }}"
                                    {{ $rule->symptom_id == $symptom->id ? 'selected' : '' }}>
                                    {{ $symptom->code ? $symptom->code . ' - ' : '' }}{{ $symptom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mb">Measure of Belief (MB)</label>
                        <input type="number" step="0.01" min="0" max="1" name="mb"
                            value="{{ $rule->mb }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="md">Measure of Disbelief (MD)</label>
                        <input type="number" step="0.01" min="0" max="1" name="md"
                            value="{{ $rule->md }}" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection

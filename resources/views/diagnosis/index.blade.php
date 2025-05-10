@extends('layouts.master')

@section('title', 'Diagnosis Ternak Ruminansia')

@section('content')
<style>
    /* Highlight selected option-box */
    .option-box {
        cursor: pointer;
        transition: background-color 0.2s, color 0.2s;
    }

    .option-box+input:checked+.option-box,
    input:checked+.option-box {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
</style>
<div class="container ">
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
    <h3 class="mb-4">Diagnosis Ternak Ruminansia</h3>
    <p>Silakan pilih kategori ruminansia terlebih dahulu, kemudian pilih gejala yang dialami ternak Anda dan tentukan
        tingkat keyakinan untuk setiap gejala.</p>

    <form method="GET" action="{{ route('diagnosis') }}">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Pilih Kategori Ruminansia</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <select name="animal_id" class="form-select" onchange="this.form.submit()" required>
                        <option value="">-- Pilih Hewan --</option>
                        @foreach ($animals as $animal)
                        <option value="{{ $animal->id }}" {{ request('animal_id')==$animal->id ? 'selected' : '' }}>
                            {{ $animal->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </form>


    @endsection
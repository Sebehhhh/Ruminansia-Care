@extends('layouts.master')
@section('title', 'Daftar Gejala Hewan')

@section('content')
<div class="container">
    @if (session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
    @endif

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

    <h3 class="mb-4">Daftar Gejala Hewan</h3>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('animal_symptoms.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-1"></i> Tambah
        </a>

        <div class="d-flex flex-column flex-md-row justify-content-md-end gap-2 mx-2">
            {{-- Filter Form --}}
            <form method="GET" action="{{ route('animal_symptoms.index') }}" class="d-flex gap-2 mx-2">
                <select name="animal_id" class="form-select form-select-sm" style="min-width: 160px">
                    <option value="">-- Semua Hewan --</option>
                    @foreach ($animals as $animal)
                    <option value="{{ $animal->id }}" {{ request('animal_id')==$animal->id ? 'selected' : '' }}>
                        {{ $animal->name }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-outline-secondary btn-sm mx-2">Filter</button>
                <a href="{{ route('animal_symptoms.index') }}" class="btn btn-outline-dark btn-sm mx-2">Reset</a>
            </form>

            {{-- Edit Form --}}
            <form id="editForm" class="d-flex gap-2 mx-2">
                <select id="animalSelect" class="form-select form-select-sm" style="min-width: 160px;" required>
                    <option value="">-- Pilih Hewan --</option>
                    @foreach ($animals as $animal)
                    <option value="{{ $animal->id }}">{{ $animal->name }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-warning btn-sm" onclick="goToEdit()">
                    Edit Gejala
                </button>
            </form>
        </div>
    </div>

    <script>
        function goToEdit() {
            const animalId = document.getElementById('animalSelect').value;
            if (animalId) {
                window.location.href = `/animal_symptoms/${animalId}/edit`;
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Silakan pilih hewan terlebih dahulu!',
                });
            }
        }
    </script>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama Hewan</th>
                        <th>Gejala</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($animalSymptoms as $index => $animalSymptom)
                    <tr>
                        <td class="text-center">{{ $animalSymptoms->firstItem() + $index }}</td>
                        <td>{{ $animalSymptom->animal->name }}</td>
                        <td>
                            {{ $animalSymptom->symptom->code ? $animalSymptom->symptom->code . ' - ' : '' }}
                            {{ $animalSymptom->symptom->name }}
                        </td>
                        <td class="text-center">
                            <form id="delete-form-{{ $animalSymptom->id }}"
                                action="{{ route('animal_symptoms.destroy', $animalSymptom->id) }}" method="POST"
                                class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $animalSymptom->id }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Tidak ada data yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $animalSymptoms->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function () {
                    const id = this.getAttribute("data-id");
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data gejala akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-form-${id}`).submit();
                        }
                    });
                });
            });
        });
    </script>
</div>
@endsection
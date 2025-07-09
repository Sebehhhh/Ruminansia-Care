@extends('layouts.master')
@section('title', 'Daftar Penyakit Hewan')

@section('content')
<div class="container">
    <h3 class="mb-4">Daftar Penyakit Hewan</h3>

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

    {{-- Tombol + Filter --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-6 mb-2 mb-md-0">
            <a href="{{ route('animal_diseases.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus me-1"></i> Tambah
            </a>
        </div>
        <div class="col-md-6">
            <div class="d-flex flex-column flex-md-row justify-content-md-end gap-2">
                {{-- Filter Form --}}
                <form method="GET" action="{{ route('animal_diseases.index') }}" class="d-flex gap-2 mx-2">
                    <select name="animal_id" class="form-select form-select-sm mx-2" style="min-width: 160px">
                        <option value="">-- Semua Hewan --</option>
                        @foreach ($animals as $animal)
                        <option value="{{ $animal->id }}" {{ request('animal_id')==$animal->id ? 'selected' : '' }}>
                            {{ $animal->name }}
                        </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-outline-secondary btn-sm mx-2">Filter</button>
                    <a href="{{ route('animal_diseases.index') }}" class="btn btn-outline-dark btn-sm mx-2">Reset</a>
                </form>

                {{-- Edit Form --}}
                <form id="editForm" class="d-flex gap-2 mx-2">
                    <select id="animalSelect" class="form-select form-select-sm mx-2" style="min-width: 160px;"
                        required>
                        <option value="">-- Pilih Hewan --</option>
                        @foreach ($animals as $animal)
                        <option value="{{ $animal->id }}">{{ $animal->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-warning btn-sm mx-2" onclick="goToEdit()">
                        Edit Penyakit
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama Hewan</th>
                        <th>Nama Penyakit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($animalDiseases as $index => $animalDisease)
                    <tr>
                        <td class="text-center">{{ $animalDiseases->firstItem() + $index }}</td>
                        <td>{{ $animalDisease->animal->name }}</td>
                        <td>
                            {{ $animalDisease->disease->code ? $animalDisease->disease->code . ' - ' : '' }}
                            {{ $animalDisease->disease->name }}
                        </td>
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $animalDisease->id }}">
                                <i class="fa fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $animalDisease->id }}"
                                action="{{ route('animal_diseases.destroy', $animalDisease->id) }}" method="POST"
                                class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Tidak ada data yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $animalDiseases->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function goToEdit() {
        const animalId = document.getElementById('animalSelect').value;
        if (animalId) {
            window.location.href = `/animal_diseases/${animalId}/edit`;
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Silakan pilih hewan terlebih dahulu!',
            });
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                const id = this.getAttribute("data-id");
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data penyakit hewan akan dihapus permanen!",
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
@endsection
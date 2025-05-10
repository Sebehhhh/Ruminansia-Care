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

    <a href="{{ route('animal_symptoms.create') }}" class="btn btn-primary mb-3"><i class="fa fa-plus"></i></a>

    <div class="d-flex mb-3 align-items-center">
        <form id="editForm" class="d-flex" action="" method="GET">
            <select id="animalSelect" class="form-control me-2" style="width: auto;" required>
                <option value="">-- Pilih Hewan --</option>
                @foreach ($animals as $animal)
                <option value="{{ $animal->id }}">{{ $animal->name }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-warning ms-2 mx-2" onclick="goToEdit()">Edit Gejala Hewan</button>
        </form>
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
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Hewan</th>
                        <th>Gejala</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($animalSymptoms as $index => $animalSymptom)
                    <tr>
                        <td>{{ $animalSymptoms->firstItem() + $index }}</td>
                        <td>{{ $animalSymptom->animal->name }}</td>
                        <td>{{ $animalSymptom->symptom->name }}</td>
                        <td>
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
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $animalSymptoms->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
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
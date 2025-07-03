@extends('layouts.master')
@section('title', 'Daftar Penyakit Hewan')

@section('content')
<div class="container">
    <h3 class="mb-4">Daftar Penyakit Hewan</h3>

    @if (session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
    

    <a href="{{ route('animal_diseases.create') }}" class="btn btn-primary mb-3"><i class="fa fa-plus"></i></a>

    <div class="d-flex mb-3 align-items-center">
        <form id="editForm" class="d-flex" action="" method="GET">
            <select id="animalSelect" class="form-control me-2" style="width: auto;" required>
                <option value="">-- Pilih Hewan --</option>
                @foreach ($animals as $animal)
                <option value="{{ $animal->id }}">{{ $animal->name }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-warning ms-2 mx-2" onclick="goToEdit()">Edit Penyakit Hewan</button>
        </form>
    </div>

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
    </script>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Hewan</th>
                        <th>Nama Penyakit</th>
                        {{-- <th>Catatan</th> --}}
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($animalDiseases as $index => $animalDisease)
                    <tr>
                        <td>{{ $animalDiseases->firstItem() + $index }}</td>
                        <td>{{ $animalDisease->animal->name }}</td>
                        <td>
                            {{ $animalDisease->disease->code ? $animalDisease->disease->code . ' - ' : '' }}{{ $animalDisease->disease->name }}
                        </td>
                        {{-- <td>{{ $animalDisease->notes }}</td> --}}
                        <td>
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $animalDiseases->links('pagination::bootstrap-4') }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function() {
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
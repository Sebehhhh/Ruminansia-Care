@extends('layouts.master')

@section('content')
    <div class="container">
        <h3 class="mb-4">Daftar Gejala</h3>

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

        <a href="{{ route('symptoms.create') }}" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> </a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Gejala</th>
                    <th>Nama Gejala</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($symptoms as $index => $symptom)
                    <tr>
                        <td>{{ $symptoms->firstItem() + $index }}</td>
                        <td>{{ $symptom->code }}</td>
                        <td>{{ $symptom->name }}</td>
                        <td>
                            <a href="{{ route('symptoms.edit', encrypt($symptom->id)) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-pencil"></i> 
                            </a>

                            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $symptom->id }}">
                                <i class="fa fa-trash"></i> 
                            </button>

                            <form id="delete-form-{{ $symptom->id }}"
                                action="{{ route('symptoms.destroy', $symptom->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $symptoms->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function() {
                    const symptomId = this.getAttribute("data-id");
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
                            document.getElementById(`delete-form-${symptomId}`).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection

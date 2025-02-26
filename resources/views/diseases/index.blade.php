@extends('layouts.master')
@section('title', 'Daftar Penyakit')
    
@section('content')
    <div class="container">
        <h3 class="mb-4">Daftar Penyakit</h3>

        <!-- SweetAlert Notifikasi -->
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

        <a href="{{ route('diseases.create') }}" class="btn btn-primary mb-3"><i class="fa fa-plus"></i></a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Penyakit</th>
                    <th>Deskripsi</th>
                    <th>Rekomendasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($diseases as $index => $disease)
                    <tr>
                        <td>{{ $diseases->firstItem() + $index }}</td>
                        <td>{{ $disease->name }}</td>
                        <td>{{ $disease->description }}</td>
                        <td>{{ $disease->recommendation }}</td>
                        <td>
                            <a href="{{ route('diseases.edit', $disease->encrypted_id) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-pencil"></i> 
                            </a>

                            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $disease->encrypted_id }}">
                                <i class="fa fa-trash"></i> 
                            </button>

                            <form id="delete-form-{{ $disease->encrypted_id }}"
                                action="{{ route('diseases.destroy', $disease->encrypted_id) }}" method="POST"
                                class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $diseases->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function() {
                    const diseaseId = this.getAttribute("data-id");
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data penyakit akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-form-${diseaseId}`).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection

@extends('layouts.master')

@section('title', 'Manajemen Hewan')

@section('content')
    <div class="container ">
        <h3>Manajemen Hewan</h3>

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

        <!-- Tombol tambah hewan dengan icon -->
        <a href="{{ route('animals.create') }}" class="btn btn-primary mb-3 mt-3 btn-icon" title="Tambah Hewan">
            <i class="fa fa-plus"></i>
        </a>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Hewan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($animals as $index => $animal)
                            <tr>
                                <td>{{ $animals->firstItem() + $index }}</td>
                                <td>{{ $animal->name }}</td>
                                <td>
                                    <!-- Tombol edit dengan icon -->
                                    <a href="{{ route('animals.edit', $animal->encrypted_id) }}"
                                        class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>

                                    <!-- Tombol hapus dengan SweetAlert -->
                                    <button class="btn btn-sm btn-danger btn-icon delete-btn" title="Hapus"
                                        data-id="{{ $animal->encrypted_id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                    <!-- Form delete (hidden, akan dikirim lewat JS) -->
                                    <form id="delete-form-{{ $animal->encrypted_id }}"
                                        action="{{ route('animals.destroy', $animal->encrypted_id) }}" method="POST"
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

        <!-- Tampilkan link paginasi -->
        <div class="mt-3">
            {{ $animals->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- SweetAlert Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Konfirmasi hapus dengan SweetAlert
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function() {
                    const animalId = this.getAttribute("data-id");
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data hewan akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-form-${animalId}`).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection

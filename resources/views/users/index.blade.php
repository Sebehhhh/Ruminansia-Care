@extends('layouts.master')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="container ">
        <h3>Manajemen Pengguna</h3>

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

        <!-- Tombol tambah pengguna dengan icon -->
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3 mt-3 btn-icon" title="Tambah Pengguna">
            <i class="fa fa-plus"></i>
        </a>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <!-- Tombol edit dengan icon -->
                                    <a href="{{ route('users.edit', $user->encrypted_id) }}"
                                        class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>

                                    <!-- Tombol hapus dengan SweetAlert -->
                                    <button class="btn btn-sm btn-danger btn-icon delete-btn" title="Hapus"
                                        data-id="{{ $user->encrypted_id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                    <!-- Form delete (hidden, akan dikirim lewat JS) -->
                                    <form id="delete-form-{{ $user->encrypted_id }}"
                                        action="{{ route('users.destroy', $user->encrypted_id) }}" method="POST"
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
            {{ $users->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- SweetAlert Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Konfirmasi hapus dengan SweetAlert
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function() {
                    const userId = this.getAttribute("data-id");
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data pengguna akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-form-${userId}`).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection

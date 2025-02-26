@extends('layouts.master')

@section('title', 'Manajemen Rule')

@section('content')
    <div class="container mt-4">
        <h3>Daftar Aturan</h3>

        <!-- Tombol tambah rule dengan icon -->
        <a href="{{ route('rules.create') }}" class="btn btn-primary mb-3 mt-3 btn-icon" title="Tambah Rule">
            <i class="fa fa-plus"></i> 
        </a>

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

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Penyakit</th>
                    <th>Gejala</th>
                    <th>MB</th>
                    <th>MD</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rules as $index => $rule)
                    <tr>
                        <td>{{ $rules->firstItem() + $index }}</td>
                        <td>{{ $rule->disease->name ?? 'N/A' }}</td>
                        <td>{{ $rule->symptom->name ?? 'N/A' }}</td>
                        <td>{{ $rule->mb }}</td>
                        <td>{{ $rule->md }}</td>
                        <td>
                            <!-- Tombol edit dengan icon -->
                            <a href="{{ route('rules.edit', $rule->encrypted_id) }}" class="btn btn-sm btn-warning btn-icon"
                                title="Edit">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <!-- Tombol hapus dengan SweetAlert -->
                            <button class="btn btn-sm btn-danger btn-icon delete-btn" title="Hapus"
                                data-id="{{ $rule->encrypted_id }}">
                                <i class="fa fa-trash"></i>
                            </button>

                            <!-- Form delete (hidden, akan dikirim lewat JS) -->
                            <form id="delete-form-{{ $rule->encrypted_id }}"
                                action="{{ route('rules.destroy', $rule->encrypted_id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Tampilkan link paginasi -->
        <div class="mt-3">
            {{ $rules->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- SweetAlert Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Konfirmasi hapus dengan SweetAlert
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function() {
                    const ruleId = this.getAttribute("data-id");
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data rule akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-form-${ruleId}`).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection

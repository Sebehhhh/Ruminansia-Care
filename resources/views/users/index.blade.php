@extends('layouts.master')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="container mt-4">
        <h3>Manajemen Pengguna</h3>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tombol tambah pengguna dengan icon saja -->
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3 mt-3 btn-icon" title="Tambah Pengguna">
            <i class="fa fa-plus"></i>
        </a>

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
                            <a href="{{ route('users.edit', $user->encrypted_id) }}" class="btn btn-sm btn-warning btn-icon"
                                title="Edit">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <!-- Tombol hapus dengan icon -->
                            <form action="{{ route('users.destroy', $user->encrypted_id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger btn-icon" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Tampilkan link paginasi -->
        <div class="mt-3">
            {{ $users->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection

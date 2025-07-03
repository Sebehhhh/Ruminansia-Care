@extends('layouts.master')

@section('title', 'Manajemen Rule')

@section('content')
<div class="container">
    <h3>Daftar Aturan</h3>

    <!-- Tombol tambah rule -->
    <a href="{{ route('rules.create') }}" class="btn btn-primary mb-3 mt-3 btn-icon" title="Tambah Rule">
        <i class="fa fa-plus"></i>
    </a>

    <!-- Notifikasi SweetAlert -->
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

    <div class="card">
        <div class="card-body">

            <!-- Dropdown filter hewan -->
            <div class="mb-3">
                <form method="GET" action="{{ route('rules.index') }}">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <select name="animal_id" id="animal_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Semua Hewan --</option>
                                @foreach ($animals as $animal)
                                    <option value="{{ $animal->id }}" {{ (request('animal_id') == $animal->id) ? 'selected' : '' }}>
                                        {{ $animal->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel rule -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gejala</th>
                        <th>Penyakit</th>
                        <th>MB</th>
                        <th>MD</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rules as $index => $rule)
                        <tr>
                            <td>{{ $rules->firstItem() + $index }}</td>
                            <td>{{ $rule->symptom->code ?? 'N/A' }}</td>
                            <td>{{ $rule->disease->code ?? 'N/A' }}</td>
                            <td>{{ $rule->mb }}</td>
                            <td>{{ $rule->md }}</td>
                            <td>
                                <!-- Tombol edit -->
                                <a href="{{ route('rules.edit', $rule->id) }}" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <!-- Tombol hapus -->
                                <button class="btn btn-sm btn-danger btn-icon delete-btn" title="Hapus" data-id="{{ $rule->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>

                                <!-- Form delete tersembunyi -->
                                <form id="delete-form-{{ $rule->id }}" action="{{ route('rules.destroy', $rule->id) }}"
                                      method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginasi -->
            <div class="mt-3">
                {{ $rules->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert untuk hapus -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
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
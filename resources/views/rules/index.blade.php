@extends('layouts.master')

@section('title', 'Manajemen Rule')

@section('content')
<div class="container">
    <h3 class="mb-4">Daftar Aturan (Rule)</h3>

    {{-- Notifikasi sukses --}}
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

    {{-- Area tombol dan filter dalam 12 kolom, dibagi 3-3-3-3 --}}
    <div class="row mb-3 align-items-center">
        <div class="col-md-2 col-sm-6 mb-2 mb-md-0">
            <a href="{{ route('rules.create') }}" class="btn btn-primary btn-sm w-100">
                <i class="fa fa-plus me-1"></i> Tambah Rule
            </a>
        </div>
        <form method="GET" action="{{ route('rules.index') }}" class="col-md-10 row g-2">
            <div class="col-md-3 col-sm-6">
                <select name="animal_id" id="animal_id" class="form-select form-select-sm"
                    onchange="this.form.submit()">
                    <option value="">-- Semua Hewan --</option>
                    @foreach ($animals as $animal)
                    <option value="{{ $animal->id }}" {{ request('animal_id')==$animal->id ? 'selected' : '' }}>
                        {{ $animal->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-sm-6 mx-2">
                <select name="disease_id" id="disease_id" class="form-select form-select-sm"
                    onchange="this.form.submit()">
                    <option value="">-- Semua Penyakit --</option>
                    @foreach ($diseases as $disease)
                    <option value="{{ $disease->id }}" {{ request('disease_id')==$disease->id ? 'selected' : '' }}>
                        {{ $disease->code }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <select name="symptom_id" id="symptom_id" class="form-select form-select-sm"
                    onchange="this.form.submit()">
                    <option value="">-- Semua Gejala --</option>
                    @foreach ($symptoms as $symptom)
                    <option value="{{ $symptom->id }}" {{ request('symptom_id')==$symptom->id ? 'selected' : '' }}>
                        {{ $symptom->code }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-6 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary btn-sm w-100">
                    <i class="fa fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel data --}}
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Penyakit</th>
                        <th>Kode Gejala</th>
                        <th>MB</th>
                        <th>MD</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rules as $index => $rule)
                    <tr>
                        <td class="text-center">{{ $rules->firstItem() + $index }}</td>
                        <td>{{ $rule->disease->code ?? '-' }}</td>
                        <td>{{ $rule->symptom->code ?? '-' }}</td>
                        <td class="text-center">{{ $rule->mb }}</td>
                        <td class="text-center">{{ $rule->md }}</td>
                        <td class="text-center">
                            <a href="{{ route('rules.edit', $rule->id) }}" class="btn btn-warning btn-sm me-1"
                                title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm delete-btn" title="Hapus" data-id="{{ $rule->id }}">
                                <i class="fa fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $rule->id }}" method="POST"
                                action="{{ route('rules.destroy', $rule->id) }}" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data aturan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Paginasi --}}
            <div class="mt-3">
                {{ $rules->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert Hapus --}}
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
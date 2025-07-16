@extends('layouts.master')

@section('title', 'Riwayat Diagnosa')

@section('content')
<div class="container ">
    <h3 class="mb-4">Riwayat Diagnosa</h3>

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

    <!-- Tampilkan Riwayat Diagnosa Terbaru -->
    @if ($latestHistory)
    <div class="card mb-4 shadow-sm border-primary">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-star me-2"></i> Diagnosa Terbaru</h5>
            <span class="badge bg-light text-dark">{{ $latestHistory->created_at->format('d M Y H:i') }}</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-muted">Hewan</h6>
                        <h5>{{ $latestHistory->animal->name ?? '-' }}</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-muted">Tanggal</h6>
                        <h5>{{ $latestHistory->created_at->format('d M Y H:i') }}</h5>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-success mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Hasil Diagnosa:</h6>
                        <h4 class="mb-0">{{ $latestHistory->disease->name ?? 'Tidak Diketahui' }}</h4>
                    </div>
                    <div class="text-center">
                        <div class="display-6">{{ number_format($latestHistory->confidence * 100, 1) }}%</div>
                        <small>Tingkat Kepercayaan</small>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <h6>Gejala yang Dipilih:</h6>
                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#latestSymptomsModal">
                    <i class="fa fa-eye"></i> Lihat Gejala
                </button>
                
                <!-- Modal untuk menampilkan gejala terbaru -->
                <div class="modal fade" id="latestSymptomsModal" tabindex="-1" role="dialog" aria-labelledby="latestSymptomsModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="latestSymptomsModalLabel">Gejala yang Dipilih</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama Gejala</th>
                                            <th>CF User</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $selectedSymptoms = $latestHistory->selected_symptoms ?? [];
                                            // Filter hanya gejala dengan CF > 0
                                            $filteredSymptomIds = array_keys(array_filter($selectedSymptoms, function($value) {
                                                return floatval($value) > 0;
                                            }));
                                            $symptoms = \App\Models\Symptom::whereIn('id', $filteredSymptomIds)->get();
                                        @endphp
                                        @foreach ($symptoms as $symptom)
                                        <tr>
                                            <td>{{ $symptom->code ?? '-' }}</td>
                                            <td>{{ $symptom->name ?? '-' }}</td>
                                            <td>{{ $selectedSymptoms[$symptom->id] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tampilkan Daftar Riwayat Diagnosa dalam Bentuk Tabel -->
    <h4 class="mt-4">Riwayat Diagnosa</h4>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Hewan</th>
                            <th>Penyakit</th>
                            <th>Kepercayaan</th>
                            <th>Gejala</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $index => $history)
                        <tr>
                            <td>{{ $histories->firstItem() + $index }}</td>
                            <td>{{ $history->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $history->animal->name ?? '-' }}</td>
                            <td>{{ $history->disease->name ?? 'Tidak Diketahui' }}</td>
                            <td>{{ number_format($history->confidence * 100, 1) }}%</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#symptomsModal{{ $history->id }}">
                                    <i class="fa fa-eye"></i> Lihat Gejala
                                </button>
                                
                                <!-- Modal untuk menampilkan gejala -->
                                <div class="modal fade" id="symptomsModal{{ $history->id }}" tabindex="-1" role="dialog" aria-labelledby="symptomsModalLabel{{ $history->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="symptomsModalLabel{{ $history->id }}">Gejala yang Dipilih</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Kode</th>
                                                            <th>Nama Gejala</th>
                                                            <th>CF User</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $selectedSymptoms = $history->selected_symptoms ?? [];
                                                            // Filter hanya gejala dengan CF > 0
                                                            $filteredSymptomIds = array_keys(array_filter($selectedSymptoms, function($value) {
                                                                return floatval($value) > 0;
                                                            }));
                                                            $symptoms = \App\Models\Symptom::whereIn('id', $filteredSymptomIds)->get();
                                                        @endphp
                                                        @foreach ($symptoms as $symptom)
                                                        <tr>
                                                            <td>{{ $symptom->code ?? '-' }}</td>
                                                            <td>{{ $symptom->name ?? '-' }}</td>
                                                            <td>{{ $selectedSymptoms[$symptom->id] ?? '-' }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger delete-btn" title="Hapus" data-id="{{ encrypt($history->id) }}">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                                <form id="delete-form-{{ encrypt($history->id) }}" action="{{ route('history.destroy', encrypt($history->id)) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">Belum ada riwayat diagnosis</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tampilkan link paginasi -->
    <div class="mt-3">
        {{ $histories->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- SweetAlert Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Hapus riwayat dengan konfirmasi SweetAlert
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function() {
                const historyId = this.getAttribute("data-id");
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Riwayat diagnosa akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const deleteForm = document.getElementById("delete-form-" + historyId);
                        if (deleteForm) {
                            deleteForm.submit();
                        } else {
                            console.error("Form delete tidak ditemukan!");
                        }
                    }
                });
            });
        });
    });
</script>
@endsection

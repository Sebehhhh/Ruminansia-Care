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
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-light text-dark">
            <h5 class="mb-0">📌 Diagnosa Terbaru</h5>
        </div>
        <div class="card-body">
            <p><strong>Hewan:</strong> {{ $latestHistory->animal->name ?? '-' }}</p>
            <p><strong>Penyakit yang Didiagnosa:</strong> {{ $latestHistory->disease->name ?? 'Tidak Diketahui' }}</p>
            <p><strong>Kepercayaan:</strong> {{ $latestHistory->confidence * 100 }}%</p>
            <p><strong>Tanggal Diagnosa:</strong> {{ $latestHistory->created_at->format('d M Y H:i') }}</p>
            <p><strong>Gejala yang Dipilih:</strong></p>
            <ul class="ml-3">
                @php
                    $selectedSymptoms = $latestHistory->selected_symptoms ?? [];
                    $symptomNames = \App\Models\Symptom::whereIn('id', array_keys($selectedSymptoms))->pluck('name')->toArray();
                @endphp
                @foreach ($symptomNames as $name)
                    <li>{{ $name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Tampilkan Daftar Riwayat Diagnosa -->
    <h4 class="mt-4">Riwayat Diagnosa</h4>
    <div class="accordion" id="historyAccordion">
        @foreach ($histories as $index => $history)
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <button class="btn btn-link text-dark text-left w-100 d-flex align-items-center" type="button"
                    data-toggle="collapse" data-target="#collapse{{ $index }}" aria-expanded="true"
                    aria-controls="collapse{{ $index }}">
                    <i class="fa fa-plus mr-2"></i>
                    <strong>{{ $history->disease->name ?? 'Tidak Diketahui' }}</strong> -
                    <span>{{ $history->confidence * 100 }}% Kepercayaan</span>
                </button>
            </div>
            <div id="collapse{{ $index }}" class="collapse" aria-labelledby="heading{{ $index }}"
                data-parent="#historyAccordion">
                <div class="card-body">
                    <p><strong>Hewan:</strong> {{ $history->animal->name ?? '-' }}</p>
                    <p><strong>Tanggal Diagnosa:</strong> {{ $history->created_at->format('d M Y H:i') }}</p>
                    <p><strong>Gejala yang Dipilih:</strong></p>
                    <ul class="ml-3">
                        @php
                            $selectedSymptoms = $history->selected_symptoms ?? [];
                            $symptomNames = \App\Models\Symptom::whereIn('id', array_keys($selectedSymptoms))->pluck('name')->toArray();
                        @endphp
                        @foreach ($symptomNames as $name)
                            <li>{{ $name }}</li>
                        @endforeach
                    </ul>
                    <!-- Tombol hapus di dalam accordion -->
                    <div class="mt-3">
                        <button class="btn btn-sm btn-danger delete-btn" title="Hapus"
                            data-id="{{ encrypt($history->id) }}">
                            <i class="fa fa-trash"></i> Hapus Riwayat
                        </button>
                        <form id="delete-form-{{ encrypt($history->id) }}"
                            action="{{ route('history.destroy', encrypt($history->id)) }}" method="POST"
                            class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
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
        // Ganti ikon + / - saat accordion dibuka atau ditutup
        document.querySelectorAll(".btn-link").forEach(button => {
            button.addEventListener("click", function() {
                let icon = this.querySelector("i");
                if (icon.classList.contains("fa-plus")) {
                    icon.classList.replace("fa-plus", "fa-minus");
                } else {
                    icon.classList.replace("fa-minus", "fa-plus");
                }
            });
        });

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
                        const deleteForm = this.closest(".card").querySelector("form");
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

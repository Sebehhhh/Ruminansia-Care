@extends('welcome.app')
@section('content')

<style>
    .img-thumbnail {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 10px #0001;
    }
</style>

<!-- ======= Hero ======= -->
<section class="hero__v6 section" id="home">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <span class="hero-subtitle text-uppercase">Sistem Pakar Ternak</span>
                <h1 class="hero-title mb-3">Diagnosa Penyakit Hewan Ternak</h1>
                <p class="hero-description mb-4 mb-lg-5">
                    Sistem pakar berbasis web untuk membantu identifikasi penyakit pada hewan ternak secara cepat dan
                    akurat menggunakan metode Certainty Factor (CF).
                </p>
                <a href="{{ route('diagnosa.index') }}" class="btn btn-primary">Mulai Diagnosa</a>
            </div>
            <div class="col-lg-6">
                <img class="img-fluid rounded-4 shadow"
                    src="https://cdn.pixabay.com/photo/2023/04/28/19/27/cow-7957275_1280.jpg" alt="Foto Ternak"
                    style="width: 100%; height: 400px; object-fit: cover;">
            </div>
        </div>
    </div>
</section>
<!-- End Hero -->

<!-- ======= Tentang Sistem ======= -->
<section class="section" id="about">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Tentang Sistem</h2>
            <p>Apa itu sistem pakar dan bagaimana cara kerjanya?</p>
        </div>
        <div class="row align-items-center">
            <div class="col-md-6">
                <img class="img-fluid rounded-4"
                    src="https://cdn.pixabay.com/photo/2018/07/26/05/52/sheeps-3562868_1280.jpg"
                    alt="Tentang Sistem Pakar" style="height: 350px; object-fit: cover;">
            </div>
            <div class="col-md-6">
                <p>Sistem ini menggunakan metode <strong>Certainty Factor (CF)</strong> untuk menentukan kemungkinan
                    penyakit berdasarkan gejala yang dipilih pengguna. Data gejala, penyakit, dan relasi aturan telah
                    ditentukan oleh pakar peternakan.</p>
                <ul>
                    <li>💡 Cepat & mudah digunakan</li>
                    <li>📋 Riwayat diagnosa tersimpan</li>
                    <li>🔐 Akses aman dan terkelola</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- End About -->

<!-- ======= Statistik ======= -->
<section class="section bg-light" id="statistik">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Informasi Sistem</h2>
            <p>Data terkini dalam sistem pakar</p>
        </div>
        <div class="row text-center">
            <div class="col-md-4">
                <h3>{{ $jumlahHewan }}</h3>
                <p>Hewan Terdaftar</p>
            </div>
            <div class="col-md-4">
                <h3>{{ $jumlahPenyakit }}</h3>
                <p>Jenis Penyakit</p>
            </div>
            <div class="col-md-4">
                <h3>{{ $jumlahGejala }}</h3>
                <p>Jenis Gejala</p>
            </div>
        </div>
    </div>
</section>
<!-- End Statistik -->

<!-- ======= Langkah Penggunaan ======= -->
<section class="section" id="langkah">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Cara Menggunakan</h2>
            <p>Langkah mudah untuk menggunakan sistem pakar diagnosa penyakit ternak</p>
        </div>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <h1>📝</h1>
                <h5>1. Masukkan Data Hewan</h5>
                <p>Isi data dasar hewan seperti nama & jenis</p>
            </div>
            <div class="col-md-4 mb-4">
                <h1>🧩</h1>
                <h5>2. Pilih Gejala</h5>
                <p>Checklist gejala-gejala yang muncul</p>
            </div>
            <div class="col-md-4 mb-4">
                <h1>🧠</h1>
                <h5>3. Lihat Hasil Diagnosa</h5>
                <p>Hasil perhitungan akan muncul otomatis</p>
            </div>
        </div>
    </div>
</section>
<!-- End Langkah -->

<!-- ======= Kontak ======= -->
<section class="section" id="kontak">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Kontak</h2>
            <p>Hubungi pengembang untuk kolaborasi atau pelaporan bug</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center">
                    <div class="mb-2"><i class="bi bi-geo-alt"></i> Gn. Makmur, Takisung, Kabupaten Tanah Laut, Kalimantan Selatan 70861</div>
                    <div class="mb-2"><i class="bi bi-geo-alt"></i> Jl. Raya Takisung, Takisung, Kabupaten Tanah Laut, Kalimantan Selatan</div>
                    <div class="mb-2"><i class="bi bi-telephone"></i> +62 852-4727-9944</div>
                    <div class="mb-2"><i class="bi bi-envelope"></i> puskeswantakisung@gmail.co</div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Kontak -->

@endsection
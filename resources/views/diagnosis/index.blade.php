@extends('layouts.master')

@section('title', 'Diagnosis Ternak Ruminansia')

@section('content')
    <div class="container ">
        <h3 class="mb-4">Diagnosis Ternak Ruminansia</h3>
        <p>Silakan pilih kategori ruminansia terlebih dahulu, kemudian pilih gejala yang dialami ternak Anda dan tentukan
            tingkat keyakinan untuk setiap gejala.</p>

        <form action="{{ route('diagnosis.process') }}" method="POST" id="diagnosis-form">
            @csrf

            <!-- Pilihan Kategori Ruminansia -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Pilih Kategori Ruminansia</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="category" value="Sapi" required> Sapi
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="category" value="Kerbau" required> Kerbau
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="category" value="Kambing" required> Kambing
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="category" value="Domba" required> Domba
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wizard Diagnosis Gejala -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Diagnosis Gejala</h5>
                </div>
                <div class="card-body">
                    <p>Pilih gejala dan tentukan tingkat keyakinan Anda untuk setiap pertanyaan. Jika tidak tahu, pilih opsi
                        <strong>Tidak Tahu</strong> (nilai 0).</p>
                    <!-- Container untuk setiap pertanyaan (gejala) -->
                    <div id="question-container">
                        @foreach ($symptoms as $index => $symptom)
                            <div class="question" data-index="{{ $index }}" style="display: none;">
                                <h5>Pertanyaan {{ $index + 1 }}: {{ $symptom->name }}</h5>
                                <div class="options my-3">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <!-- Pilihan: Tidak Tahu -->
                                        <label class="btn btn-outline-secondary">
                                            <input type="radio" name="answers[{{ $symptom->id }}]" value="0"> Tidak
                                            Tahu
                                        </label>
                                        <!-- Pilihan: Sedikit Yakin -->
                                        <label class="btn btn-outline-secondary">
                                            <input type="radio" name="answers[{{ $symptom->id }}]" value="0.2">
                                            Sedikit Yakin
                                        </label>
                                        <!-- Pilihan: Agak Yakin -->
                                        <label class="btn btn-outline-secondary">
                                            <input type="radio" name="answers[{{ $symptom->id }}]" value="0.4"> Agak
                                            Yakin
                                        </label>
                                        <!-- Pilihan: Cukup Yakin -->
                                        <label class="btn btn-outline-secondary">
                                            <input type="radio" name="answers[{{ $symptom->id }}]" value="0.6"> Cukup
                                            Yakin
                                        </label>
                                        <!-- Pilihan: Yakin -->
                                        <label class="btn btn-outline-secondary">
                                            <input type="radio" name="answers[{{ $symptom->id }}]" value="0.8"> Yakin
                                        </label>
                                        <!-- Pilihan: Sangat Yakin -->
                                        <label class="btn btn-outline-secondary">
                                            <input type="radio" name="answers[{{ $symptom->id }}]" value="1"> Sangat
                                            Yakin
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Tombol Navigasi Antar Pertanyaan -->
                    <div class="navigation mt-4 d-flex justify-content-between">
                        <button type="button" id="prev-btn" class="btn btn-secondary">Sebelumnya</button>
                        <button type="button" id="next-btn" class="btn btn-secondary">Selanjutnya</button>
                        <button type="submit" id="submit-btn" class="btn btn-primary" style="display: none;">Submit
                            Diagnosis</button>
                    </div>

                    <!-- Navigasi Langsung via Nomor Pertanyaan -->
                    <div class="pagination mt-3">
                        @foreach ($symptoms as $index => $symptom)
                            <button type="button" class="btn btn-light page-btn"
                                data-index="{{ $index }}">{{ $index + 1 }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Script JavaScript untuk Navigasi Wizard -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const questions = document.querySelectorAll('.question');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');
            const pageBtns = document.querySelectorAll('.page-btn');
            let currentIndex = 0;

            function showQuestion(index) {
                questions.forEach((question, i) => {
                    question.style.display = (i === index) ? 'block' : 'none';
                });
                // Atur tampilan tombol navigasi
                prevBtn.style.display = (index === 0) ? 'none' : 'inline-block';
                nextBtn.style.display = (index === questions.length - 1) ? 'none' : 'inline-block';
                submitBtn.style.display = (index === questions.length - 1) ? 'inline-block' : 'none';
            }

            // Tampilkan pertanyaan pertama
            showQuestion(currentIndex);

            prevBtn.addEventListener('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    showQuestion(currentIndex);
                }
            });

            nextBtn.addEventListener('click', function() {
                if (currentIndex < questions.length - 1) {
                    currentIndex++;
                    showQuestion(currentIndex);
                }
            });

            // Navigasi langsung melalui tombol nomor
            pageBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    currentIndex = parseInt(this.getAttribute('data-index'));
                    showQuestion(currentIndex);
                });
            });
        });
    </script>
@endsection

@extends('layouts.admin')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Tambah FAQ Baru</h1>
            <ul class="breadcrumb">
                <li><a href="{{ url('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="/manageFAQ" class="text-decoration-none">FAQ</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="#" class="text-decoration-none">Tambah Baru</a></li>
            </ul>
        </div> 
    </div>

    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body">

            <h5 class="mb-4 text-dark fw-bold">Form Tambah FAQ</h5>

            {{-- FORM SIMPAN --}}
            <form action="/manageFaq-simpan" method="POST">
                @csrf

                {{-- INFO: ID FAQ DIHAPUS KARENA OTOMATIS (F01, F02...) --}}

                {{-- PERTANYAAN --}}
                <div class="mb-3">
                    <label for="pertanyaan" class="form-label fw-bold">Pertanyaan (Q)</label>
                    <input type="text"
                           id="pertanyaan"
                           name="pertanyaan"
                           class="form-control"
                           placeholder="Contoh: Bagaimana cara memesan maggot?"
                           value="{{ old('pertanyaan') }}"
                           required>
                </div>

                {{-- JAWABAN --}}
                <div class="mb-3">
                    <label for="jawaban" class="form-label fw-bold">Jawaban (A)</label>
                    <textarea id="jawaban"
                              name="jawaban"
                              class="form-control"
                              rows="5"
                              placeholder="Tulis jawaban lengkap di sini..."
                              required>{{ old('jawaban') }}</textarea>
                </div>

                {{-- BUTTON --}}
                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a href="/manageFaq" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan FAQ</button>
                </div>

            </form>

        </div>
    </div>
</main>
@endsection


@extends('layouts.admin') 


@section('content')

<main>
    <div class="head-title">
        <div class="left">
            <h1>Manajemen FAQ</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="{{ route('managefaq.index') }}"> FAQ </a></li>
            </ul>
        </div>
    </div>

    <div class="container-fluid py-4 ">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            
            <div class="alert alert-danger">{{ session('error') }}</div> 
            <div class="alert alert-danger">{{ session('error' )}}</div> 
        @endif

        <div class="card shadow mb-5 border-start border-success border-5">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"> Tambah Pertanyaan dan Jawaban Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('managefaq.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="pertanyaan" class="form-label fw-bold">Pertanyaan (Q)</label>
                        <input type="text" class="form-control" id="pertanyaan" name="pertanyaan" placeholder="Masukkan pertanyaan FAQ..." required>
                    </div>

                    <div class="mb-3">
                        <label for="jawaban" class="form-label fw-bold">Jawaban (A)</label>
                        <textarea class="form-control" id="jawaban" name="jawaban" rows="5" placeholder="Tulis jawaban lengkap untuk pertanyaan di atas..." required></textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-success"><i class='bx bx-save'></i> Simpan FAQ Baru</button>
                    </div>
                </form>
            </div>
        </div>
        
        <hr>
        
        <h5 class="mb-3 text-dark">Daftar FAQ Yang Sudah Ada (Klik untuk Edit)</h5>
        
        <div class="accordion" id="faqAccordion">
            @forelse ($faqs as $faq)
            <div class="accordion-item shadow-sm mb-2">
                <h2 class="accordion-header" id="heading{{ $faq->id }}">
                    <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}" aria-expanded="false" aria-controls="collapse{{ $faq->id }}">
                        <span class="fw-bold me-3 text-success">Q:</span> {{ $faq->pertanyaan }}
                    </button>
                </h2>
                <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#faqAccordion">
                    <div class="accordion-body bg-light">
                        {{-- Form Edit di dalam Accordion Body --}}
                        <form action="{{ route('managefaq.update', $faq->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <h6 class="text-secondary">Edit Jawaban:</h6>
                            <div class="mb-3">
                                <textarea class="form-control" name="jawaban" rows="4" required>{{ $faq->jawaban }}</textarea>
                            </div>
                            <h6 class="text-secondary">Edit Pertanyaan:</h6>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="pertanyaan" value="{{ $faq->pertanyaan }}" required>
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <button type="submit" class="btn btn-sm btn-warning"><i class='bx bx-save'></i> Simpan Perubahan</button>
                                
                                <button type="button" class="btn btn-sm btn-danger" onclick="document.getElementById('delete-form-{{ $faq->id }}').submit();">
                                    <i class='bx bx-trash'></i> Hapus FAQ
                                </button>
                            </div>
                        </form>
                        
                        {{-- Form Hapus terpisah (hidden) --}}
                        <form id="delete-form-{{ $faq->id }}" action="{{ route('managefaq.destroy', $faq->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>

                    </div>
                </div>
            </div>
            @empty
                <div class="alert alert-info text-center mt-4">
                    Belum ada FAQ yang tercatat. Silakan gunakan formulir di atas untuk memulai.
                </div>
            @endforelse
        </div>
    </div>
    
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection
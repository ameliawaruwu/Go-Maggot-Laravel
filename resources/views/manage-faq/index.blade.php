@extends('layouts.admin') 

@section('content')

<main>
    <div class="head-title">
        <div class="left">
            <h1>Manajemen FAQ</h1>
            <ul class="breadcrumb">
                <li><a href="" class="text-decoration-none">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="#" class="text-decoration-none">FAQ</a></li>
            </ul>
        </div>
        
        <a href="/manageFaq-input" class="btn-download" style="padding: 10px 15px; border-radius: 8px; font-weight: 600; text-decoration:none;">
            <i class='bx bxs-plus-circle'></i>
            <span class="text">Tambah FAQ</span>
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="mb-4 text-dark fw-bold">Daftar Pertanyaan & Jawaban</h5>
            <div class="accordion" id="faqAccordion">

                @forelse ($faq as $f)
                <div class="accordion-item mb-2 border rounded overflow-hidden">
                    <h2 class="accordion-header" id="heading{{ $f->id_faq }}">
                        <button class="accordion-button collapsed fw-bold text-dark" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#collapse{{ $f->id_faq }}" 
                                aria-expanded="false" 
                                aria-controls="collapse{{ $f->id_faq }}">
                            <span class="badge bg-success me-3">Q</span> {{ $f->pertanyaan }}
                        </button>
                    </h2>

                    <div id="collapse{{ $f->id_faq }}" 
                         class="accordion-collapse collapse" 
                         aria-labelledby="heading{{ $f->id_faq }}" 
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body bg-light">
                            
                            <div class="mb-3">
                                <strong class="text-success">Jawaban:</strong>
                                <p class="mt-1 text-muted" style="white-space: pre-line;">{{ $f->jawaban }}</p>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="/manageFaq-edit/{{ $f->id_faq }}" class="btn btn-sm btn-warning text-white">
                                    <i class='bx bxs-edit'></i> Edit
                                </a>
                                <a href="/manageFaq-hapus/{{ $f->id_faq }}" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Yakin ingin menghapus FAQ ini?')">
                                    <i class='bx bxs-trash'></i> Hapus
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted">Belum ada FAQ.</p>
                @endforelse

            </div>
        </div>
    </div>
</main>

@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

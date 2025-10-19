@extends('layouts.admin') 

@section('content')

<main>
    <div class="head-title">
        <div class="left">
            <h1>Tambah Artikel Baru</h1>
            <ul class="breadcrumb">
                <li><a href="#"  class="text-decoration-none">Artikel</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="#"  class="text-decoration-none">Tambah Baru</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Form Artikel</h3>
            </div>
            
           
            @if ($errors->any())
                <div class="form-message error" style="margin-bottom: 20px; padding: 15px; border-radius: 8px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

          
            <form action="{{ route('publication.store') }}" method="POST">
                @csrf

                <div class="form-group mb-4">
                    <label for="judul">Judul Artikel</label>
                    <input type="text" id="judul" name="judul" class="form-control" 
                           value="{{ old('judul') }}" required>
                </div>

                <div class="form-group mb-4">
                    <label for="penulis">Penulis</label>
                    <input type="text" id="penulis" name="penulis" class="form-control" 
                           value="{{ old('penulis') }}" required>
                </div>
                
               
                <div class="form-group mb-4">
                    <label>Tanggal</label>
                    {{-- Menampilkan format yang sesuai dengan gambar, namun input tanggal diabaikan karena diisi Carbon::now() --}}
                    <input type="text" class="form-control" value="{{ now()->format('d/m/Y') }}" disabled> 
                </div>

                <div class="form-group mb-4">
                    <label for="hak_cipta">Hak Cipta</label>
                    <input type="text" id="hak_cipta" name="hak_cipta" class="form-control" 
                           value="{{ old('hak_cipta') }}" required>
                </div>

                <div class="form-group mb-4">
                    <label for="konten">Isi Artikel</label>
                    {{-- Gunakan textarea untuk input yang lebih besar seperti 'Isi Artikel' --}}
                    <textarea id="konten" name="konten" class="form-control" rows="10" 
                              placeholder="Masukkan konten lengkap artikel..." required>{{ old('konten') }}</textarea>
                </div>

                <div class="form-actions">
                      <a href="{{ route('publication.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                  
                </div>
            </form>

        </div>
    </div>
</main>

@endsection
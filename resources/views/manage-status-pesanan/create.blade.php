@extends('layouts.app2')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Status Pesanan</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/manageStatus-simpan') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="id_status_pesanan" class="form-label">ID Status</label>
            <input type="text" name="id_status_pesanan" id="id_status_pesanan"
                   class="form-control" value="{{ old('id_status_pesanan') }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Nama Status</label>
            <input type="text" name="status" id="status"
                   class="form-control" value="{{ old('status') }}" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi"
                      class="form-control">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="urutan_tampilan" class="form-label">Urutan Tampilan</label>
            <input type="number" name="urutan_tampilan" id="urutan_tampilan"
                   class="form-control" value="{{ old('urutan_tampilan') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ url('/manageStatus') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

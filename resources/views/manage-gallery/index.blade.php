@extends('layouts.admin')

@section('title', 'Manajemen Galeri Foto')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Manage Galeri</h4>
        <a href="{{ route('gallery.create') }}" class="btn btn-primary">+ Tambah Galeri</a>
    </div>

    @if (session('status_message'))
        <div class="alert alert-success">
            {{ session('status_message') }}
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID Galeri</th>
                <th>Gambar</th>
                <th>Keterangan</th>
                <th width="160">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($galleries as $item)
                <tr>
                    <td>{{ $item->id_galeri }}</td>
                    <td>
                        @if($item->gambar)
                            <img src="{{ asset('photo/'.$item->gambar) }}" alt="" style="height:70px;">
                        @else
                            <span class="text-muted">Tidak ada</span>
                        @endif
                    </td>
                    <td>{{ $item->keterangan }}</td>
                    <td>
                        <a href="{{ route('gallery.edit', $item->id_galeri) }}" class="btn btn-sm btn-warning">
                            Edit
                        </a>

                        <form action="{{ route('gallery.destroy', $item->id_galeri) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus galeri ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada data galeri.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

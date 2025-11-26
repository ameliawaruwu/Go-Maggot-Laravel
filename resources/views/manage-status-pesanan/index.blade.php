@extends('layouts.app2')

@section('content')
<div class="container">
    <h1 class="mb-4">Kelola Status Pesanan</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ url('/manageStatus-input') }}" class="btn btn-primary mb-3">
        Tambah Status
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Status</th>
                <th>Nama Status</th>
                <th>Deskripsi</th>
                <th>Urutan Tampilan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($status as $item)
                <tr>
                    <td>{{ $item->id_status_pesanan }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>{{ $item->urutan_tampilan }}</td>
                    <td>
                        <a href="{{ url('/manageStatus-edit/' . $item->id_status_pesanan) }}" class="btn btn-sm btn-warning">
                            Edit
                        </a>

                        <a href="{{ url('/manageStatus-hapus/' . $item->id_status_pesanan) }}"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Yakin hapus status ini?')">
                            Hapus
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data status pesanan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

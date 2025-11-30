@extends('layouts.admin')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Manajemen Pengguna</h1>
            <ul class="breadcrumb">
                <li><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="/manageUser" class="text-decoration-none">Pengguna</a></li>
            </ul>
        </div>
        <a href="/manageUser-input" class="btn-download" style="padding: 10px 15px; border-radius: 8px; font-weight: 600; text-decoration:none;">
            <i class='bx bxs-plus-circle'></i>
            <span class="text">Tambah Pengguna</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Daftar Pengguna</h3>
                <div style="display: flex; gap: 10px;">
                    <div class="search-box" style="position: relative;">
                        <input type="text" placeholder="Cari user..." style="padding: 8px 30px 8px 10px; border: 1px solid #ccc; border-radius: 5px;">
                        <i class='bx bx-search' style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
                    <i class='bx bx-filter' style="font-size: 24px; cursor: pointer;"></i> 
                </div>
            </div>
            
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID Pengguna</th>
                            <th>Nama User</th>
                            <th>Email</th>
                            <th>Foto Profil</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengguna as $pgn)
                            <tr>
                                <td style="height: 70px; display: flex; align-items: center; justify-content: center;">{{ $pgn->id_pengguna }} </td>
                                <td>{{ $pgn->username }}</td>
                                <td>{{ $pgn->email }}</td>
                                <td>
                                    @if($pgn->foto_profil)
                                        <img src="{{ asset('photo/' . $pgn->foto_profil) }}" 
                                             style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;" 
                                             alt="Foto Profil">
                                    @else
                                        <span style="color: gray; font-size: 12px;">Tidak Ada Foto</span>
                                    @endif
                                </td>
                                <td>
                                        {{ $pgn->role }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        {{-- Tombol Edit --}}
                                        <a href="/manageUser-edit/{{ $pgn->id_pengguna }}" 
                                           class="btn btn-sm btn-warning text-white" 
                                           style="padding: 5px 10px; border-radius: 5px; text-decoration: none; background-color: #ffc107;">
                                            <i class='bx bxs-edit'></i>
                                        </a>
                                        <a href="/manageUser-hapus/{{ $pgn->id_pengguna }}" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Yakin ingin menghapus user {{ $pgn->username }}?')"
                                           style="padding: 5px 10px; border-radius: 5px; text-decoration: none; background-color: #dc3545; color: white;">
                                            <i class='bx bxs-trash'></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

@endsection

@extends('layouts.admin')

@section('content')

<link rel="stylesheet" href="{{ asset('Admin-HTML/css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('Admin-HTML/css/adminUser.css') }}"> 

<main>
    <div class="head-title">
        <div class="left">
            <h1>Managemen Pengguma</h1>
            <ul class="breadcrumb">
                <li><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="{{ route('user.index') }}" class="text-decoration-none">User</a></li>
            </ul>
        </div>
        <a href="{{ route('user.create') }}" class="btn-download" style="padding: 10px 15px; border-radius: 8px; font-weight: 600; text-decoration:none;">
            <i class='bx bxs-plus-circle'></i>
            <span class="text">Add New User</span>
        </a>
    </div>

    @if(session('status_message'))
        <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; background: #e6ffed; color: #007f3d; border-radius: 8px;">
            {{ session('status_message') }}
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 20px; padding: 15px; background: #ffe6e6; color: #ff0000; border-radius: 8px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>User List</h3>
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
                            <th>Id</th>
                            <th>Nama User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Foto Profil</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            @php
                                $photoFileName = $user['foto_profil'];
                                $photoPath = !empty($photoFileName) 
                                    ? asset('images/' . $photoFileName) 
                                    : asset('Admin-HTML/img/no-avatar.png'); 
                                
                                $roleText = ($user['role'] == 'admin') ? 'admin' : 'konsumen';
                                
                                // PERBAIKAN: Menyiapkan string CSS Role untuk menghindari ParseError
                                $roleStyle = ($user['role'] == 'admin') 
                                    ? 'background: rgba(255, 193, 7, 0.2); color: #ffc107;' 
                                    : 'background: rgba(40, 167, 69, 0.2); color: #28a745;';
                            @endphp
                            <tr>
                                <td data-label="Id">{{ $user['id_pelanggan'] }}</td>
                                <td data-label="Nama User">{{ $user['username'] }}</td>
                                <td data-label="Email">{{ $user['email'] }}</td>
                                <td data-label="Role">
                                    <span class="status" 
                                        {{-- Style dinamis dicetak sebagai variabel utuh --}}
                                        style="padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600; text-transform: capitalize; ">
                                        
                                        {{ $roleText }} 
                                    </span>
                                </td>
                                <td data-label="Foto Profil">
                                    @if (!empty($user['foto_profil']))
                                        <span class="text-success">Foto Ada</span> 
                                    @else
                                        Tidak Ada
                                    @endif
                                </td>
                                <td data-label="Action" style="white-space: nowrap;">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('user.edit', $user['id_pelanggan']) }}" 
                                        class="btn-action btn-edit" title="Edit User" style="display: inline-block; padding: 6px; border-radius: 5px; background: #ffc107; color: white; margin-right: 5px; line-height: 1;">
                                        {{-- IKON EDIT: Ditambahkan font-size 18px --}}
                                        <i class='bx bxs-edit' style="font-size: 18px;"></i>
                                    </a>

                                    {{-- Tombol Delete (JS Confirmation) --}}
                                    <form action="{{ route('user.destroy', $user['id_pelanggan']) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Delete User" 
                                                class="delete-button" 
                                                data-id="{{ $user['id_pelanggan'] }}"
                                                style="border: none; padding: 6px; border-radius: 5px; background: #dc3545; color: white; cursor: pointer;">
                                            {{-- IKON DELETE: Ditambahkan font-size 18px --}}
                                            <i class='bx bxs-trash' style="color: white; font-size: 18px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan='6' style="text-align: center;">Tidak ada data user.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

@endsection

@push('scripts')
<script src="{{ asset('js/delete.js') }}"></script>
@endpush
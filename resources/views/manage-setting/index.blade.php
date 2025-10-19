@extends('layouts.admin') 

@section('content')
<main class="container-fluid mt-4"> 
    
    
    <div class="mb-4">
        <h1 class="mb-0">Settings</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Settings</li>
            </ol>
    </div>

    
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        
        {{-- Profile Settings --}}
        <div class="col-lg-8 col-md-12">
            <div class="card shadow-sm">
                {{-- HEADER CARD: Bold --}}
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0 fw-bold">Profile Settings</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.update') }}">
                        @csrf 
                        
                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold">Username</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="{{ $settingsData['username'] ?? 'admin_user' }}" disabled>
                            <div class="form-text">Hubungi Administrator untuk mengubah Username.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ $settingsData['email'] ?? 'admin@example.com' }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Biarkan kosong jika tidak ingin mengubah">
                            <div class="form-text">Biarkan kosong jika tidak ingin mengubah password.</div>
                        </div>
                        
                        {{-- Tombol Save Changes dengan ikon Boxicons bxs-save --}}
                        <button type="submit" class="btn btn-success mt-3">
                            <i class='bx bxs-save me-2'></i>Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Notification Settings --}}
        <div class="col-lg-4 col-md-12">
            <div class="card shadow-sm">
                {{-- HEADER CARD: Bold --}}
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0 fw-bold">Notification Settings</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.update') }}">
                        @csrf 
                        
                        <div class="d-flex justify-content-between align-items-start mb-3 border-bottom pb-3">
                            <div>
                                <h6 class="mb-0">Email Notifications</h6>
                                <small class="text-muted">Dapatkan pembaruan dan notifikasi melalui email.</small>
                            </div>
                            <div class="form-check form-switch ms-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="email_notifications" name="email_notifications" 
                                       value="1" {{ ($settingsData['email_notifications'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label visually-hidden" for="email_notifications">Email</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-start mb-4 border-bottom pb-3">
                            <div>
                                <h6 class="mb-0">Push Notifications</h6>
                                <small class="text-muted">Dapatkan notifikasi di browser Anda.</small>
                            </div>
                            <div class="form-check form-switch ms-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="push_notifications" name="push_notifications" 
                                       value="1" {{ ($settingsData['push_notifications'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label visually-hidden" for="push_notifications">Push</label>
                            </div>
                        </div>
                        
                        {{-- Tombol Save Notifications dengan ikon Boxicons bxs-bell --}}
                        <button type="submit" class="btn btn-info bg-success w-100 text-white">
                            <i class='bx bxs-bell me-2'></i>Save Notifications
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
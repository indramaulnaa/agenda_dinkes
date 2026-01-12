@extends('layouts.admin')

@section('title', 'Settings')
@section('page_title', 'Pengaturan Akun')
@section('page_subtitle', 'Kelola profil dan keamanan akun anda')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-4">Edit Profil & Password</h5>
                
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control bg-light" value="{{ Auth::user()->email }}" readonly>
                        <div class="form-text">Email tidak dapat diubah demi keamanan.</div>
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3 text-secondary">Ganti Password (Opsional)</h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti password">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="bi bi-save me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
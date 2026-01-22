@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page_title', 'Settings')
@section('page_subtitle', 'Pengaturan aplikasi dan sistem')

@section('content')

<div class="row">
    @if(Auth::user()->role === 'super_admin')
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-danger bg-opacity-10 p-3 rounded-3 text-danger">
                        <i class="bi bi-shield-lock-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Keamanan Website (PIN)</h5>
                        <p class="text-muted small mb-0">Kode akses untuk halaman publik pegawai</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-3">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">PIN Saat Ini</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key"></i></span>
                            <input type="text" name="site_pin" class="form-control border-start-0" 
                                value="{{ \App\Models\Setting::where('key', 'site_pin')->value('value') ?? '123456' }}" 
                                placeholder="Masukkan PIN Baru">
                        </div>
                        <div class="form-text text-danger small">
                            <i class="bi bi-exclamation-circle"></i> Hati-hati! Mengubah PIN akan mempengaruhi akses seluruh pegawai.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold">
                        <i class="bi bi-save me-2"></i> Simpan PIN Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                        <i class="bi bi-info-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Informasi Aplikasi</h5>
                        <p class="text-muted small mb-0">Versi dan status sistem</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Nama Aplikasi</span>
                        <span class="fw-bold text-dark">Agenda Dinkes</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Versi</span>
                        <span class="badge bg-light text-dark border">v1.0.0 (Release)</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Status Notifikasi WA</span>
                        <span class="badge bg-success-subtle text-success">Aktif</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Zona Waktu</span>
                        <span class="text-muted">{{ config('app.timezone') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
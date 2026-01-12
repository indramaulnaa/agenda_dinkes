@extends('layouts.admin')

@section('title', 'Tambah Staff')
@section('page_title', 'Tambah Staff Baru')
@section('page_subtitle', 'Masukkan data pegawai baru ke dalam sistem')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                
                <form action="{{ route('staff.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIP (Nomor Induk Pegawai)</label>
                        <input type="number" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}" placeholder="Contoh: 19800101..." required>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nama beserta gelar" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Jabatan</label>
                            <input type="text" name="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position') }}" placeholder="Contoh: Kepala Bidang..." required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">No. HP / WhatsApp</label>
                            <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Contoh: 08123456789" required>
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem">*Pastikan nomor aktif WhatsApp</small>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email (Opsional)</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@dinkes.go.id">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('staff.index') }}" class="btn btn-light border px-4">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="bi bi-save me-2"></i> Simpan Data
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
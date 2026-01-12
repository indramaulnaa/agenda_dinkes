@extends('layouts.admin')

@section('title', 'Edit Staff')
@section('page_title', 'Edit Data Staff')
@section('page_subtitle', 'Perbarui informasi data pegawai')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                
                <form action="{{ route('staff.update', $staff->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIP</label>
                        <input type="number" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $staff->nip) }}" required>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $staff->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Jabatan</label>
                            <input type="text" name="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position', $staff->position) }}" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">No. HP / WhatsApp</label>
                            <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $staff->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email (Opsional)</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $staff->email) }}">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('staff.index') }}" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-warning text-white px-4 fw-bold">
                            <i class="bi bi-pencil-square me-2"></i> Update Data
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
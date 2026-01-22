@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page_title', 'Manajemen User')
@section('page_subtitle', 'Kelola akun Admin dan Super Admin')

@section('content')

<style>
    /* --- CUSTOM STYLES --- */
    .user-card {
        background: white; border: none; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04); overflow: hidden;
    }
    
    /* Tombol Tambah (Biru Gradient) */
    .btn-add-user {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        border: none; color: white; font-weight: 700; padding: 10px 25px;
        border-radius: 50px; display: inline-flex; align-items: center; gap: 8px;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3); transition: all 0.3s ease;
    }
    .btn-add-user:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4); color: white; }

    /* Table Styles */
    .table thead th {
        background-color: #f8f9fa; color: #6c757d; font-weight: 700; font-size: 0.75rem;
        text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 2px solid #eaeaea; padding: 15px 25px;
    }
    .table tbody td {
        padding: 15px 25px; vertical-align: middle; border-bottom: 1px solid #f9f9f9; font-size: 0.95rem; color: #374151;
    }
    .table tbody tr:hover { background-color: #fcfcfc; }

    /* Avatar Circle */
    .avatar-circle {
        width: 38px; height: 38px; background-color: #e9ecef; color: #495057;
        border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 0.9rem; margin-right: 12px; text-transform: uppercase;
    }
    
    /* Badges Role */
    .badge-role { padding: 6px 12px; border-radius: 50px; font-weight: 700; font-size: 0.7rem; letter-spacing: 0.5px; }
    .badge-super { background-color: #dc3545; color: white; box-shadow: 0 2px 5px rgba(220, 53, 69, 0.3); }
    .badge-admin { background-color: #0d6efd; color: white; box-shadow: 0 2px 5px rgba(13, 110, 253, 0.3); }
    
    /* Tombol Aksi Kecil */
    .btn-action { width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; border: none; transition: 0.2s; }
    .btn-edit { background-color: #fff3cd; color: #ffc107; }
    .btn-edit:hover { background-color: #ffc107; color: white; }
    .btn-delete { background-color: #f8d7da; color: #dc3545; }
    .btn-delete:hover { background-color: #dc3545; color: white; }

    /* Form Modal */
    .form-label-bold { font-weight: 600; font-size: 0.9rem; color: #374151; }
    .form-control, .form-select { border-radius: 10px; padding: 10px 15px; }
    .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15); }
</style>

<div class="d-flex justify-content-end mb-4">
    <button class="btn-add-user" data-bs-toggle="modal" data-bs-target="#createUserModal">
        <i class="bi bi-person-plus-fill"></i> Tambah User Baru
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" style="border-radius: 12px;">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="user-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="30%">Nama Lengkap</th>
                    <th width="30%">Email Address</th>
                    <th width="15%" class="text-center">Role</th>
                    <th width="20%" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                <tr>
                    <td class="text-center fw-bold text-secondary">{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $user->name }}</div>
                                @if($user->id == Auth::id())
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 0.65rem;">Anda</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-secondary">{{ $user->email }}</td>
                    <td class="text-center">
                        @if($user->role == 'super_admin')
                            <span class="badge badge-role badge-super">SUPER ADMIN</span>
                        @else
                            <span class="badge badge-role badge-admin">ADMIN BIASA</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" title="Edit User">
                                <i class="bi bi-pencil-fill"></i>
                            </button>

                            @if($user->id != Auth::id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Hapus User">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            @else
                                <button class="btn-action btn-delete" style="opacity: 0.3; cursor: not-allowed;" disabled>
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>

                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header border-bottom-0 pb-0">
                                <h5 class="modal-title fw-bold">Edit User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('users.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="mb-3">
                                        <label class="form-label-bold">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label-bold">Email Address</label>
                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label-bold">Role Akses</label>
                                        <select name="role" class="form-select">
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin Biasa</option>
                                            <option value="super_admin" {{ $user->role == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label-bold">Password Baru <small class="text-muted fw-normal">(Kosongkan jika tidak ingin ubah)</small></label>
                                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                                    </div>
                                    
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-warning text-white fw-bold rounded-pill px-4">Update User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Admin Yankes" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-bold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="nama@dinkes.go.id" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-bold">Role Akses <span class="text-danger">*</span></label>
                        <select name="role" class="form-select">
                            <option value="admin">Admin Biasa</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label-bold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4">Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
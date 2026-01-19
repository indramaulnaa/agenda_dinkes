@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page_title', 'Manajemen User')
@section('page_subtitle', 'Kelola akun Admin dan Super Admin')

@section('content')

    <div class="d-flex justify-content-end mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-person-plus-fill me-2"></i> Tambah User Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Nama Lengkap</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Role</th>
                            <th class="pe-4 py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $user->name }}</div>
                                @if($user->id == Auth::id())
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 0.7rem;">Anda</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role == 'super_admin')
                                    <span class="badge bg-danger">SUPER ADMIN</span>
                                @else
                                    <span class="badge bg-primary">Admin Biasa</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <button class="btn btn-sm btn-outline-warning me-1 btn-edit"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}"
                                    data-role="{{ $user->role }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                
                                @if($user->id != Auth::id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus user ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Login</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="text" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Role (Hak Akses)</label>
                            <select name="role" class="form-select">
                                <option value="admin">Admin Biasa</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Edit User & Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditUser" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Login</label>
                            <input type="email" name="email" id="editEmail" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Role (Hak Akses)</label>
                            <select name="role" id="editRole" class="form-select">
                                <option value="admin">Admin Biasa</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="alert alert-warning border-0 small">
                            <i class="bi bi-key-fill me-1"></i> <b>Reset Password</b><br>
                            Isi kolom di bawah ini HANYA jika ingin mengganti password user. Jika tidak, biarkan kosong.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-danger">Password Baru (Opsional)</label>
                            <input type="text" name="password" class="form-control border-danger" placeholder="Kosongkan jika tidak ingin ubah password">
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning text-white">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Script untuk mengisi Modal Edit secara otomatis
            $('.btn-edit').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var email = $(this).data('email');
                var role = $(this).data('role');

                // Isi Form
                $('#editName').val(name);
                $('#editEmail').val(email);
                $('#editRole').val(role);

                // Set Action URL Form
                var url = "{{ route('users.index') }}/" + id;
                $('#formEditUser').attr('action', url);

                // Tampilkan Modal
                new bootstrap.Modal(document.getElementById('editModal')).show();
            });
        });
    </script>

@endsection
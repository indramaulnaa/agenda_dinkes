@extends('layouts.admin')

@section('title', 'Data Staff')
@section('page_title', 'Data Staff / Pegawai')
@section('page_subtitle', 'Kelola data pegawai Dinas Kesehatan')

@section('content')

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        
        <div class="card-header bg-white py-3">
            <div class="row g-3 align-items-center justify-content-between">
                
                <div class="col-12 col-md-auto">
                    <h6 class="mb-0 fw-bold text-secondary">Daftar Pegawai</h6>
                </div>

                <div class="col-12 col-md-auto">
                    <div class="d-flex gap-2">
                        <form action="{{ route('staff.index') }}" method="GET" class="d-flex">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari Nama / NIP / HP..." value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary btn-sm" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>

                        <a href="{{ route('staff.create') }}" class="btn btn-primary btn-sm px-3 fw-bold">
                            <i class="bi bi-person-plus-fill me-2"></i> Tambah
                        </a>
                    </div>
                </div>

            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="px-4 py-3">Nama Pegawai</th>
                            <th class="py-3">NIP</th>
                            <th class="py-3">Jabatan</th>
                            <th class="py-3">No. HP / WA</th>
                            <th class="px-4 py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $s)
                        <tr>
                            <td class="px-4">
                                <div class="fw-bold text-dark">{{ $s->name }}</div>
                                <small class="text-muted">{{ $s->email ?? '-' }}</small>
                            </td>
                            <td>{{ $s->nip }}</td>
                            <td><span class="badge bg-info text-dark bg-opacity-10 border border-info px-3">{{ $s->position }}</span></td>
                            <td>
                                <span class="text-success fw-bold"><i class="bi bi-whatsapp"></i> {{ $s->phone }}</span>
                            </td>
                            <td class="px-4 text-end">
                                <a href="{{ route('staff.edit', $s->id) }}" class="btn btn-sm btn-warning text-white me-1"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('staff.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-search display-6 d-block mb-3"></i>
                                Data tidak ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white py-3">
            {{ $staff->links() }}
        </div>

    </div>

@endsection
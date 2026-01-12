@extends('layouts.admin')

@section('title', 'Edit Agenda')
@section('page_title', 'Edit Agenda')
@section('page_subtitle', 'Perbarui detail agenda kegiatan')

@section('content')

    <style>
        /* Style khusus Form */
        .form-card { background: white; border-radius: 12px; border: 1px solid #eaeaea; padding: 30px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .form-label { font-weight: 600; color: #374151; font-size: 0.9rem; }
        .form-control { padding: 10px 15px; border-radius: 8px; border: 1px solid #d1d5db; }
        
        /* Tombol Update (Warna Kuning/Warning) */
        .btn-simpan { background-color: #d97706; border: none; padding: 10px 30px; font-weight: 600; transition: 0.2s; }
        .btn-simpan:hover { background-color: #b45309ff; }
    </style>

    <div class="row justify-content-center">
        <div class="col-md-12">
            
            <div class="form-card">
                <form action="{{ route('agenda.update', $agenda->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label">Nama Kegiatan / Agenda</label>
                        <input type="text" name="title" class="form-control" value="{{ $agenda->title }}" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Waktu Mulai</label>
                            <input type="datetime-local" name="start_time" class="form-control" value="{{ $agenda->start_time }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Waktu Selesai</label>
                            <input type="datetime-local" name="end_time" class="form-control" value="{{ $agenda->end_time }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="location" class="form-control" value="{{ $agenda->location }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea name="description" class="form-control" rows="4">{{ $agenda->description }}</textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('agenda.index') }}" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-warning btn-simpan text-white">Update Agenda</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection
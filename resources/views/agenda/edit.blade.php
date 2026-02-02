@extends('layouts.admin')

@section('title', 'Edit Agenda')
@section('page_title', 'Edit Agenda')
@section('page_subtitle', 'Perbarui detail agenda kegiatan')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Style khusus Form */
        .form-card { background: white; border-radius: 12px; border: 1px solid #eaeaea; padding: 30px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .form-label { font-weight: 600; color: #374151; font-size: 0.9rem; }
        .form-control, .form-select { padding: 10px 15px; border-radius: 8px; border: 1px solid #d1d5db; }
        
        /* Tombol Update (Warna Kuning/Warning) */
        .btn-simpan { background-color: #d97706; border: none; padding: 10px 30px; font-weight: 600; transition: 0.2s; }
        .btn-simpan:hover { background-color: #b45309; }

        /* Custom Style Select2 */
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
            background-color: #d97706; color: white; border: none; /* Warna Orange sesuai tombol edit */
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-md-12">
            
            <div class="form-card">
                <form action="{{ route('agenda.update', $agenda->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label">Nama Kegiatan / Agenda</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $agenda->title) }}" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Waktu Mulai</label>
                            <input type="datetime-local" name="start_time" class="form-control" 
                                value="{{ \Carbon\Carbon::parse($agenda->start_time)->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Waktu Selesai</label>
                            <input type="datetime-local" name="end_time" class="form-control" 
                                value="{{ $agenda->end_time ? \Carbon\Carbon::parse($agenda->end_time)->format('Y-m-d\TH:i') : '' }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $agenda->location) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Peserta / Tujuan WhatsApp <span class="text-danger">*</span></label>
                        <select name="participants[]" class="form-select select2-peserta" multiple="multiple" required>
                            @php
                                // Ambil data peserta lama (array) agar bisa dicek satu per satu
                                $currentParticipants = $agenda->participants ?? [];
                            @endphp

                            <option value="Seluruh Pegawai Dinas Kesehatan" {{ in_array('Seluruh Pegawai Dinas Kesehatan', $currentParticipants) ? 'selected' : '' }}>Seluruh Pegawai Dinas Kesehatan</option>
                            
                            <option value="Subbagian Umum & Kepegawaian" {{ in_array('Subbagian Umum & Kepegawaian', $currentParticipants) ? 'selected' : '' }}>Subbagian Umum & Kepegawaian</option>
                            
                            <option value="Subbagian Program & Keuangan" {{ in_array('Subbagian Program & Keuangan', $currentParticipants) ? 'selected' : '' }}>Subbagian Program & Keuangan</option>
                            
                            <option value="Puskesmas" {{ in_array('Puskesmas', $currentParticipants) ? 'selected' : '' }}>Puskesmas</option>
                            
                            <option value="Bidang Kesehatan Masyarakat (Kesmas)" {{ in_array('Bidang Kesehatan Masyarakat (Kesmas)', $currentParticipants) ? 'selected' : '' }}>Bidang Kesehatan Masyarakat (Kesmas)</option>
                            
                            <option value="Bidang Pencegahan & Pengendalian Penyakit (P2P)" {{ in_array('Bidang Pencegahan & Pengendalian Penyakit (P2P)', $currentParticipants) ? 'selected' : '' }}>Bidang Pencegahan & Pengendalian Penyakit (P2P)</option>
                            
                            <option value="Bidang Pelayanan Kesehatan (Yankes)" {{ in_array('Bidang Pelayanan Kesehatan (Yankes)', $currentParticipants) ? 'selected' : '' }}>Bidang Pelayanan Kesehatan (Yankes)</option>
                            
                            <option value="Kepala Dinas & Pejabat Struktural" {{ in_array('Kepala Dinas & Pejabat Struktural', $currentParticipants) ? 'selected' : '' }}>Kepala Dinas & Pejabat Struktural</option>
                        </select>
                        <div class="form-text text-muted">Sesuaikan peserta jika ada perubahan.</div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch p-3 bg-light rounded border">
                            <input class="form-check-input" type="checkbox" name="is_whatsapp_notify" value="1" id="waCheck" 
                                {{ $agenda->is_whatsapp_notify ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-success" for="waCheck">
                                <i class="bi bi-whatsapp me-2"></i> Kirim Notifikasi WhatsApp Otomatis
                            </label>
                            
                            @if($agenda->notification_sent)
                                <div class="badge bg-success ms-2">Sudah Terkirim</div>
                            @else
                                <div class="badge bg-secondary ms-2">Belum Terkirim</div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $agenda->description) }}</textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('agenda.index') }}" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-warning btn-simpan text-white">Update Agenda</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.select2-peserta').select2({
                theme: 'bootstrap-5',
                placeholder: "Klik untuk mengubah peserta...",
                allowClear: true
            });
        });
    </script>

@endsection
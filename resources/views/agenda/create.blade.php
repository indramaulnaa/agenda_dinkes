@extends('layouts.admin')

@section('title', 'Buat Agenda Baru')
@section('page_title', 'Buat Agenda Baru')
@section('page_subtitle', 'Tambahkan agenda kegiatan baru ke dalam sistem')

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
        .form-control:focus, .form-select:focus { border-color: #198754; box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1); }
        
        /* Tombol Simpan (Warna Hijau) */
        .btn-simpan { background-color: #198754; border: none; padding: 10px 30px; font-weight: 600; transition: 0.2s; }
        .btn-simpan:hover { background-color: #146c43; }

        /* Custom Style Select2 agar Hijau */
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
            background-color: #198754; color: white; border: none;
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-md-12">
            
            <div class="form-card">
                <form action="{{ route('agenda.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label">Nama Kegiatan / Agenda</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Rapat Koordinasi Stunting" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Waktu Mulai</label>
                            <input type="datetime-local" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Waktu Selesai (Opsional)</label>
                            <input type="datetime-local" name="end_time" class="form-control">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="location" class="form-control" placeholder="Contoh: Aula Utama Dinkes" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Peserta / Tujuan WhatsApp <span class="text-danger">*</span></label>
                        <select name="participants[]" class="form-select select2-peserta" multiple="multiple" required>
                            <option value="Seluruh Pegawai Dinas Kesehatan">Seluruh Pegawai Dinas Kesehatan</option>
                            <option value="Subbagian Umum & Kepegawaian">Subbagian Umum & Kepegawaian</option>
                            <option value="Subbagian Program & Keuangan">Subbagian Program & Keuangan</option>
                            <option value="Puskesmas">Puskesmas</option>
                            <option value="Bidang Kesehatan Masyarakat (Kesmas)">Bidang Kesehatan Masyarakat (Kesmas)</option>
                            <option value="Bidang Pencegahan & Pengendalian Penyakit (P2P)">Bidang Pencegahan & Pengendalian Penyakit (P2P)</option>
                            <option value="Bidang Pelayanan Kesehatan (Yankes)">Bidang Pelayanan Kesehatan (Yankes)</option>
                            <option value="Kepala Dinas & Pejabat Struktural">Kepala Dinas & Pejabat Struktural</option>
                        </select>
                        <div class="form-text text-muted">Bisa pilih lebih dari satu bidang.</div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch p-3 bg-light rounded border">
                            <input class="form-check-input" type="checkbox" name="is_whatsapp_notify" value="1" id="waCheck" checked>
                            <label class="form-check-label fw-bold text-success" for="waCheck">
                                <i class="bi bi-whatsapp me-2"></i> Kirim Notifikasi WhatsApp Otomatis
                            </label>
                            <div class="small text-secondary ms-1">Pesan akan dikirim 30 menit sebelum acara dimulai.</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Catatan untuk peserta..."></textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('agenda.index') }}" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-primary btn-simpan text-white">Simpan Agenda</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.select2-peserta').select2({
                theme: 'bootstrap-5',
                placeholder: "Klik untuk memilih peserta...",
                allowClear: true
            });
        });
    </script>

@endsection 
@extends('layouts.admin')

@section('title', 'Laporan Kegiatan')
@section('page_title', 'Laporan & Rekap')
@section('page_subtitle', 'Cetak dan unduh laporan agenda dinas')

@section('content')

<style>
    /* Card Style */
    .report-card {
        background: white; border: none; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04); overflow: hidden;
    }
    
    /* Header Card (Filter Area) */
    .filter-section {
        background-color: #f8f9fa; padding: 25px; border-bottom: 1px solid #eee;
    }
    
    /* Form Labels */
    .form-label-bold { font-weight: 700; color: #4b5563; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    
    /* Inputs */
    .form-control, .form-select {
        border-radius: 10px; border: 1px solid #dee2e6; padding: 10px 15px;
        font-size: 0.95rem; transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #198754; box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
    }

    /* Buttons */
    .btn-pill { border-radius: 50px; padding: 10px 25px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; }
    
    .btn-filter { background-color: #0d6efd; border: none; color: white; box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2); }
    .btn-filter:hover { background-color: #0b5ed7; transform: translateY(-2px); color: white; }
    
    .btn-print { background-color: #dc3545; border: none; color: white; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2); }
    .btn-print:hover { background-color: #bb2d3b; transform: translateY(-2px); color: white; }

    /* Table Styles */
    .table thead th {
        background-color: #fff; color: #6c757d; font-weight: 700; font-size: 0.75rem;
        text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 2px solid #f3f4f6; padding: 15px 20px;
    }
    .table tbody td {
        padding: 15px 20px; vertical-align: middle; border-bottom: 1px solid #f9f9f9; font-size: 0.95rem; color: #374151;
    }
    .table tbody tr:hover { background-color: #f8f9fa; }

    /* Badges */
    .badge-pill { padding: 6px 12px; border-radius: 50px; font-weight: 700; font-size: 0.7rem; letter-spacing: 0.5px; }
    .badge-agenda { background-color: #d1e7dd; color: #146c43; }
    .badge-room { background-color: #ffe5d0; color: #fd7e14; }
    
    .date-box {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        width: 60px; height: 60px; background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef;
        text-align: center;
    }
    .date-day { font-size: 1.2rem; font-weight: 800; line-height: 1; color: #212529; }
    .date-month { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; color: #6c757d; }
</style>

<div class="report-card mb-5">
    
    <div class="filter-section">
        <form action="{{ route('reports.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label-bold"><i class="bi bi-calendar-event me-1"></i> Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label-bold"><i class="bi bi-calendar-event me-1"></i> Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label-bold"><i class="bi bi-funnel me-1"></i> Tipe Laporan</label>
                    <select name="type" class="form-select">
                        <option value="all" {{ $type == 'all' ? 'selected' : '' }}>Semua Kegiatan</option>
                        <option value="general" {{ $type == 'general' ? 'selected' : '' }}>Agenda Umum</option>
                        <option value="meeting_room" {{ $type == 'meeting_room' ? 'selected' : '' }}>Booking Ruangan</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-filter btn-pill flex-grow-1 justify-content-center">
                        <i class="bi bi-search"></i> Tampilkan
                    </button>
                    <a href="{{ route('reports.print', ['start_date' => $startDate, 'end_date' => $endDate, 'type' => $type]) }}" target="_blank" class="btn btn-print btn-pill">
                        <i class="bi bi-file-earmark-pdf-fill"></i> Cetak
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="30%">Nama Kegiatan</th>
                    <th width="20%">Lokasi</th>
                    <th width="15%">Jenis / Status</th>
                    <th width="15%">Dibuat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agendas as $index => $agenda)
                <tr>
                    <td class="text-center fw-bold text-secondary">{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="date-box">
                                <span class="date-day">{{ $agenda->start_time->format('d') }}</span>
                                <span class="date-month">{{ $agenda->start_time->format('M') }}</span>
                            </div>
                            <div style="font-size: 0.85rem;" class="fw-bold text-muted">
                                {{ $agenda->start_time->format('H:i') }} <br>
                                {{ $agenda->end_time ? $agenda->end_time->format('H:i') : 'Selesai' }}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $agenda->title }}</div>
                        @if($agenda->description)
                            <div class="text-muted small text-truncate" style="max-width: 300px;">
                                {{ $agenda->description }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center text-secondary small fw-medium">
                            <i class="bi bi-geo-alt-fill me-2 text-danger opacity-50"></i>
                            {{ $agenda->location }}
                        </div>
                    </td>
                    <td>
                        @if($agenda->type == 'meeting_room')
                            <span class="badge badge-pill badge-room">BOOKING RUANGAN</span>
                        @else
                            <span class="badge badge-pill badge-agenda">AGENDA UMUM</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-secondary fw-bold" style="width: 28px; height: 28px; font-size: 0.7rem;">
                                {{ strtoupper(substr($agenda->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <span class="small fw-semibold text-dark">{{ $agenda->user->name ?? 'Unknown' }}</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="py-4">
                            <i class="bi bi-clipboard-x text-muted display-4 opacity-25"></i>
                            <p class="text-muted fw-bold mt-3 mb-1">Tidak ada data ditemukan</p>
                            <p class="text-secondary small">Coba ubah filter tanggal atau tipe laporan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
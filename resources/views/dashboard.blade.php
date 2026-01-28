@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')
@section('page_subtitle', 'Ringkasan aktivitas dan statistik agenda Dinas Kesehatan')

@section('content')

<style>
    /* --- CUSTOM DASHBOARD STYLES --- */
    .stat-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }
    
    .stat-card::after {
        content: ''; position: absolute; top: -20px; right: -20px;
        width: 100px; height: 100px; border-radius: 50%; opacity: 0.1; z-index: 0;
    }
    .card-green::after { background: #198754; }
    .card-blue::after { background: #0d6efd; }
    .card-orange::after { background: #fd7e14; }
    .card-purple::after { background: #6f42c1; }

    .icon-box {
        width: 60px; height: 60px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; z-index: 1;
    }
    .bg-soft-green { background-color: #d1e7dd; color: #198754; }
    .bg-soft-blue { background-color: #cfe2ff; color: #0d6efd; }
    .bg-soft-orange { background-color: #ffe5d0; color: #fd7e14; }
    .bg-soft-purple { background-color: #e2d9f3; color: #6f42c1; }

    .stat-number { font-size: 2.2rem; font-weight: 800; color: #212529; line-height: 1; margin-bottom: 5px; z-index: 1; position: relative; }
    .stat-label { color: #6c757d; font-weight: 500; font-size: 0.95rem; z-index: 1; position: relative; }

    .custom-table-card {
        border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04); overflow: hidden;
    }
    .table-header-styled {
        background-color: #fff; border-bottom: 1px solid #f0f0f0; padding: 20px 25px;
        display: flex; justify-content: space-between; align-items: center;
    }
    .table thead th {
        background-color: #f9fafb; color: #6c757d; font-weight: 700; font-size: 0.75rem;
        text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid #eaeaea; padding: 15px 25px;
    }
    .table tbody td {
        padding: 15px 25px; vertical-align: middle; border-bottom: 1px solid #f9f9f9; font-size: 0.95rem;
    }
    .table tbody tr:hover { background-color: #f8f9fa; }
    
    /* FIX BADGE (PILL SHAPE + NO WRAP) */
    .badge-soft { 
        padding: 8px 16px; 
        border-radius: 50px; /* Kembali ke bentuk Pill (Bulat Lonjong) */
        font-weight: 700; 
        font-size: 0.75rem; 
        white-space: nowrap; /* Mencegah teks turun ke bawah */
        display: inline-block;
    }
    
    /* Warna Hijau untuk Agenda */
    .badge-soft-success { 
        background-color: #d1e7dd; 
        color: #146c43; 
    }
    
    /* Warna Merah Muda untuk Meeting Room (Sesuai Screenshot Anda) */
    .badge-soft-danger { 
        background-color: #f8d7da; 
        color: #842029; 
    }
    
    .avatar-circle {
        width: 32px; height: 32px; background-color: #e9ecef; color: #495057;
        border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.85rem; margin-right: 10px;
    }
</style>

<div class="row g-4 mb-5">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card card-green">
            <div>
                <div class="stat-number">{{ $agendaToday }}</div>
                <div class="stat-label">Agenda Hari Ini</div>
            </div>
            <div class="icon-box bg-soft-green">
                <i class="bi bi-calendar-check"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card card-blue">
            <div>
                <div class="stat-number">{{ $agendaMonth }}</div>
                <div class="stat-label">Agenda Bulan Ini</div>
            </div>
            <div class="icon-box bg-soft-blue">
                <i class="bi bi-calendar-month"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card card-orange">
            <div>
                <div class="stat-number">{{ $agendaUpcoming }}</div>
                <div class="stat-label">Akan Datang</div>
            </div>
            <div class="icon-box bg-soft-orange">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card card-purple">
            <div>
                <div class="stat-number">{{ $agendaArchived }}</div>
                <div class="stat-label">Arsip</div>
            </div>
            <div class="icon-box bg-soft-purple">
                <i class="bi bi-archive"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card custom-table-card">
            <div class="table-header-styled">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Agenda Baru Ditambahkan</h5>
                    <p class="text-muted small mb-0">Daftar kegiatan yang baru saja diinput ke sistem hari ini</p>
                </div>
                <a href="{{ route('agenda.index') }}" class="btn btn-outline-success btn-sm px-3 fw-bold rounded-pill">
                    Lihat Kalender <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th width="30%">KEGIATAN</th>
                            <th width="20%">WAKTU PELAKSANAAN</th>
                            <th width="20%">LOKASI</th>
                            <th width="20%">DIBUAT OLEH</th>
                            <th width="10%" class="text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAgendas as $agenda)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $agenda->title }}</div>
                                @if($agenda->description)
                                    <div class="text-muted small text-truncate" style="max-width: 250px;">
                                        {{ $agenda->description }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $agenda->start_time->isoFormat('D MMM Y') }}</div>
                                <div class="small text-secondary">
                                    {{ $agenda->start_time->format('H:i') }}
                                    @if($agenda->end_time) - {{ $agenda->end_time->format('H:i') }} @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-secondary">
                                    <i class="bi bi-geo-alt-fill me-2 text-danger opacity-50"></i>
                                    {{ $agenda->location }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($agenda->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="small fw-bold text-dark">{{ $agenda->user->name ?? 'Unknown' }}</span>
                                        <span class="text-muted" style="font-size: 0.7rem;">{{ $agenda->user->email ?? '' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($agenda->type == 'meeting_room')
                                    <span class="badge-soft badge-soft-danger">Meeting Room</span>
                                @else
                                    <span class="badge-soft badge-soft-success">Agenda</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" alt="Empty" style="width: 60px; opacity: 0.5; margin-bottom: 15px;">
                                    <p class="text-muted fw-medium mb-0">Belum ada agenda yang ditambahkan hari ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
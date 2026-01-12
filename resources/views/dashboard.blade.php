@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')
@section('page_subtitle', 'Ringkasan statistik agenda Dinas Kesehatan')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon icon-green"><i class="bi bi-calendar-event"></i></div>
                <div class="stat-info">
                    <h3>{{ $agendaHariIni ?? 0 }}</h3>
                    <p>Agenda Hari Ini</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon icon-blue"><i class="bi bi-calendar-month"></i></div>
                <div class="stat-info">
                    <h3>{{ $agendaBulanIni ?? 0 }}</h3>
                    <p>Agenda Bulan Ini</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon icon-yellow"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-info">
                    <h3>{{ $agendaAkanDatang ?? 0 }}</h3>
                    <p>Akan Datang</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon icon-purple"><i class="bi bi-archive"></i></div>
                <div class="stat-info">
                    <h3>{{ $totalAgenda ?? 0 }}</h3>
                    <p>Total Arsip</p>
                </div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Agenda Baru Ditambahkan (Hari Ini)</h5>
            <a href="{{ route('agenda.index') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th>KEGIATAN</th>
                        <th>TANGGAL & WAKTU</th>
                        <th>LOKASI</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestAgendas ?? [] as $agenda)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $agenda->title }}</div>
                        </td>

                        <td>
                            <div class="text-secondary small">
                                <div><i class="bi bi-calendar me-1"></i> {{ \Carbon\Carbon::parse($agenda->start_time)->locale('id')->isoFormat('D MMM Y') }}</div>
                                
                                <div class="mt-1 fw-bold text-dark">
                                    <i class="bi bi-clock me-1 text-secondary"></i> 
                                    {{ \Carbon\Carbon::parse($agenda->start_time)->format('H:i') }}
                                    @if($agenda->end_time)
                                        - {{ \Carbon\Carbon::parse($agenda->end_time)->format('H:i') }}
                                    @endif
                                    WIB
                                </div>
                            </div>
                        </td>

                        <td class="text-secondary">{{ $agenda->location }}</td>

                        <td>
                            <span class="status-badge">Aktif</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada agenda yang ditambahkan hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
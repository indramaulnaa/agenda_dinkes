<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; overflow-x: hidden; }

        /* --- LAYOUT SIDEBAR (Sama Persis) --- */
        .wrapper { display: flex; width: 100%; align-items: stretch; }
        
        #sidebar {
            min-width: 260px; max-width: 260px; background: #ffffff; color: #333;
            min-height: 100vh; border-right: 1px solid #eaeaea; padding: 20px;
            position: fixed; height: 100%; overflow-y: auto; z-index: 100;
        }
        
        #content {
            width: 100%; margin-left: 260px; padding: 0; min-height: 100vh;
        }

        .sidebar-brand {
            display: flex; align-items: center; gap: 12px;
            padding-bottom: 30px; margin-bottom: 10px; border-bottom: 1px solid #f0f0f0;
        }
        .brand-logo {
            width: 40px; height: 40px; background: #198754; color: white;
            border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        }
        .brand-text h5 { margin: 0; font-weight: 700; font-size: 1rem; color: #111827; }
        .brand-text p { margin: 0; font-size: 0.75rem; color: #6b7280; }

        .sidebar-menu { list-style: none; padding: 0; margin-top: 20px; }
        .sidebar-menu li { margin-bottom: 8px; }
        .sidebar-menu a {
            display: flex; align-items: center; gap: 12px; padding: 12px 16px;
            color: #4b5563; text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 0.95rem; transition: 0.2s;
        }
        .sidebar-menu a:hover { background-color: #f3f4f6; color: #111827; }
        
        /* Menu Aktif Hijau */
        .sidebar-menu a.active {
            background-color: #198754; color: white; box-shadow: 0 4px 6px rgba(25, 135, 84, 0.15);
        }

        .user-profile {
            position: absolute; bottom: 20px; left: 20px; right: 20px;
            display: flex; align-items: center; gap: 10px; padding-top: 20px; border-top: 1px solid #f0f0f0;
        }
        .avatar {
            width: 36px; height: 36px; background: #e5e7eb; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-weight: 700; color: #374151;
        }

        /* --- HEADER --- */
        .main-header {
            background: white; padding: 25px 40px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eaeaea;
        }
        .page-title h4 { font-weight: 700; margin-bottom: 4px; font-size: 1.5rem; color: #111827; }
        .page-title p { color: #6b7280; margin-bottom: 0; font-size: 0.9rem; }

        /* --- DASHBOARD SPECIFIC --- */
        .stat-card {
            background: white; padding: 25px; border-radius: 12px; border: 1px solid #eaeaea;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 20px;
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-5px); border-color: #198754; }
        
        .stat-icon {
            width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;
        }
        .icon-blue { background: #e0f2fe; color: #0284c7; }
        .icon-green { background: #dcfce7; color: #16a34a; }
        .icon-yellow { background: #fef9c3; color: #ca8a04; }
        .icon-purple { background: #f3e8ff; color: #9333ea; }

        .stat-info h3 { font-weight: 700; margin: 0; font-size: 1.8rem; color: #111827; }
        .stat-info p { margin: 0; color: #6b7280; font-size: 0.9rem; }

        .content-section { padding: 30px 40px; }
        
        /* Table Style */
        .table-card { background: white; border-radius: 12px; border: 1px solid #eaeaea; overflow: hidden; }
        .table-header { padding: 20px 25px; border-bottom: 1px solid #eaeaea; background: #fcfcfc; }
        .table-header h5 { margin: 0; font-weight: 700; }
        
        .custom-table th { background: #f9fafb; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; padding: 15px 25px; border-bottom: 1px solid #eaeaea; color: #4b5563; }
        .custom-table td { padding: 15px 25px; vertical-align: middle; border-bottom: 1px solid #eaeaea; font-size: 0.95rem; }
        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: #dcfce7; color: #166534; }
    </style>
</head>
<body>

<div class="wrapper">
    <nav id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo"><i class="bi bi-hospital"></i></div>
            <div class="brand-text">
                <h5>Dinas Kesehatan</h5>
                <p>Admin Panel</p>
            </div>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="active">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('agenda.index') }}">
                    <i class="bi bi-calendar-week"></i> Agenda Management
                </a>
            </li>
            <li>
                <a href="#"><i class="bi bi-people"></i> Staff Data</a>
            </li>
            <li>
                <a href="#"><i class="bi bi-gear"></i> Settings</a>
            </li>
        </ul>

        <div class="user-profile">
            <div class="avatar">AD</div>
            <div>
                <div style="font-weight: 600; font-size: 0.9rem;">Admin User</div>
                <div style="font-size: 0.75rem; color: #6b7280;">admin@dinkes.go.id</div>
            </div>
        </div>
    </nav>

    <div id="content">
        
        <div class="main-header">
            <div class="page-title">
                <h4>Dashboard Overview</h4>
                <p>Ringkasan statistik agenda Dinas Kesehatan</p>
            </div>
            <div>
                <span class="badge bg-light text-dark border px-3 py-2">
                    <i class="bi bi-calendar-check me-2"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </div>

        <div class="content-section">
            
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon icon-green"><i class="bi bi-calendar-event"></i></div>
                        <div class="stat-info">
                            <h3>{{ $agendaHariIni }}</h3>
                            <p>Agenda Hari Ini</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon icon-blue"><i class="bi bi-calendar-month"></i></div>
                        <div class="stat-info">
                            <h3>{{ $agendaBulanIni }}</h3>
                            <p>Agenda Bulan Ini</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon icon-yellow"><i class="bi bi-hourglass-split"></i></div>
                        <div class="stat-info">
                            <h3>{{ $agendaAkanDatang }}</h3>
                            <p>Akan Datang</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon icon-purple"><i class="bi bi-archive"></i></div>
                        <div class="stat-info">
                            <h3>{{ $totalAgenda }}</h3>
                            <p>Total Arsip</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="table-header d-flex justify-content-between align-items-center">
                    <h5>Agenda Baru Ditambahkan</h5>
                    <a href="{{ route('agenda.index') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table mb-0">
                        <thead>
                            <tr>
                                <th>Kegiatan</th>
                                <th>Tanggal & Waktu</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestAgendas as $agenda)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $agenda->title }}</div>
                                </td>
                                <td>
                                    <div class="text-secondary small">
                                        <i class="bi bi-calendar me-1"></i> {{ \Carbon\Carbon::parse($agenda->start_time)->format('d M Y') }}
                                        <br>
                                        <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($agenda->start_time)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="text-secondary">{{ $agenda->location }}</td>
                                <td>
                                    <span class="status-badge">Aktif</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada data agenda.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
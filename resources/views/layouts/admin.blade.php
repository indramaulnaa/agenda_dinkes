<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Dinas Kesehatan</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; overflow-x: hidden; }

        /* --- LAYOUT SIDEBAR --- */
        .wrapper { display: flex; width: 100%; align-items: stretch; }
        
        #sidebar {
            min-width: 260px; max-width: 260px; background: #ffffff; color: #333;
            min-height: 100vh; border-right: 1px solid #eaeaea; padding: 20px;
            position: fixed; height: 100%; overflow-y: auto; z-index: 100;
            display: flex; flex-direction: column; /* Agar footer bisa di bawah */
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

        .sidebar-menu { list-style: none; padding: 0; margin-top: 20px; flex-grow: 1; }
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

        /* User Profile & Logout Area */
        .sidebar-footer {
            margin-top: auto; /* Dorong ke paling bawah */
            border-top: 1px solid #f0f0f0;
            padding-top: 20px;
        }

        .user-profile {
            display: flex; align-items: center; gap: 10px; margin-bottom: 15px;
        }
        .avatar {
            width: 36px; height: 36px; background: #e5e7eb; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-weight: 700; color: #374151;
        }

        /* Tombol Logout Style */
        .btn-logout {
            width: 100%; text-align: left; display: flex; align-items: center; gap: 10px;
            padding: 10px 16px; border-radius: 8px; color: #dc3545; font-weight: 600;
            background: #fff5f5; border: 1px solid #fed7d7; transition: 0.2s;
        }
        .btn-logout:hover {
            background: #dc3545; color: white; border-color: #dc3545;
        }

        /* --- HEADER --- */
        .main-header {
            background: white; padding: 25px 40px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eaeaea;
        }
        .page-title h4 { font-weight: 700; margin-bottom: 4px; font-size: 1.5rem; color: #111827; }
        .page-title p { color: #6b7280; margin-bottom: 0; font-size: 0.9rem; }

        .content-section { padding: 30px 40px; }
        
        /* Kita tetap simpan style table/card disini agar halaman lain bisa pakai */
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
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('agenda.index') }}" class="{{ request()->routeIs('agenda.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-week"></i> Agenda Management
                </a>
            </li>
            <li>
            <a href="{{ route('meeting-room.index') }}" class="{{ request()->is('meeting-room*') ? 'active' : '' }}">
                <i class="bi bi-door-open-fill"></i> Meeting Room
            </a>
            </li>
            <li>
                <a href="{{ route('staff.index') }}" class="{{ request()->routeIs('staff.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Staff Data
                </a>
            </li>
            <li>
                <a href="{{ Route::has('settings.index') ? route('settings.index') : '#' }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="avatar">AD</div>
                <div>
                    <div style="font-weight: 600; font-size: 0.9rem;">Admin User</div>
                    <div style="font-size: 0.75rem; color: #6b7280;">admin@dinkes.go.id</div>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-logout" onclick="return confirm('Yakin ingin keluar?')">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </nav>

    <div id="content">
        
        <div class="main-header">
            <div class="page-title">
                <h4>@yield('page_title', 'Dashboard Overview')</h4>
                <p>@yield('page_subtitle', 'Sistem Informasi Agenda Dinas Kesehatan')</p>
            </div>
            <div>
                <span class="badge bg-light text-dark border px-3 py-2">
                    <i class="bi bi-calendar-check me-2"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </div>
        <div class="content-section">
            @yield('content')
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
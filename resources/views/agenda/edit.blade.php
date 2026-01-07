<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agenda - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Menggunakan style yang sama persis dengan Create */
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .wrapper { display: flex; width: 100%; align-items: stretch; }
        #sidebar { min-width: 260px; max-width: 260px; background: #ffffff; min-height: 100vh; border-right: 1px solid #eaeaea; padding: 20px; position: fixed; }
        #content { width: 100%; margin-left: 260px; min-height: 100vh; }
        .sidebar-brand { display: flex; align-items: center; gap: 12px; padding-bottom: 30px; margin-bottom: 10px; border-bottom: 1px solid #f0f0f0; }
        .brand-logo { width: 40px; height: 40px; background: #198754; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .brand-text h5 { margin: 0; font-weight: 700; font-size: 1rem; color: #111827; }
        .brand-text p { margin: 0; font-size: 0.75rem; color: #6b7280; }
        .sidebar-menu { list-style: none; padding: 0; margin-top: 20px; }
        .sidebar-menu a { display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: #4b5563; text-decoration: none; border-radius: 8px; font-weight: 500; transition: 0.2s; margin-bottom: 8px;}
        .sidebar-menu a:hover, .sidebar-menu a.active { background-color: #198754; color: white; }
        
        .main-header { background: white; padding: 25px 40px; border-bottom: 1px solid #eaeaea; }
        .form-card { background: white; border-radius: 12px; border: 1px solid #eaeaea; padding: 30px; margin: 30px 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .form-label { font-weight: 600; color: #374151; font-size: 0.9rem; }
        .form-control { padding: 10px 15px; border-radius: 8px; border: 1px solid #d1d5db; }
        .btn-simpan { background-color: #d97706; border: none; padding: 10px 30px; font-weight: 600; } /* Warna Kuning untuk Edit */
        .btn-simpan:hover { background-color: #b45309; }
    </style>
</head>
<body>

<div class="wrapper">
    <nav id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo"><i class="bi bi-hospital"></i></div>
            <div class="brand-text"><h5>Dinas Kesehatan</h5><p>Admin Panel</p></div>
        </div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li><a href="{{ route('agenda.index') }}" class="active"><i class="bi bi-calendar-week"></i> Agenda Management</a></li>
            <li><a href="#"><i class="bi bi-people"></i> Staff Data</a></li>
            <li><a href="#"><i class="bi bi-gear"></i> Settings</a></li>
        </ul>
    </nav>

    <div id="content">
        <div class="main-header">
            <h4 class="fw-bold m-0">Edit Agenda</h4>
        </div>

        <div class="form-card">
            <form action="{{ route('agenda.update', $agenda->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="mb-4">
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

</body>
</html>
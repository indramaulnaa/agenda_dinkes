<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Agenda Dinas</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; overflow-x: hidden; }

        /* --- LAYOUT SIDEBAR & CONTENT --- */
        .wrapper { display: flex; width: 100%; align-items: stretch; }
        
        /* SIDEBAR STYLE */
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            background: #ffffff;
            color: #333;
            min-height: 100vh;
            border-right: 1px solid #eaeaea;
            padding: 20px;
            position: fixed; /* Agar sidebar diam saat scroll */
            height: 100%;
            overflow-y: auto;
        }
        
        /* Main Content Style */
        #content {
            width: 100%;
            margin-left: 260px; /* Memberi ruang untuk sidebar */
            padding: 0;
            min-height: 100vh;
        }

        /* LOGO AREA */
        .sidebar-brand {
            display: flex; align-items: center; gap: 12px;
            padding-bottom: 30px; margin-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .brand-logo {
            width: 40px; height: 40px; background: #198754; color: white;
            border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        }
        .brand-text h5 { margin: 0; font-weight: 700; font-size: 1rem; color: #111827; }
        .brand-text p { margin: 0; font-size: 0.75rem; color: #6b7280; }

        /* MENU ITEMS */
        .sidebar-menu { list-style: none; padding: 0; margin-top: 20px; }
        .sidebar-menu li { margin-bottom: 8px; }
        .sidebar-menu a {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 16px;
            color: #4b5563; text-decoration: none;
            border-radius: 8px; font-weight: 500; font-size: 0.95rem;
            transition: 0.2s;
        }
        .sidebar-menu a:hover { background-color: #f3f4f6; color: #111827; }
        
        /* MENU AKTIF (Agenda Management) */
        .sidebar-menu a.active {
            background-color: #198754; color: white;
            box-shadow: 0 4px 6px rgba(25, 135, 84, 0.15);
        }
        .sidebar-menu a.active:hover { background-color: #157347; }

        /* USER PROFILE (Bottom Sidebar) */
        .user-profile {
            position: absolute; bottom: 20px; left: 20px; right: 20px;
            display: flex; align-items: center; gap: 10px;
            padding-top: 20px; border-top: 1px solid #f0f0f0;
        }
        .avatar {
            width: 36px; height: 36px; background: #e5e7eb; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-weight: 700; color: #374151;
        }

        /* --- HEADER CONTENT (Main Area) --- */
        .main-header {
            background: white; padding: 25px 40px;
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid #eaeaea;
        }
        .page-title h4 { font-weight: 700; margin-bottom: 4px; font-size: 1.5rem; color: #111827; }
        .page-title p { color: #6b7280; margin-bottom: 0; font-size: 0.9rem; }

        /* --- STYLE KALENDER (YANG SUDAH KITA SEPAKATI) --- */
        .calendar-card {
            background: white; margin: 30px 40px; padding: 25px;
            border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #eaeaea;
        }
        .fc-toolbar-title { font-size: 1.25rem !important; font-weight: 700; color: #1f2937; }
        
        /* Header Hari (Abu-abu, Hitam) */
        .fc-col-header-cell { background-color: #f1f3f5 !important; padding: 12px 0 !important; border-bottom: 1px solid #dee2e6 !important; }
        .fc-col-header-cell-cushion { color: #212529 !important; text-decoration: none !important; font-size: 0.95rem; font-weight: 600; text-transform: uppercase; }
        
        /* Angka Tanggal (Hitam) */
        .fc-daygrid-day-number { color: #000000 !important; text-decoration: none !important; font-size: 1.1rem; font-weight: 500; padding: 8px 12px !important; margin: 2px; }
        .fc-day-today .fc-daygrid-day-number { background-color: #198754 !important; color: #ffffff !important; width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin: 4px; }
        .fc-day-today { background: none !important; }
        
        /* Event/Agenda (Balok Tebal) */
        .fc-daygrid-event {
            border: none !important; padding: 6px 10px !important; border-radius: 6px;
            margin-top: 3px !important; cursor: pointer; display: flex !important; 
            flex-direction: row !important; align-items: center !important;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-decoration: none !important;
        }
        .event-time-text { font-weight: 800; margin-right: 6px; font-size: 0.9rem; }
        .event-title-text { font-weight: 600; font-size: 0.9rem; }

        /* Custom Buttons */
        .btn-custom-green { background-color: #198754; border: none; color: white; font-weight: 600; padding: 10px 20px; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; }
        .btn-custom-green:hover { background-color: #157347; color: white; }
        .btn-outline-custom { background: white; border: 1px solid #d1d5db; color: #374151; font-weight: 500; padding: 10px 16px; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; }
        .btn-outline-custom:hover { background-color: #f3f4f6; color: #111827; }

        /* List View Custom */
        .custom-card { background: white; border: 1px solid #edf2f7; border-radius: 12px; padding: 16px; margin: 8px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.03); display: flex; flex-direction: column; gap: 5px; }
        .custom-time { font-weight: 700; color: #111827; }
        .custom-title { font-weight: 700; font-size: 1.1rem; color: #198754; }
        .fc-list-event-graphic, .fc-list-event-time { display: none; }
        .fc-list-event:hover td { background: transparent !important; }
        .fc-list-day-text { font-size: 1.1rem; font-weight: 700; color: #198754; margin-top: 20px; display: block; text-decoration: none !important; }
        .fc-list-day-cushion { background: transparent !important; }
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
                <a href="#"><i class="bi bi-grid-1x2"></i> Dashboard</a>
            </li>
            <li>
                <a href="{{ route('agenda.index') }}" class="active">
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
            <div style="font-size: 0.9rem;">
                <div style="font-weight: 600;">Admin User</div>
                <div style="font-size: 0.75rem; color: #6b7280;">admin@dinkes.go.id</div>
            </div>
        </div>
    </nav>

    <div id="content">
        
        <div class="main-header">
            <div class="page-title">
                <h4>Agenda Management</h4>
                <p>Kelola jadwal dan agenda kegiatan Dinas Kesehatan</p>
            </div>
            
            <div class="d-flex gap-2">
                <a href="{{ route('home') }}" class="btn-outline-custom text-decoration-none">
                    <i class="bi bi-box-arrow-up-right"></i> Lihat Web
                </a>
                <a href="{{ route('agenda.create') }}" class="btn-custom-green text-decoration-none">
                    <i class="bi bi-plus-lg"></i> Buat Agenda Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success m-4 mb-0 border-0 shadow-sm d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="calendar-card">
            <div id='calendar'></div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Kelola Agenda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <h4 id="eventTitle" class="fw-bold text-success mb-3"></h4>
                <div class="mb-2 text-muted"><i class="bi bi-clock me-2"></i><span id="eventTime"></span></div>
                <div class="mb-2 text-muted"><i class="bi bi-geo-alt me-2"></i><span id="eventLocation"></span></div>
                <div class="mt-3 p-3 bg-light rounded text-secondary" id="eventDesc"></div>
                <hr class="my-4">
                <div class="d-flex gap-2">
                    <a href="#" id="btnEdit" class="btn btn-warning text-white flex-grow-1"><i class="bi bi-pencil-square"></i> Edit</a>
                    <form id="formDelete" action="#" method="POST" class="flex-grow-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Hapus agenda ini?')"><i class="bi bi-trash"></i> Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            eventDisplay: 'block', 

            headerToolbar: {
                left: 'title',
                center: '',
                right: 'dayGridMonth,listMonth prev,today,next'
            },
            buttonText: { today: 'Hari Ini', month: 'Bulan', list: 'List Agenda' },

            events: '{{ route("agenda.feed") }}',

            // Custom Content (Sama seperti sebelumnya)
            eventContent: function(arg) {
                let event = arg.event;
                let start = event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});

                if (arg.view.type === 'listMonth') {
                    let end = event.end ? event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) : '';
                    let time = end ? `${start} - ${end}` : start;
                    
                    let card = document.createElement('div');
                    card.className = 'custom-card';
                    card.innerHTML = `
                        <div class="d-flex justify-content-between">
                            <span class="custom-time">${time}</span>
                            <small class="text-muted"><i class="bi bi-pencil-fill"></i> Kelola</small>
                        </div>
                        <div class="custom-title">${event.title}</div>
                        <div class="custom-loc"><i class="bi bi-geo-alt-fill"></i> ${event.extendedProps.location || '-'}</div>
                    `;
                    return { domNodes: [card] };
                } else {
                    let content = document.createElement('div');
                    content.style.display = 'flex'; content.style.alignItems = 'center';
                    content.innerHTML = `<span class="event-time-text">${start}</span><span class="event-title-text">${event.title}</span>`;
                    return { domNodes: [content] };
                }
            },

            eventClick: function(info) {
                var event = info.event;
                document.getElementById('eventTitle').innerText = event.title;
                document.getElementById('eventLocation').innerText = event.extendedProps.location || '-';
                document.getElementById('eventDesc').innerText = event.extendedProps.description || '-';
                var start = event.start.toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' });
                document.getElementById('eventTime').innerText = start;
                
                var baseUrl = "{{ url('/agenda') }}"; 
                document.getElementById('btnEdit').href = baseUrl + "/" + event.id + "/edit";
                document.getElementById('formDelete').action = baseUrl + "/" + event.id;

                new bootstrap.Modal(document.getElementById('detailModal')).show();
            }
        });
        calendar.render();
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
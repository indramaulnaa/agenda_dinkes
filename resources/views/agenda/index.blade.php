<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Agenda Dinas</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }

        /* --- 1. GLOBAL RESET (Hapus Garis Bawah di Semua Link) --- */
        a { text-decoration: none !important; }

        /* --- 2. HEADER ADMIN (HIJAU) --- */
        .header-section {
            background-color: #198754; 
            padding: 25px 40px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .app-title h4 { font-weight: 700; margin-bottom: 5px; font-size: 1.6rem; }
        .app-title p { color: #e9ecef; margin-bottom: 0; font-size: 1rem; opacity: 0.9; }
        
        /* Badge Khusus Admin */
        .badge-admin {
            background-color: #ffc107; color: #212529; 
            font-size: 0.7rem; padding: 4px 8px; border-radius: 6px; 
            margin-left: 8px; vertical-align: middle; text-transform: uppercase;
            font-weight: 700;
        }

        /* --- 3. CONTAINER KALENDER --- */
        .calendar-card {
            background: white;
            margin: 30px 40px; 
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            border: 1px solid #eaeaea;
        }

        /* --- 4. FULLCALENDAR CUSTOM --- */
        .fc-toolbar-title { font-size: 1.25rem !important; font-weight: 700; color: #1f2937; }
        .fc-button { border-radius: 6px !important; font-weight: 500 !important; text-transform: capitalize !important; }
        .fc-button-primary { background-color: white !important; border: 1px solid #e5e7eb !important; color: #374151 !important; box-shadow: none !important; }
        .fc-button-primary:hover { background-color: #f0fdf4 !important; color: #198754 !important; border-color: #198754 !important; }

        /* --- 5. PERBAIKAN TAMPILAN HARI & TANGGAL (Sesuai Prototype) --- */
        
        /* HEADER HARI (Min, Sen, Sel...) -> BACKGROUND ABU-ABU */
        .fc-col-header-cell {
            background-color: #f1f3f5 !important; /* Abu-abu muda */
            padding: 12px 0 !important;
            border-bottom: 1px solid #dee2e6 !important;
        }
        
        /* Teks Nama Hari (Hitam, Tanpa Garis) */
        .fc-col-header-cell-cushion {
            color: #212529 !important; /* Hitam */
            text-decoration: none !important;
            font-size: 0.95rem;
            font-weight: 600;
            text-transform: uppercase; /* Huruf Kapital Semua */
            letter-spacing: 0.5px;
        }

        /* ANGKA TANGGAL (Hitam, Tanpa Garis) */
        .fc-daygrid-day-number {
            color: #000000 !important; /* Hitam Pekat */
            text-decoration: none !important;
            font-size: 1.1rem;
            font-weight: 500;
            padding: 8px 12px !important;
            margin: 2px;
        }
        
        /* Hover pada tanggal (opsional biar interaktif) */
        .fc-daygrid-day-number:hover {
            background-color: #e9ecef;
            border-radius: 50%;
        }

        /* Hari Ini (Lingkaran Hijau) - Teks Tetap Putih */
        .fc-day-today .fc-daygrid-day-number {
            background-color: #198754 !important; 
            color: #ffffff !important; /* Putih */
            width: 32px; height: 32px; /* Ukuran lingkaran */
            border-radius: 50%; 
            display: inline-flex; align-items: center; justify-content: center; 
            margin: 4px;
        }
        .fc-day-today { background: none !important; }

        /* --- 6. AGENDA BULAN (BALOK TEBAL) --- */
        .fc-daygrid-event {
            border: none !important;
            padding: 6px 10px !important;
            border-radius: 6px;
            margin-top: 3px !important;
            cursor: pointer;
            display: flex !important; 
            flex-direction: row !important;
            align-items: center !important;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            text-decoration: none !important; /* Hapus garis bawah link agenda */
        }
        .event-time-text { font-weight: 800; margin-right: 6px; font-size: 0.9rem; }
        .event-title-text { font-weight: 600; font-size: 0.9rem; }

        /* --- 7. LIST VIEW (KARTU ADMIN) --- */
        .custom-card {
            background: white; border: 1px solid #edf2f7; border-radius: 12px;
            padding: 16px; margin: 8px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.03);
            display: flex; flex-direction: column; gap: 5px; position: relative;
        }
        .custom-card:hover { border-color: #198754; transform: translateY(-2px); transition: 0.2s; }

        .custom-time { font-weight: 700; color: #111827; }
        .custom-title { font-weight: 700; font-size: 1.1rem; color: #198754; }
        .custom-loc { color: #6b7280; font-size: 0.9rem; }

        /* Hide default elements */
        .fc-list-event-graphic, .fc-list-event-time { display: none; }
        .fc-list-event:hover td { background: transparent !important; }
        .fc-list-day-text { font-size: 1.1rem; font-weight: 700; color: #198754; margin-top: 20px; display: block; text-decoration: none !important; }
        .fc-list-day-cushion { background: transparent !important; }
    </style>
</head>
<body>

    @if(session('success'))
        <div class="alert alert-success m-4 mb-0 border-0 shadow-sm d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="header-section">
        <div class="app-title">
            <div class="d-flex align-items-center gap-2">
                <h4>Agenda Management</h4>
                <span class="badge badge-admin">ADMIN</span>
            </div>
            <p>Kelola jadwal dan agenda kegiatan Dinas Kesehatan</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('home') }}" class="btn btn-outline-light">
                <i class="bi bi-box-arrow-up-right"></i> Lihat Web
            </a>
            <a href="{{ route('agenda.create') }}" class="btn btn-light text-success fw-bold">
                <i class="bi bi-plus-lg"></i> Buat Agenda
            </a>
        </div>
    </div>

    <div class="calendar-card">
        <div id='calendar'></div>
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
                        <a href="#" id="btnEdit" class="btn btn-warning text-white flex-grow-1">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <form id="formDelete" action="#" method="POST" class="flex-grow-1">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Hapus agenda ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
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
                buttonText: { today: 'Hari Ini', month: 'Kalender', list: 'List Agenda' },

                events: '{{ route("agenda.feed") }}',

                // --- LOGIKA TAMPILAN (SAMA DENGAN PEGAWAI) ---
                eventContent: function(arg) {
                    let event = arg.event;
                    let start = event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});

                    // 1. TAMPILAN LIST (KARTU)
                    if (arg.view.type === 'listMonth') {
                        let end = event.end ? event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) : '';
                        let time = end ? `${start} - ${end}` : start;
                        
                        let card = document.createElement('div');
                        card.className = 'custom-card';
                        // Kita tambahkan ikon pencil kecil biar admin tau ini bisa diedit
                        card.innerHTML = `
                            <div class="d-flex justify-content-between">
                                <span class="custom-time">${time}</span>
                                <small class="text-muted"><i class="bi bi-pencil-fill"></i> Kelola</small>
                            </div>
                            <div class="custom-title">${event.title}</div>
                            <div class="custom-loc"><i class="bi bi-geo-alt-fill"></i> ${event.extendedProps.location || '-'}</div>
                        `;
                        return { domNodes: [card] };
                    } 
                    
                    // 2. TAMPILAN BULAN (BALOK TEBAL)
                    else {
                        let content = document.createElement('div');
                        content.style.display = 'flex';
                        content.style.alignItems = 'center';
                        content.innerHTML = `
                            <span class="event-time-text">${start}</span>
                            <span class="event-title-text">${event.title}</span>
                        `;
                        return { domNodes: [content] };
                    }
                },

                // --- LOGIKA KLIK (MODAL EDIT/HAPUS) ---
                eventClick: function(info) {
                    var event = info.event;
                    document.getElementById('eventTitle').innerText = event.title;
                    document.getElementById('eventLocation').innerText = event.extendedProps.location || '-';
                    document.getElementById('eventDesc').innerText = event.extendedProps.description || '-';
                    
                    var start = event.start.toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' });
                    document.getElementById('eventTime').innerText = start;

                    // SET TOMBOL EDIT & DELETE
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
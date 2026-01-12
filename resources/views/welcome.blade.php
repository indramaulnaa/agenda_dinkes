<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Dinas Kesehatan</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <style>
        /* --- BACKGROUND IMAGE DENGAN OVERLAY --- */
        body { 
            font-family: 'Inter', sans-serif; 
            
            /* 1. Ganti URL ini dengan foto gedung kantor Anda nanti */
            background: url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=2053&auto=format&fit=crop') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            position: relative;
        }

        /* 2. Lapisan Putih Transparan (Agar teks tetap terbaca jelas) */
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(248, 249, 250, 0.1); /* Putih 90% Opacity */
            z-index: -1; /* Taruh di belakang konten */
            backdrop-filter: blur(2px); /* Efek blur dikit biar estetik */
        }
        
        /* Navbar Style */
        .navbar { background: rgba(255, 255, 255, 0.95); border-bottom: 1px solid #eaeaea; padding: 15px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.02); backdrop-filter: blur(5px); }
        .brand-logo { width: 40px; height: 40px; background: #198754; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-right: 12px;}
        .brand-text h5 { font-weight: 700; margin: 0; color: #111827; font-size: 1.1rem; }
        .brand-text p { margin: 0; font-size: 0.8rem; color: #6b7280; }

        /* Calendar Container */
        .calendar-container { background: white; border-radius: 16px; border: 1px solid #eaeaea; box-shadow: 0 4px 20px rgba(0,0,0,0.03); padding: 30px; margin-top: 30px; margin-bottom: 50px; }
        .fc-toolbar-title { font-size: 1.5rem !important; font-weight: 700; color: #1f2937; }
        
        /* TOMBOL TOOLBAR (CLEAN STYLE) */
        .fc .fc-button-primary { background-color: transparent !important; border: none !important; color: #6b7280 !important; font-weight: 600; box-shadow: none !important; padding: 8px 16px; transition: 0.2s; }
        .fc .fc-button-primary:hover { background-color: #f3f4f6 !important; color: #111827 !important; }
        .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active { background-color: transparent !important; color: #111827 !important; font-weight: 800 !important; }

        /* Header Kalender */
        .fc-col-header-cell { background-color: #f9fafb !important; padding: 15px 0 !important; border-bottom: 1px solid #eaeaea !important; }
        .fc-col-header-cell-cushion { color: #6b7280 !important; text-decoration: none !important; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        
        /* Angka Tanggal */
        .fc-daygrid-day-number { color: #374151 !important; text-decoration: none !important; font-weight: 500; margin: 4px; padding: 4px 8px; }
        
        /* Hari Ini */
        .fc-day-today { background: none !important; background-color: transparent !important; }
        .fc-day-today .fc-daygrid-day-number { background-color: transparent !important; color: #111827 !important; font-weight: 800 !important; }
        
        /* --- STYLE KHUSUS LIST VIEW --- */
        .fc-theme-standard .fc-list { border: none !important; }
        .fc-theme-standard .fc-list-day-cushion { background-color: transparent !important; border: none !important; padding: 15px 0 10px 0 !important; }
        .fc-list-event td { border: none !important; background: transparent !important; }
        .fc-list-event:hover td { background: transparent !important; }
        
        /* Judul Tanggal */
        .fc-list-day-text, .fc-list-day-side-text { font-size: 1.1rem; font-weight: 700; color: #111827; text-decoration: none !important; }
        .fc-list-day-cushion a { text-decoration: none !important; pointer-events: none; color: inherit !important; }
        
        .fc-list-event-title { padding: 0 !important; border: none !important; }
        .fc-list-event-time, .fc-list-event-graphic { display: none !important; }
        .fc-list-empty { display: none !important; }

        /* DESAIN KARTU AGENDA */
        .agenda-list-card {
            background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px;
            margin-bottom: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            transition: all 0.2s ease-in-out; cursor: pointer; 
        }
        .agenda-list-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); border-color: #198754; }

        /* WARNA STATUS */
        .card-status-badge { font-size: 0.75rem; font-weight: 700; padding: 5px 12px; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.5px; }
        .bg-ongoing { background-color: #fff3cd; color: #664d03; } 
        .bg-scheduled { background-color: #cfe2ff; color: #084298; } 
        .bg-selesai { background-color: #d1e7dd; color: #0f5132; } 

        .card-time { font-size: 1rem; font-weight: 800; color: #111827; margin-bottom: 4px; }
        .card-title { font-size: 1.1rem; font-weight: 600; color: #374151; margin-bottom: 8px; line-height: 1.4; }
        .card-location { font-size: 0.9rem; color: #6b7280; display: flex; align-items: center; gap: 6px; }
        .card-participants { margin-top: 10px; padding-top: 10px; border-top: 1px dashed #e5e7eb; font-size: 0.85rem; color: #6b7280; }

        /* Grid Style */
        .fc-daygrid-event { border: none !important; padding: 4px 8px !important; border-radius: 6px; margin-top: 4px !important; cursor: pointer; font-weight: 500; font-size: 0.85rem; transition: none !important; }
        .fc-daygrid-event:hover { opacity: 1 !important; filter: none !important; }
        .event-time-text { font-weight: 900 !important; margin-right: 6px; }
        
        /* Modal Style */
        .modal-title { font-weight: 700; color: #1f2937; }
        .detail-label { font-size: 0.85rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .detail-content { font-size: 1rem; color: #111827; font-weight: 500; margin-bottom: 16px; }
        .badge-participant { background-color: #e0f2fe; color: #0369a1; padding: 8px 12px; border-radius: 6px; font-weight: 600; font-size: 0.9rem; display: inline-block; border: 1px solid #bae6fd; margin-right: 4px; margin-bottom: 4px;}

        /* Tombol Tutup Custom */
        .btn-close-custom { background-color: #4b5563; border: none; color: white; font-weight: 700; padding: 10px; transition: all 0.3s ease; }
        .btn-close-custom:hover { background-color: #000000 !important; color: white !important; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <div class="d-flex align-items-center">
                <div class="brand-logo"><i class="bi bi-hospital"></i></div>
                <div class="brand-text">
                    <h5>Dinas Kesehatan</h5>
                    <p>Sistem Informasi Agenda</p>
                </div>
            </div>
            <div class="ms-auto">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-success fw-bold px-4">Dashboard Admin</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-success fw-bold px-4">Login Admin</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <div class="container" style="margin-top: 100px;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-dark">Jadwal Kegiatan</h2>
            <p class="text-secondary fw-medium">Agenda resmi kegiatan Dinas Kesehatan bulan ini</p>
        </div>

        <div class="calendar-container">
            <div id='calendar'></div>
        </div>
        
        <div class="text-center text-secondary fw-medium mb-5">
            <small>&copy; {{ date('Y') }} Dinas Kesehatan. All rights reserved.</small>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title">Detail Agenda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <h4 id="detailTitle" class="fw-bold text-success mb-4" style="font-size: 1.4rem;"></h4>

                    <div class="mb-3">
                        <div class="detail-label"><i class="bi bi-clock me-1"></i> Waktu Pelaksanaan</div>
                        <div id="detailTime" class="detail-content"></div>
                    </div>

                    <div class="mb-3">
                        <div class="detail-label"><i class="bi bi-geo-alt me-1"></i> Lokasi</div>
                        <div id="detailLocation" class="detail-content"></div>
                    </div>

                    <div class="mb-4">
                        <div class="detail-label"><i class="bi bi-people me-1"></i> Ditujukan Kepada</div>
                        <div id="detailParticipantsWrapper" class="d-flex flex-wrap"></div>
                    </div>

                    <div class="bg-light p-3 rounded border">
                        <div class="detail-label mb-2">Catatan / Deskripsi</div>
                        <div id="detailDesc" class="text-secondary" style="font-size: 0.95rem; line-height: 1.5;"></div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-close-custom w-100" data-bs-dismiss="modal">Tutup</button>
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
                headerToolbar: {
                    left: 'title',
                    center: '',
                    right: 'dayGridMonth,listMonth prev,today,next' 
                },
                buttonText: { today: 'Hari Ini', month: 'Bulan', list: 'List' },
                events: '{{ route("agenda.feed") }}', 

                datesSet: function(info) {
                    if (info.view.type === 'listMonth') {
                        var now = new Date(); now.setHours(0,0,0,0); 
                        var todayStr = now.toISOString().split('T')[0];

                        document.querySelectorAll('.fc-list-day').forEach(function(header) {
                            var dateStr = header.getAttribute('data-date');
                            if (dateStr) {
                                var headerDate = new Date(dateStr); headerDate.setHours(0,0,0,0);
                                if (headerDate < now) { header.style.display = 'none'; }
                                if (dateStr === todayStr) {
                                    var titleEl = header.querySelector('.fc-list-day-text');
                                    if(titleEl) { titleEl.innerText = "Hari Ini"; titleEl.style.color = "#198754"; }
                                }
                            }
                        });
                    }
                },

                eventDidMount: function(arg) {
                    if (arg.view.type === 'listMonth') {
                        var now = new Date(); now.setHours(0,0,0,0); 
                        if (arg.event.start < now) { arg.el.style.display = 'none'; }
                    }
                },

                eventContent: function(arg) {
                    let event = arg.event;
                    let start = event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                    
                    if (arg.view.type === 'listMonth') {
                        let end = event.end ? event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) : '';
                        let timeRange = end ? `${start} - ${end}` : start;
                        
                        let now = new Date();
                        let eventEnd = event.end || event.start;
                        
                        let badgeLabel = 'Akan Datang'; let badgeClass = 'bg-scheduled';

                        if (now > eventEnd) { badgeLabel = 'Selesai'; badgeClass = 'bg-selesai'; } 
                        else if (now >= event.start && now <= eventEnd) { badgeLabel = 'Sedang Berlangsung'; badgeClass = 'bg-ongoing'; }

                        let pData = event.extendedProps.participants;
                        let pText = Array.isArray(pData) ? pData.join(', ') : pData;
                        if (!pText) pText = "-";

                        let card = document.createElement('div');
                        card.className = 'agenda-list-card';
                        card.innerHTML = `
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="card-time">${timeRange}</div>
                                    <div class="card-title">${event.title}</div>
                                </div>
                                <span class="card-status-badge ${badgeClass}">${badgeLabel}</span>
                            </div>
                            <div class="card-location">
                                <i class="bi bi-geo-alt-fill me-2 text-muted"></i> ${event.extendedProps.location || 'Lokasi tidak tersedia'}
                            </div>
                            <div class="card-participants">
                                <i class="bi bi-people-fill me-1"></i> ${pText}
                            </div>
                        `;
                        return { domNodes: [card] };

                    } else {
                        let content = document.createElement('div');
                        content.style.backgroundColor = event.backgroundColor;
                        content.style.borderColor = event.borderColor;
                        content.style.color = event.textColor;
                        content.style.padding = '4px 8px';
                        content.style.borderRadius = '6px';
                        content.style.overflow = 'hidden';
                        content.style.whiteSpace = 'nowrap';
                        content.style.textOverflow = 'ellipsis';
                        content.innerHTML = `<span class="event-time-text">${start}</span> ${event.title}`;
                        return { domNodes: [content] };
                    }
                },

                eventClick: function(info) {
                    var event = info.event;
                    var props = event.extendedProps;

                    document.getElementById('detailTitle').innerText = event.title;
                    document.getElementById('detailLocation').innerText = props.location || '-';
                    document.getElementById('detailDesc').innerText = props.description || '-';

                    var wrapper = document.getElementById('detailParticipantsWrapper');
                    wrapper.innerHTML = ''; 
                    var pData = props.participants;
                    if(Array.isArray(pData)) {
                        pData.forEach(p => { let b = document.createElement('span'); b.className='badge-participant'; b.innerText=p; wrapper.appendChild(b); });
                    } else if (pData) {
                        let b = document.createElement('span'); b.className='badge-participant'; b.innerText=pData; wrapper.appendChild(b);
                    } else { wrapper.innerText = 'Umum'; }

                    var start = event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                    var end = event.end ? event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) : '';
                    var dateStr = event.start.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                    
                    document.getElementById('detailTime').innerText = `${dateStr} • ${start} ${end ? '- ' + end : ''}`;

                    new bootstrap.Modal(document.getElementById('detailModal')).show();
                }
            });
            calendar.render();
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
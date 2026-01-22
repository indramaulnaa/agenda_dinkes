<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Dinas Kesehatan</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <style>
        /* --- BACKGROUND IMAGE --- */
        body { 
            font-family: 'Inter', sans-serif; 
            background: url('{{ asset('images/slide.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            position: relative;
        }
        body::before {
            content: ""; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(255, 255, 255, 0); 
            z-index: -1; backdrop-filter: blur(2px);
        }
        
        /* Navbar */
        .navbar { background: rgba(255, 255, 255, 0.95); border-bottom: 1px solid #eaeaea; padding: 15px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .brand-logo img { height: 45px; margin-right: 12px; }
        .brand-text h5 { font-weight: 700; margin: 0; color: #111827; font-size: 1.1rem; }
        .brand-text p { margin: 0; font-size: 0.8rem; color: #6b7280; }

        /* Calendar Container */
        .calendar-container { 
            background: white; border-radius: 24px; border: 1px solid #f3f4f6; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.04); padding: 40px; margin-top: 30px; margin-bottom: 50px; 
        }
        
        /* --- 1. CLEAN BUTTONS --- */
        .fc-toolbar-title { font-size: 1.75rem !important; font-weight: 800; color: #111827; letter-spacing: -0.5px; }
        .fc .fc-button {
            background-color: transparent !important; border: none !important; box-shadow: none !important;
            color: #9ca3af !important; font-weight: 600 !important; font-size: 0.95rem !important;
            padding: 8px 12px !important; text-transform: capitalize; transition: all 0.2s ease;
        }
        .fc .fc-button:hover { color: #374151 !important; background: #f3f4f6 !important; border-radius: 8px; }
        .fc .fc-icon { font-size: 1.2rem; font-weight: bold; color: #111827; }

        /* --- 2. WARNA TOMBOL AKTIF --- */
        .fc-btnAgenda-button.active-mode, 
        body.theme-green .fc-listMonth-button.fc-button-active {
            color: #198754 !important; background-color: #ecfdf5 !important;
            border-radius: 50px !important; padding: 8px 20px !important; font-weight: 800 !important;
        }
        .fc-btnRooms-button.active-mode,
        body.theme-orange .fc-listMonth-button.fc-button-active {
            color: #fd7e14 !important; background-color: #fff7ed !important;
            border-radius: 50px !important; padding: 8px 20px !important; font-weight: 800 !important;
        }
        .fc-today-button {
            color: #111827 !important; background-color: #f3f4f6 !important;
            border-radius: 50px !important; padding: 8px 20px !important; margin-right: 5px !important;
        }

        /* --- 3. HEADER KALENDER (CLEAN) --- */
        .fc-col-header-cell {
            background-color: #f9fafb !important; padding: 15px 0 !important;
            border: none !important; border-bottom: 1px solid #f3f4f6 !important;
        }
        .fc-scrollgrid { border: none !important; }
        .fc-theme-standard td, .fc-theme-standard th { border: 1px solid #f3f4f6; }

        .fc-col-header-cell-cushion {
            text-decoration: none !important; color: #6b7280 !important; font-weight: 700 !important;
            text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;
        }

        /* --- 4. TANGGAL HARI INI (HIJAU/ORANYE) --- */
        .fc-daygrid-day-top { justify-content: center; margin-top: 5px; }
        .fc-daygrid-day-number { 
            color: #4b5563 !important; text-decoration: none !important; font-weight: 600; 
            width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;
            border-radius: 50%; z-index: 2;
        }
        .fc-day-today { background: transparent !important; }
        
        body.theme-green .fc-day-today .fc-daygrid-day-number { 
            background-color: #198754 !important; color: white !important; box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);
        }
        body.theme-orange .fc-day-today .fc-daygrid-day-number { 
            background-color: #fd7e14 !important; color: white !important; box-shadow: 0 4px 10px rgba(253, 126, 20, 0.3);
        }

        /* --- 5. KOTAK EVENT (KAPSUL) --- */
        .fc-daygrid-event { border: none !important; padding: 2px !important; margin-top: 4px !important; background: transparent !important; }
        .event-capsule {
            display: flex; align-items: center; gap: 8px; padding: 6px 12px;
            border-radius: 8px; font-size: 0.8rem; box-shadow: 0 2px 4px rgba(0,0,0,0.03);
            transition: transform 0.1s; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;
        }
        .event-capsule:hover { transform: scale(1.02); }
        .event-time { font-weight: 800; font-size: 0.75rem; }
        .event-title { font-weight: 400; }

        /* --- 6. LIST VIEW CUSTOMIZATION --- */
        .fc-list-table td { border: none !important; background: transparent !important; }
        .fc-list-day-cushion { background-color: transparent !important; padding-top: 25px !important; }
        .fc-list-day-text { font-size: 1.1rem; font-weight: 800; color: #111827; text-decoration: none !important; }
        .fc-list-day-side-text { font-weight: 600; color: #9ca3af !important; text-decoration: none !important; font-size: 1rem; }
        .fc-list-day-cushion a { text-decoration: none !important; pointer-events: none; }
        .fc-list-event-time, .fc-list-event-graphic { display: none !important; }
        .fc-list-empty { display: none !important; }

        .agenda-list-card {
            padding: 15px 20px; border-radius: 12px; margin-bottom: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center;
            transition: transform 0.2s;
        }
        .agenda-list-card:hover { transform: translateY(-2px); }
        .list-time { font-weight: 800; font-size: 0.95rem; margin-bottom: 2px; }
        .list-title { font-weight: 400; font-size: 1rem; }
        .list-loc { font-size: 0.85rem; opacity: 0.8; margin-top: 4px; display: flex; align-items: center; gap: 5px;}

        /* --- 7. MODAL DETAIL MODERN (DIPERBARUI) --- */
        .modal-content { border-radius: 20px; border: none; overflow: hidden; }
        .detail-label-small { font-size: 0.75rem; text-transform: uppercase; color: #9ca3af; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 4px; }
        .detail-text-content { font-size: 1.05rem; color: #1f2937; font-weight: 600; }
        .detail-section { margin-bottom: 25px; display: flex; gap: 18px; align-items: center; }
        
        /* --- UPDATE: MODERN ICON BOX --- */
        .detail-icon-box {
            width: 52px; height: 52px; /* Sedikit lebih besar */
            border-radius: 50%; /* Lingkaran penuh */
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; /* Icon lebih besar */
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08); /* Efek melayang halus */
            transition: all 0.3s ease;
        }
        .detail-icon-box:hover { transform: translateY(-3px) scale(1.05); box-shadow: 0 6px 15px rgba(0,0,0,0.12); }

        /* Tema Hijau (Gradient Modern) */
        .theme-green .detail-icon-box { 
            background: linear-gradient(135deg, #d1e7dd 0%, #a8d5c2 100%); 
            color: #0f5132; 
        }
        /* Tema Oranye (Gradient Modern) */
        .theme-orange .detail-icon-box { 
            background: linear-gradient(135deg, #ffe5d0 0%, #ffd1b0 100%); 
            color: #c2410c; 
        }

        .desc-box { background: #f9fafb; padding: 20px; border-radius: 16px; border: 1px solid #f3f4f6; color: #4b5563; font-size: 0.95rem; line-height: 1.6; }
        
        .footer-link { 
            text-decoration: none; color: white !important; font-weight: 600; font-size: 0.9rem; transition: 0.2s; text-shadow: 0 1px 3px rgba(0,0,0,0.8);
        }
        .footer-link:hover { color: #198754 !important; text-decoration: underline; }
        .btn-close-custom { background-color: #1f2937; border: none; color: white; font-weight: 700; padding: 12px; transition: all 0.3s ease; font-size: 1rem;}
        .btn-close-custom:hover { background-color: #000000 !important; color: white !important; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
    </style>
</head>
<body class="theme-green"> 

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <div class="d-flex align-items-center">
                <div class="brand-logo me-3">
                    <img src="{{ asset('images/logo_batang.png') }}" alt="Logo">
                </div>
                <div class="brand-text">
                    <h5>Dinas Kesehatan</h5>
                    <p>Sistem Informasi Agenda Dinas Kesehatan</p>
                </div>
            </div>
            <div class="ms-auto">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-dark fw-bold px-4 rounded-pill">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-dark fw-bold px-4 rounded-pill">Login</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <div class="container" style="margin-top: 100px;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-white mb-1" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Jadwal Kegiatan</h2>
            <p class="text-white mb-0" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">Agenda resmi kegiatan Dinas Kesehatan bulan ini</p>
        </div>

        <div class="calendar-container">
            <div id='calendar'></div>
        </div>
        
        <div class="text-center mb-5">
            <a href="https://www.linkedin.com/in/indra-maulana-a33334250/" target="_blank" class="footer-link">
                &copy; 2026 Dinas Kesehatan - Magang UNNES
            </a>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-bottom-0 pb-0" style="padding: 25px 30px 10px;">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="opacity: 0.5;"></button>
                </div>
                
                <div class="modal-body" style="padding: 0 30px 30px 30px;">
                    <h3 id="detailTitle" class="fw-bold text-dark mb-4 mt-0" style="line-height: 1.3; font-size: 1.75rem;"></h3>

                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="detail-section">
                                <div class="detail-icon-box">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <div>
                                    <div class="detail-label-small">Waktu Pelaksanaan</div>
                                    <div id="detailTime" class="detail-text-content"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="detail-section">
                                <div class="detail-icon-box">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div>
                                    <div class="detail-label-small">Tempat / Lokasi</div>
                                    <div id="detailLocation" class="detail-text-content"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3 gap-3">
                             <div class="detail-icon-box" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="detail-label-small mb-0" style="font-size: 0.85rem;">Peserta / Tujuan</div>
                        </div>
                        <div id="detailParticipantsWrapper" class="d-flex flex-wrap gap-2 ps-2"></div>
                    </div>

                    <div class="desc-box" id="detailDesc"></div>
                </div>
                
                <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-close-custom w-100 rounded-pill" data-bs-dismiss="modal">Tutup Detail</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentType = 'general';
        const colors = {
            agenda: { even: { bg: '#d1e7dd', text: '#146c43' }, odd:  { bg: '#e0f2fe', text: '#0284c7' } },
            rooms: { even: { bg: '#ffe5d0', text: '#c2410c' }, odd:  { bg: '#fee2e2', text: '#b91c1c' } }
        };

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                
                customButtons: {
                    btnAgenda: { text: 'Agenda', click: function() { loadEvents('general', 'btnAgenda'); } },
                    btnRooms: { text: 'Rooms', click: function() { loadEvents('meeting_room', 'btnRooms'); } }
                },

                headerToolbar: { left: 'title', center: '', right: 'btnAgenda,btnRooms,listMonth prev,today,next' },
                buttonText: { list: 'List' }, 
                events: '{{ route("agenda.feed") }}?type=general',

                datesSet: function(info) {
                    if (info.view.type === 'listMonth') {
                        var now = new Date(); now.setHours(0,0,0,0);
                        var todayStr = now.getFullYear() + "-" + String(now.getMonth() + 1).padStart(2, '0') + "-" + String(now.getDate()).padStart(2, '0');

                        document.querySelectorAll('.fc-list-day').forEach(function(header) {
                            var dateStr = header.getAttribute('data-date');
                            if (dateStr) {
                                var headerDate = new Date(dateStr); headerDate.setHours(0,0,0,0);
                                if (headerDate < now) { header.style.display = 'none'; }
                                if (dateStr === todayStr) {
                                    var titleEl = header.querySelector('.fc-list-day-text');
                                    if(titleEl) { 
                                        titleEl.innerText = "Hari Ini"; 
                                        titleEl.style.color = currentType === 'general' ? '#198754' : '#fd7e14'; 
                                    }
                                }
                            }
                        });
                    }
                },

                eventDidMount: function(arg) {
                    if (arg.view.type === 'listMonth') {
                        var now = new Date(); now.setHours(0,0,0,0);
                        var eventDate = new Date(arg.event.start); eventDate.setHours(0,0,0,0);
                        if (eventDate < now) { arg.el.style.display = 'none'; }
                    }
                },

                eventContent: function(arg) {
                    let event = arg.event;
                    let start = event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                    let end = event.end ? event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) : '';
                    let timeDisplay = end ? `${start} - ${end}` : start;

                    let eventId = parseInt(event.id) || 0;
                    let themeSet = (currentType === 'general') ? colors.agenda : colors.rooms;
                    let theme = (eventId % 2 === 0) ? themeSet.even : themeSet.odd;

                    if (arg.view.type === 'listMonth') {
                        let card = document.createElement('div');
                        card.className = 'agenda-list-card';
                        card.style.backgroundColor = theme.bg;
                        card.style.color = theme.text;
                        card.style.border = 'none'; 
                        card.innerHTML = `<div><div class="list-time">${timeDisplay}</div> <div class="list-title">${event.title}</div><div class="list-loc"><i class="bi bi-geo-alt-fill"></i> ${event.extendedProps.location || '-'}</div></div><i class="bi bi-chevron-right" style="opacity: 0.5;"></i>`;
                        return { domNodes: [card] };
                    } else {
                        let capsule = document.createElement('div');
                        capsule.className = 'event-capsule';
                        capsule.style.backgroundColor = theme.bg;
                        capsule.style.color = theme.text;
                        capsule.innerHTML = `<span class="event-time">${start}</span> <span class="event-title">${event.title}</span>`;
                        return { domNodes: [capsule] };
                    }
                },

                eventClick: function(info) {
                    var props = info.event.extendedProps;
                    
                    document.getElementById('detailTitle').innerText = info.event.title;
                    var modalTitle = document.getElementById('detailTitle');
                    
                    // Ganti warna judul berdasarkan tipe
                    modalTitle.className = currentType === 'general' ? 'fw-bold text-success mb-2' : 'fw-bold text-danger mb-2';

                    document.getElementById('detailLocation').innerText = props.location || '-';
                    document.getElementById('detailDesc').innerText = props.description || 'Tidak ada catatan tambahan.';
                    
                    var dateStr = info.event.start.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                    var start = info.event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                    var end = info.event.end ? info.event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) : '';
                    document.getElementById('detailTime').innerText = `${dateStr}\nPukul ${start} - ${end}`;

                    var wrapper = document.getElementById('detailParticipantsWrapper'); wrapper.innerHTML = '';
                    var pData = props.participants;
                    
                    // Style Badge Peserta (Pill Kapsul)
                    if(Array.isArray(pData)) {
                        pData.forEach(p => {
                            let badgeClass = currentType === 'general' 
                                ? 'bg-success-subtle text-success border-success-subtle' 
                                : 'bg-danger-subtle text-danger border-danger-subtle';
                            
                            wrapper.innerHTML += `<span class="badge rounded-pill border px-3 py-2 fw-normal ${badgeClass}">${p}</span>`;
                        });
                    } else {
                        wrapper.innerHTML = `<span class="text-muted small">${pData || '-'}</span>`;
                    }

                    new bootstrap.Modal(document.getElementById('detailModal')).show();
                },
            });

            calendar.render();

            setTimeout(() => {
                document.querySelector('.fc-btnAgenda-button').classList.add('active-mode');
            }, 100);

            window.loadEvents = function(type, btnId) {
                currentType = type;
                if(type === 'general') {
                    document.body.classList.remove('theme-orange'); document.body.classList.add('theme-green');
                } else {
                    document.body.classList.remove('theme-green'); document.body.classList.add('theme-orange');
                }
                calendar.changeView('dayGridMonth');
                calendar.removeAllEventSources();
                calendar.addEventSource("{{ route('agenda.feed') }}?type=" + type);
                document.querySelector('.fc-btnAgenda-button').classList.remove('active-mode');
                document.querySelector('.fc-btnRooms-button').classList.remove('active-mode');
                document.querySelector('.fc-' + btnId + '-button').classList.add('active-mode');
            };
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
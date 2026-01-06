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
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }

        /* --- 1. GLOBAL RESET (Hapus Garis Bawah) --- */
        a { text-decoration: none !important; }

        /* --- 2. HEADER PEGAWAI (HIJAU + JAM) --- */
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
        
        /* Ikon Jam Besar */
        .header-icon {
            font-size: 2.5rem; opacity: 0.8; background: rgba(255,255,255,0.1);
            width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
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

        /* --- 5. PERBAIKAN TAMPILAN HARI & TANGGAL (SAMA DENGAN ADMIN) --- */
        
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
            text-transform: uppercase;
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

        /* Hari Ini (Lingkaran Hijau) - Teks Tetap Putih */
        .fc-day-today .fc-daygrid-day-number {
            background-color: #198754 !important; 
            color: #ffffff !important; /* Putih */
            width: 32px; height: 32px;
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
            text-decoration: none !important;
        }
        .event-time-text { font-weight: 800; margin-right: 6px; font-size: 0.9rem; }
        .event-title-text { font-weight: 600; font-size: 0.9rem; }

        /* --- 7. LIST VIEW (KARTU PEGAWAI) --- */
        .custom-card {
            background: white; border: 1px solid #edf2f7; border-radius: 12px;
            padding: 16px; margin: 8px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.03);
            display: flex; flex-direction: column; gap: 5px; position: relative;
        }
        .custom-card:hover { border-color: #198754; transform: translateY(-2px); transition: 0.2s; }

        .custom-time { font-weight: 700; color: #111827; }
        .custom-title { font-weight: 700; font-size: 1.1rem; color: #198754; }
        .custom-loc { color: #6b7280; font-size: 0.9rem; }

        .fc-list-event-graphic, .fc-list-event-time { display: none; }
        .fc-list-event:hover td { background: transparent !important; }
        .fc-list-day-text { font-size: 1.1rem; font-weight: 700; color: #198754; margin-top: 20px; display: block; text-decoration: none !important; }
        .fc-list-day-cushion { background: transparent !important; }
    </style>
</head>
<body>

    <div class="header-section">
        <div class="app-title">
            <h4>Agenda Dinas Kesehatan</h4>
            <p>Jadwal Kegiatan Anda</p>
        </div>
        <div class="header-icon">
            <i class="bi bi-clock"></i>
        </div>
    </div>

    <div class="calendar-card">
        <div id='calendar'></div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">Detail Kegiatan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <h4 id="eventTitle" class="fw-bold text-dark mb-3"></h4>
                    <div class="mb-2"><i class="bi bi-clock me-2 text-success"></i><span id="eventTime"></span></div>
                    <div class="mb-2"><i class="bi bi-geo-alt me-2 text-success"></i><span id="eventLocation"></span></div>
                    <div class="bg-light p-3 rounded mt-3" id="eventDesc"></div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Tutup</button>
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
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Kalender',
                    list: 'List Agenda'
                },

                events: '{{ route("agenda.feed") }}',

                // --- BAGIAN PENTING: MENGATUR ISI KOTAK ---
                eventContent: function(arg) {
                    let event = arg.event;
                    let start = event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}); // 07.15

                    // 1. JIKA TAMPILAN LIST (KARTU)
                    if (arg.view.type === 'listMonth') {
                        let end = event.end ? event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) : '';
                        let time = end ? `${start} - ${end}` : start;
                        
                        let card = document.createElement('div');
                        card.className = 'custom-card';
                        card.innerHTML = `
                            <div class="d-flex justify-content-between"><span class="custom-time">${time}</span></div>
                            <div class="custom-title">${event.title}</div>
                            <div class="custom-loc"><i class="bi bi-geo-alt-fill"></i> ${event.extendedProps.location || '-'}</div>
                        `;
                        return { domNodes: [card] };
                    } 
                    
                    // 2. JIKA TAMPILAN BULAN (KOTAK HIJAU)
                    // Kita paksa isi teksnya agar tidak kosong!
                    else {
                        let content = document.createElement('div');
                        content.style.display = 'flex';
                        content.style.alignItems = 'center';
                        
                        // Isi: Jam (Tebal) + Judul
                        content.innerHTML = `
                            <span class="event-time-text">${start}</span>
                            <span class="event-title-text">${event.title}</span>
                        `;
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
                    new bootstrap.Modal(document.getElementById('detailModal')).show();
                }
            });
            calendar.render();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
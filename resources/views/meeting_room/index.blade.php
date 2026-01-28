@extends('layouts.admin')

@section('title', 'Meeting Room')
@section('page_title', 'Meeting Room')
@section('page_subtitle', 'Kelola jadwal penggunaan ruang rapat dinas')

@section('content')

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <style>
        /* --- STYLE DASAR --- */
        .calendar-card {
            background: white; padding: 25px;
            border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #f3f4f6;
        }
        
        /* HEADER & BUTTONS KALENDER */
        .fc-toolbar-title { font-size: 1.5rem !important; font-weight: 800; color: #1f2937; letter-spacing: -0.5px; }
        .fc .fc-button {
            background-color: transparent !important; border: none !important; box-shadow: none !important;
            color: #9ca3af !important; font-weight: 600 !important; font-size: 0.9rem !important;
            padding: 8px 16px !important; text-transform: capitalize; transition: all 0.2s ease;
        }
        .fc .fc-button:hover { color: #374151 !important; background: #f3f4f6 !important; border-radius: 8px; }
        .fc .fc-icon { font-size: 1.2rem; font-weight: bold; color: #111827; }

        /* Tombol Aktif (ORANYE Kapsul) */
        .fc-dayGridMonth-button.fc-button-active, 
        .fc-listMonth-button.fc-button-active {
            color: #fd7e14 !important; background-color: #fff7ed !important;
            border-radius: 50px !important; padding: 8px 24px !important; font-weight: 800 !important;
        }
        .fc-today-button {
            color: #111827 !important; background-color: #f3f4f6 !important;
            border-radius: 50px !important; padding: 8px 20px !important; margin-right: 5px !important;
        }

        /* KALENDER ELEMEN */
        .fc-col-header-cell { background-color: #f9fafb !important; padding: 15px 0 !important; border: none !important; border-bottom: 1px solid #f3f4f6 !important; }
        .fc-scrollgrid { border: none !important; }
        .fc-theme-standard td, .fc-theme-standard th { border: 1px solid #f3f4f6; }
        .fc-col-header-cell-cushion { text-decoration: none !important; color: #6b7280 !important; font-weight: 700 !important; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; }
        
        .fc-daygrid-day-top { justify-content: center; margin-top: 5px; }
        .fc-daygrid-day-number { color: #4b5563 !important; text-decoration: none !important; font-weight: 600; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; z-index: 2; }
        .fc-day-today { background: transparent !important; }
        .fc-day-today .fc-daygrid-day-number { background-color: #fd7e14 !important; color: white !important; box-shadow: 0 4px 10px rgba(253, 126, 20, 0.3); }
        
        .fc-daygrid-event { border: none !important; padding: 2px !important; margin-top: 4px !important; background: transparent !important; cursor: pointer; }
        
        .event-capsule {
            display: flex; align-items: center; gap: 8px; padding: 5px 10px;
            border-radius: 8px; font-size: 0.8rem; box-shadow: 0 2px 4px rgba(0,0,0,0.03);
            transition: transform 0.1s; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;
        }
        .event-capsule:hover { transform: scale(1.02); }
        .event-time { font-weight: 800; font-size: 0.75rem; }
        .event-title { font-weight: 400; }

        .fc-list-table td { border: none !important; background: transparent !important; }
        .fc-list-day-cushion { background-color: transparent !important; padding-top: 20px !important; }
        .fc-list-day-text { font-size: 1.1rem; font-weight: 800; color: #111827; text-decoration: none !important; }
        .fc-list-day-side-text { font-weight: 600; color: #9ca3af !important; text-decoration: none !important; }
        .fc-list-event-time, .fc-list-event-graphic { display: none !important; }
        
        .agenda-list-card { 
            padding: 15px 20px; border-radius: 12px; margin-bottom: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center;
            transition: transform 0.2s; border: none;
        }
        .agenda-list-card:hover { transform: translateY(-2px); }
        .list-time { font-weight: 800; font-size: 0.95rem; margin-bottom: 2px; }
        .list-title { font-weight: 400; font-size: 1rem; }
        .list-loc { font-size: 0.85rem; opacity: 0.8; margin-top: 4px; display: flex; align-items: center; gap: 5px;}

        /* TOMBOL UTAMA */
        .btn-custom-orange { 
            background: linear-gradient(135deg, #fd7e14 0%, #d9480f 100%); border: none; color: white; 
            font-weight: 700; padding: 10px 25px; border-radius: 50px; display: inline-flex; align-items: center; gap: 8px; 
            box-shadow: 0 4px 15px rgba(253, 126, 20, 0.3); transition: all 0.3s ease;
        }
        .btn-custom-orange:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(253, 126, 20, 0.4); color: white; }

        /* --- STYLE MODAL DETAIL MODERN --- */
        .modal-content { border-radius: 20px; border: none; overflow: hidden; }
        .detail-label-small { font-size: 0.75rem; text-transform: uppercase; color: #9ca3af; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 4px; }
        .detail-text-content { font-size: 1.05rem; color: #1f2937; font-weight: 600; }
        .detail-section { margin-bottom: 25px; display: flex; gap: 18px; align-items: center; }
        
        /* ICON MODERN (Lingkaran + Gradient) */
        .detail-icon-box {
            width: 52px; height: 52px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        .detail-icon-box:hover { transform: translateY(-3px) scale(1.05); }

        /* Varian Warna Icon */
        .icon-time { background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); color: #0284c7; }
        .icon-loc { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #dc2626; }
        .icon-people { background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%); color: #ea580c; } /* Oranye utk Meeting Room */

        .desc-box { background: #f9fafb; padding: 20px; border-radius: 16px; border: 1px solid #f3f4f6; color: #4b5563; font-size: 0.95rem; line-height: 1.6; }

        /* MODAL & FORM UMUM */
        .form-label-bold { font-weight: 600; font-size: 0.9rem; color: #374151; }
        .select2-container .select2-selection--multiple { min-height: 38px; border: 1px solid #ced4da; border-radius: 0.375rem; }

        /* SELECT2 HIJAU KOTAK TUMPUL */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #198754 !important; border: none !important; color: white !important;
            border-radius: 8px !important; padding: 5px 10px !important; font-size: 0.85rem !important; font-weight: 600 !important; margin-top: 6px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white !important; border-right: 1px solid rgba(255,255,255,0.3) !important; margin-right: 5px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover { background-color: #146c43 !important; }
    </style>

    <div class="d-flex justify-content-end mb-4">
        <button class="btn btn-custom-orange" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-calendar-plus fs-6"></i> Booking Ruangan
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4"><i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}<button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4"><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}<button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="calendar-card"><div id='calendar'></div></div>

    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-bottom-0 pb-0" style="padding: 25px 30px 10px;">
                    <div class="d-flex align-items-center text-muted small"><i class="bi bi-building me-1"></i> Detail Booking</div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="opacity: 0.5;"></button>
                </div>
                
                <div class="modal-body" style="padding: 0 30px 30px 30px;">
                    <h3 id="detailTitle" class="fw-bold text-dark mb-1 mt-0" style="line-height: 1.3; font-size: 1.6rem;"></h3>
                    <div class="text-secondary small mb-4">
                        Dibuat oleh: <span id="detailCreator" class="fw-bold text-danger"></span>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="detail-section">
                                <div class="detail-icon-box icon-time">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <div>
                                    <div class="detail-label-small">Waktu Penggunaan</div>
                                    <div id="detailTime" class="detail-text-content"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="detail-section">
                                <div class="detail-icon-box icon-loc">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div>
                                    <div class="detail-label-small">Ruangan</div>
                                    <div id="detailLocation" class="detail-text-content"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3 gap-3">
                             <div class="detail-icon-box icon-people" style="width: 40px; height: 40px; font-size: 1.1rem;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="detail-label-small mb-0" style="font-size: 0.85rem;">Bidang / Peserta</div>
                        </div>
                        <div id="detailParticipantsWrapper" class="d-flex flex-wrap gap-2 ps-2"></div>
                    </div>

                    <div class="desc-box mb-3" id="detailDesc"></div>
                    
                    <div id="notOwnerAlert" class="alert alert-warning border-0 small align-items-center mb-3" style="display:none; border-radius: 12px; background-color: #fff3cd; color: #856404; border-left: 5px solid #ffc107;"></div>

                    <div id="actionButtons" class="d-flex gap-2 mt-4" style="display: none;">
                        <button id="btnOpenEdit" class="btn btn-warning text-white fw-bold py-2 flex-grow-1 shadow-sm rounded-pill">
                            <i class="bi bi-pencil-square me-2"></i> Edit Booking
                        </button>
                        <form id="formDelete" action="javascript:void(0);" method="POST" class="flex-grow-1">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger fw-bold w-100 py-2 shadow-sm rounded-pill" onclick="return confirm('Batalkan booking ruangan ini?')">
                                <i class="bi bi-trash me-2"></i> Batalkan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0"><h5 class="modal-title fw-bold">Booking Ruangan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body pt-0">
                    <form action="{{ route('meeting-room.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="meeting_room">
                        
                        <div class="mb-3"><label class="form-label-bold">Nama Kegiatan <span class="text-danger">*</span></label><input type="text" name="title" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label-bold">Tanggal <span class="text-danger">*</span></label><input type="date" name="date" class="form-control" required></div>
                        <div class="row mb-3"><div class="col-md-6"><label class="form-label-bold">Mulai</label><input type="text" name="start_hour" class="form-control timepicker-24h bg-white" placeholder="08:00" required></div><div class="col-md-6"><label class="form-label-bold">Selesai</label><input type="text" name="end_hour" class="form-control timepicker-24h bg-white" placeholder="12:00"></div></div>
                        
                        <div class="mb-3">
                            <label class="form-label-bold">Pilih Ruangan <span class="text-danger">*</span></label>
                            <select name="location" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Ruangan --</option>
                                <option value="Aula Utama Dinas Kesehatan Lt 2">Aula Utama Dinas Kesehatan Lt 2</option>
                                <option value="Kepala Dinas Kesehatan Lt 1">Kepala Dinas Kesehatan Lt 1</option>
                                <option value="Sekertaris Dinas Kesehatan Lt 1">Sekertaris Dinas Kesehatan Lt 1</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label-bold">Bidang Pengguna</label>
                            <select name="participants[]" class="form-select select2-create" multiple="multiple" style="width: 100%">
                                <option value="Sekretariat">Sekretariat</option>
                                <option value="Bidang Kesehatan Masyarakat (Kesmas)">Bidang Kesehatan Masyarakat (Kesmas)</option>
                                <option value="Bidang Pencegahan & Pengendalian Penyakit (P2P)">Bidang Pencegahan & Pengendalian Penyakit (P2P)</option>
                                <option value="Bidang Pelayanan Kesehatan (Yankes)">Bidang Pelayanan Kesehatan (Yankes)</option>
                                <option value="Bidang Sumber Daya Kesehatan (SDK)">Bidang Sumber Daya Kesehatan (SDK)</option>
                                <option value="Kepala Dinas & Pejabat Struktural">Kepala Dinas & Pejabat Struktural</option>
                            </select>
                        </div>

                        <div class="mb-3"><label class="form-label-bold">Keterangan Tambahan</label><textarea name="description" class="form-control" rows="3"></textarea></div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-custom-orange">Simpan Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0"><h5 class="modal-title fw-bold">Edit Booking Ruangan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body pt-0">
                    <form id="formEditAgenda" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="type" value="meeting_room">
                        
                        <div class="mb-3"><label class="form-label-bold">Nama Kegiatan</label><input type="text" id="editTitle" name="title" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label-bold">Tanggal</label><input type="date" id="editDate" name="date" class="form-control" required></div>
                        <div class="row mb-3"><div class="col-md-6"><label class="form-label-bold">Mulai</label><input type="text" id="editStart" name="start_hour" class="form-control timepicker-24h bg-white" required></div><div class="col-md-6"><label class="form-label-bold">Selesai</label><input type="text" id="editEnd" name="end_hour" class="form-control timepicker-24h bg-white"></div></div>
                        
                        <div class="mb-3">
                            <label class="form-label-bold">Pilih Ruangan</label>
                            <select id="editLocation" name="location" class="form-select" required>
                                <option value="Aula Utama Dinas Kesehatan Lt 2">Aula Utama Dinas Kesehatan Lt 2</option>
                                <option value="Kepala Dinas Kesehatan Lt 1">Kepala Dinas Kesehatan Lt 1</option>
                                <option value="Sekertaris Dinas Kesehatan Lt 1">Sekertaris Dinas Kesehatan Lt 1</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-bold">Bidang Pengguna</label>
                            <select id="editParticipants" name="participants[]" class="form-select select2-edit" multiple="multiple" style="width: 100%">
                                <option value="Sekretariat">Sekretariat</option>
                                <option value="Bidang Kesehatan Masyarakat (Kesmas)">Bidang Kesehatan Masyarakat (Kesmas)</option>
                                <option value="Bidang Pencegahan & Pengendalian Penyakit (P2P)">Bidang Pencegahan & Pengendalian Penyakit (P2P)</option>
                                <option value="Bidang Pelayanan Kesehatan (Yankes)">Bidang Pelayanan Kesehatan (Yankes)</option>
                                <option value="Bidang Sumber Daya Kesehatan (SDK)">Bidang Sumber Daya Kesehatan (SDK)</option>
                                <option value="Kepala Dinas & Pejabat Struktural">Kepala Dinas & Pejabat Struktural</option>
                            </select>
                        </div>
                        <div class="mb-3"><label class="form-label-bold">Keterangan</label><textarea id="editDescription" name="description" class="form-control" rows="3"></textarea></div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning text-white fw-bold">Update Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.select2-create').select2({ dropdownParent: $('#createModal'), placeholder: "Pilih...", allowClear: true });
            $('.select2-edit').select2({ dropdownParent: $('#editModal'), placeholder: "Pilih...", allowClear: true });
            $(".timepicker-24h").flatpickr({ enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, allowInput: true, minuteIncrement: 1 });
        });

        // Warna Tema ROOMS
        const colors = { even: { bg: '#ffe8cc', text: '#d9480f' }, odd:  { bg: '#ffe3e3', text: '#c92a2a' } };

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: { left: 'title', center: '', right: 'dayGridMonth,listMonth prev,today,next' },
                buttonText: { dayGridMonth: 'Rooms', listMonth: 'List', today: 'Today' },
                events: '{{ route("agenda.feed") }}?type=meeting_room', 

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
                                    if(titleEl) { titleEl.innerText = "Hari Ini"; titleEl.style.color = "#fd7e14"; } 
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
                    let eventId = parseInt(event.id) || 0;
                    let theme = (eventId % 2 === 0) ? colors.even : colors.odd;

                    if (arg.view.type === 'listMonth') {
                        let end = event.end ? event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) : '';
                        let timeRange = end ? `${start} - ${end}` : start;
                        let card = document.createElement('div');
                        card.className = 'agenda-list-card';
                        card.style.backgroundColor = theme.bg;
                        card.style.color = theme.text;
                        card.innerHTML = `<div><div class="list-time">${timeRange}</div><div class="list-title">${event.title}</div><div class="list-loc"><i class="bi bi-geo-alt-fill"></i> ${event.extendedProps.location || '-'}</div></div><i class="bi bi-pencil-square" style="opacity: 0.5;"></i>`;
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

                // --- LOGIKA KLIK DETAIL ---
                eventClick: function(info) {
                    var event = info.event;
                    var props = event.extendedProps;
                    
                    var actionButtons = document.getElementById('actionButtons'); 
                    var notOwnerAlert = document.getElementById('notOwnerAlert');
                    var btnOpenEdit = document.getElementById('btnOpenEdit');
                    var formDelete = document.getElementById('formDelete');

                    // 1. RESET TAMPILAN
                    actionButtons.style.display = 'none'; 
                    notOwnerAlert.style.display = 'none'; 
                    notOwnerAlert.innerHTML = '';
                    btnOpenEdit.onclick = null;
                    formDelete.action = "javascript:void(0);";

                    document.getElementById('detailTitle').innerText = event.title;
                    document.getElementById('detailLocation').innerText = props.location || '-';
                    document.getElementById('detailDesc').innerText = props.description || 'Tidak ada keterangan.';
                    
                    var creatorName = props.creator_name || 'Admin lain';
                    document.getElementById('detailCreator').innerText = creatorName;

                    var wrapper = document.getElementById('detailParticipantsWrapper'); wrapper.innerHTML = ''; 
                    var pData = props.participants; 
                    if(Array.isArray(pData)) { pData.forEach(p => { let b = document.createElement('span'); b.className='badge rounded-pill bg-danger-subtle text-danger-emphasis border border-danger-subtle py-2 px-3 fw-normal'; b.innerText=p; wrapper.appendChild(b); }); } 
                    else if (pData) { let b = document.createElement('span'); b.className='badge rounded-pill bg-danger-subtle text-danger-emphasis border border-danger-subtle py-2 px-3 fw-normal'; b.innerText=pData; wrapper.appendChild(b); } 
                    else { wrapper.innerText = '-'; }
                    
                    var start = event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                    var end = event.end ? event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) : '';
                    document.getElementById('detailTime').innerText = event.start.toLocaleDateString('id-ID', { dateStyle: 'full' }) + ' • ' + start + (end ? ' - ' + end : '');
                    
                    // 2. LOGIKA HAK AKSES
                    if (props.can_edit) {
                        actionButtons.style.display = 'flex'; 
                        
                        var baseUrl = "{{ url('/meeting-room') }}"; 
                        if(event.id) {
                            formDelete.action = baseUrl + "/" + event.id;
                        }

                        btnOpenEdit.onclick = function() {
                            var detailModal = bootstrap.Modal.getInstance(document.getElementById('detailModal')); 
                            detailModal.hide();
                            
                            document.getElementById('formEditAgenda').action = baseUrl + "/" + event.id;
                            document.getElementById('editTitle').value = event.title;
                            document.getElementById('editLocation').value = props.location;
                            document.getElementById('editDescription').value = props.description;
                            $('#editParticipants').val(props.participants).trigger('change');
                            
                            var year = event.start.getFullYear(); var month = String(event.start.getMonth() + 1).padStart(2, '0'); var day = String(event.start.getDate()).padStart(2, '0');
                            document.getElementById('editDate').value = `${year}-${month}-${day}`;
                            
                            var startHour = String(event.start.getHours()).padStart(2, '0'); var startMin = String(event.start.getMinutes()).padStart(2, '0'); var timeStr = `${startHour}:${startMin}`;
                            document.getElementById('editStart').value = timeStr; 
                            if(document.getElementById('editStart')._flatpickr) document.getElementById('editStart')._flatpickr.setDate(timeStr);
                            
                            if(event.end) {
                                var endHour = String(event.end.getHours()).padStart(2, '0'); var endMin = String(event.end.getMinutes()).padStart(2, '0'); var endStr = `${endHour}:${endMin}`;
                                document.getElementById('editEnd').value = endStr; 
                                if(document.getElementById('editEnd')._flatpickr) document.getElementById('editEnd')._flatpickr.setDate(endStr);
                            } else {
                                document.getElementById('editEnd').value = ''; 
                                if(document.getElementById('editEnd')._flatpickr) document.getElementById('editEnd')._flatpickr.clear();
                            }
                            
                            new bootstrap.Modal(document.getElementById('editModal')).show();
                        };
                    } else {
                        notOwnerAlert.style.display = 'flex';
                        notOwnerAlert.innerHTML = '<i class="bi bi-lock-fill me-2 fs-5"></i><div>Maaf, Anda tidak memiliki izin untuk mengubah booking ini (Dibuat oleh <strong>' + creatorName + '</strong>).</div>';
                    }

                    new bootstrap.Modal(document.getElementById('detailModal')).show();
                }
            });
            calendar.render();
        });
    </script>
@endsection
@extends('layouts.admin')

@section('title', 'Agenda Dinas')
@section('page_title', 'Agenda Management')
@section('page_subtitle', 'Kelola jadwal dan agenda kegiatan Dinas Kesehatan')

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

        /* Tombol Aktif (Hijau Kapsul) */
        .fc-dayGridMonth-button.fc-button-active, 
        .fc-listMonth-button.fc-button-active {
            color: #198754 !important; background-color: #ecfdf5 !important;
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
        .fc-day-today .fc-daygrid-day-number { background-color: #198754 !important; color: white !important; box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3); }
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

        /* TOMBOL ATAS */
        .btn-outline-custom { 
            background: white; border: 2px solid #e5e7eb; color: #4b5563; font-weight: 700; 
            padding: 10px 20px; border-radius: 50px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
            transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        .btn-outline-custom:hover { 
            border-color: #198754; color: #198754; background-color: #f0fdf4; 
            transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .btn-custom-green { 
            background: linear-gradient(135deg, #198754 0%, #146c43 100%); border: none; color: white; 
            font-weight: 700; padding: 10px 25px; border-radius: 50px; display: inline-flex; align-items: center; gap: 8px; 
            box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3); transition: all 0.3s ease;
        }
        .btn-custom-green:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(25, 135, 84, 0.4); color: white; }

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
        .icon-people { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); color: #16a34a; }

        .desc-box { background: #f9fafb; padding: 20px; border-radius: 16px; border: 1px solid #f3f4f6; color: #4b5563; font-size: 0.95rem; line-height: 1.6; }

        /* MODAL & FORM UMUM */
        .form-label-bold { font-weight: 600; font-size: 0.9rem; color: #374151; }
        .wa-toggle-card { background: #dcfce7; border: 1px solid #bbf7d0; border-radius: 8px; padding: 15px; display: flex; align-items: center; justify-content: space-between;}
        .select2-container .select2-selection--multiple { min-height: 38px; border: 1px solid #ced4da; border-radius: 0.375rem; }

        /* --- SELECT2 HIJAU KOTAK TUMPUL --- */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #198754 !important; border: none !important; color: white !important;
            border-radius: 8px !important; padding: 5px 10px !important; font-size: 0.85rem !important; font-weight: 600 !important; margin-top: 6px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white !important; border-right: 1px solid rgba(255,255,255,0.3) !important; margin-right: 5px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover { background-color: #146c43 !important; }
    </style>

    <div class="d-flex gap-3 justify-content-end mb-4">
        <a href="{{ url('/') }}" target="agenda_web_view" class="btn-outline-custom">
            <i class="bi bi-globe-asia-australia fs-5"></i> Lihat Web
        </a>
        <button class="btn btn-custom-green" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-lg fs-6"></i> Buat Agenda Baru
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="opacity: 0.5;"></button>
                </div>
                
                <div class="modal-body" style="padding: 0 30px 30px 30px;">
                    <h3 id="detailTitle" class="fw-bold text-dark mb-1 mt-0" style="line-height: 1.3; font-size: 1.6rem;"></h3>
                    <div class="text-secondary small mb-4">
                        Dibuat oleh: <span id="detailCreator" class="fw-bold text-success"></span>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="detail-section">
                                <div class="detail-icon-box icon-time">
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
                                <div class="detail-icon-box icon-loc">
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
                             <div class="detail-icon-box icon-people" style="width: 40px; height: 40px; font-size: 1.1rem;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="detail-label-small mb-0" style="font-size: 0.85rem;">Peserta / Tujuan</div>
                        </div>
                        <div id="detailParticipantsWrapper" class="d-flex flex-wrap gap-2 ps-2"></div>
                    </div>

                    <div class="desc-box mb-3" id="detailDesc"></div>
                    
                    <div id="notOwnerAlert" class="alert alert-warning border-0 small align-items-center mb-3" style="display:none; border-radius: 12px; background-color: #fff3cd; color: #856404; border-left: 5px solid #ffc107;"></div>
                    
                    <div id="detailWaActive" class="text-success small fw-bold mb-3 align-items-center" style="display:none;">
                        <i class="bi bi-whatsapp fs-5 me-2"></i> Notifikasi WhatsApp Aktif
                    </div>

                    <div id="actionButtons" class="d-flex gap-2 mt-4" style="display: none;">
                        <button id="btnOpenEdit" class="btn btn-warning text-white fw-bold py-2 flex-grow-1 shadow-sm rounded-pill">
                            <i class="bi bi-pencil-square me-2"></i> Edit Agenda
                        </button>
                        <form id="formDelete" action="javascript:void(0);" method="POST" class="flex-grow-1">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger fw-bold w-100 py-2 shadow-sm rounded-pill" onclick="return confirm('Hapus agenda ini?')">
                                <i class="bi bi-trash me-2"></i> Hapus
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
                <div class="modal-header border-bottom-0"><h5 class="modal-title fw-bold">Input Agenda Kegiatan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body pt-0">
                    <form action="{{ route('agenda.store') }}" method="POST">
                        @csrf
                        <div class="mb-3"><label class="form-label-bold">Nama Kegiatan <span class="text-danger">*</span></label><input type="text" name="title" class="form-control" placeholder="Masukkan nama kegiatan" required></div>
                        <div class="mb-3"><label class="form-label-bold">Tanggal <span class="text-danger">*</span></label><input type="date" name="date" class="form-control" required></div>
                        <div class="row mb-3"><div class="col-md-6"><label class="form-label-bold">Mulai</label><input type="text" name="start_hour" class="form-control timepicker-24h bg-white" placeholder="08:00" required></div><div class="col-md-6"><label class="form-label-bold">Selesai</label><input type="text" name="end_hour" class="form-control timepicker-24h bg-white" placeholder="12:45"></div></div>
                        <div class="mb-3"><label class="form-label-bold">Tempat/Lokasi <span class="text-danger">*</span></label><input type="text" name="location" class="form-control" placeholder="Masukkan lokasi kegiatan" required></div>
                        <div class="mb-3">
                            <label class="form-label-bold">Peserta & Tujuan WA <span class="text-danger">*</span></label>
                            <select name="participants[]" class="form-select select2-create" multiple="multiple" style="width: 100%" required>
                                <option value="Seluruh Pegawai Dinas Kesehatan">Seluruh Pegawai Dinas Kesehatan</option>
                                <option value="Sekretariat">Sekretariat</option>
                                <option value="Bidang Kesehatan Masyarakat (Kesmas)">Bidang Kesehatan Masyarakat (Kesmas)</option>
                                <option value="Bidang Pencegahan & Pengendalian Penyakit (P2P)">Bidang Pencegahan & Pengendalian Penyakit (P2P)</option>
                                <option value="Bidang Pelayanan Kesehatan (Yankes)">Bidang Pelayanan Kesehatan (Yankes)</option>
                                <option value="Bidang Sumber Daya Kesehatan (SDK)">Bidang Sumber Daya Kesehatan (SDK)</option>
                                <option value="Kepala Dinas & Pejabat Struktural">Kepala Dinas & Pejabat Struktural</option>
                            </select>
                            <div class="form-text small text-muted">Jika Notifikasi WA aktif, pesan akan dikirim ke grup sesuai pilihan ini.</div>
                        </div>
                        <div class="mb-3"><label class="form-label-bold">Detail Agenda</label><textarea name="description" class="form-control" rows="3" placeholder="Masukkan detail agenda kegiatan (opsional)"></textarea></div>
                        <div class="wa-toggle-card mb-4">
                            <div><div class="fw-bold text-success"><i class="bi bi-whatsapp"></i> Kirim Notifikasi WhatsApp</div><div class="small text-secondary">Pesan dikirim otomatis 30 menit sebelum acara</div></div>
                            <div class="form-check form-switch"><input class="form-check-input fs-4" type="checkbox" name="is_whatsapp_notify" value="1"></div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-custom-green">Simpan Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0"><h5 class="modal-title fw-bold">Edit Agenda Kegiatan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body pt-0">
                    <form id="formEditAgenda" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3"><label class="form-label-bold">Nama Kegiatan <span class="text-danger">*</span></label><input type="text" id="editTitle" name="title" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label-bold">Tanggal <span class="text-danger">*</span></label><input type="date" id="editDate" name="date" class="form-control" required></div>
                        <div class="row mb-3"><div class="col-md-6"><label class="form-label-bold">Mulai</label><input type="text" id="editStart" name="start_hour" class="form-control timepicker-24h bg-white" required></div><div class="col-md-6"><label class="form-label-bold">Selesai</label><input type="text" id="editEnd" name="end_hour" class="form-control timepicker-24h bg-white"></div></div>
                        <div class="mb-3"><label class="form-label-bold">Tempat/Lokasi <span class="text-danger">*</span></label><input type="text" id="editLocation" name="location" class="form-control" required></div>
                        <div class="mb-3">
                            <label class="form-label-bold">Peserta & Tujuan WA <span class="text-danger">*</span></label>
                            <select id="editParticipants" name="participants[]" class="form-select select2-edit" multiple="multiple" style="width: 100%" required>
                                <option value="Seluruh Pegawai Dinas Kesehatan">Seluruh Pegawai Dinas Kesehatan</option>
                                <option value="Sekretariat">Sekretariat</option>
                                <option value="Bidang Kesehatan Masyarakat (Kesmas)">Bidang Kesehatan Masyarakat (Kesmas)</option>
                                <option value="Bidang Pencegahan & Pengendalian Penyakit (P2P)">Bidang Pencegahan & Pengendalian Penyakit (P2P)</option>
                                <option value="Bidang Pelayanan Kesehatan (Yankes)">Bidang Pelayanan Kesehatan (Yankes)</option>
                                <option value="Bidang Sumber Daya Kesehatan (SDK)">Bidang Sumber Daya Kesehatan (SDK)</option>
                                <option value="Kepala Dinas & Pejabat Struktural">Kepala Dinas & Pejabat Struktural</option>
                            </select>
                        </div>
                        <div class="mb-3"><label class="form-label-bold">Detail Agenda</label><textarea id="editDescription" name="description" class="form-control" rows="3"></textarea></div>
                        <div class="wa-toggle-card mb-4">
                            <div><div class="fw-bold text-success"><i class="bi bi-whatsapp"></i> Kirim Notifikasi WhatsApp</div><div class="small text-secondary">Update pengingat ke peserta via WhatsApp</div></div>
                            <div class="form-check form-switch"><input id="editWa" class="form-check-input fs-4" type="checkbox" name="is_whatsapp_notify" value="1"></div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning text-white fw-bold">Update Agenda</button>
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

        const colors = { even: { bg: '#d1e7dd', text: '#146c43' }, odd:  { bg: '#e0f2fe', text: '#0284c7' } };

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: { left: 'title', center: '', right: 'dayGridMonth,listMonth prev,today,next' },
                buttonText: { dayGridMonth: 'Agenda', listMonth: 'List' },
                events: '{{ route("agenda.feed") }}?type=general',

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

                eventClick: function(info) {
                    var event = info.event;
                    var props = event.extendedProps;
                    
                    var actionButtons = document.getElementById('actionButtons'); 
                    var notOwnerAlert = document.getElementById('notOwnerAlert');
                    var btnOpenEdit = document.getElementById('btnOpenEdit');
                    var detailWaActive = document.getElementById('detailWaActive');
                    var formDelete = document.getElementById('formDelete');

                    // 1. RESET TAMPILAN
                    actionButtons.style.display = 'none'; 
                    notOwnerAlert.style.display = 'none'; 
                    notOwnerAlert.innerHTML = '';
                    detailWaActive.style.display = 'none';
                    btnOpenEdit.onclick = null;
                    formDelete.action = "javascript:void(0);";

                    document.getElementById('detailTitle').innerText = event.title;
                    document.getElementById('detailLocation').innerText = props.location || '-';
                    document.getElementById('detailDesc').innerText = props.description || 'Tidak ada deskripsi.';
                    
                    var creatorName = props.creator_name || 'Admin lain';
                    document.getElementById('detailCreator').innerText = creatorName;

                    var wrapper = document.getElementById('detailParticipantsWrapper'); wrapper.innerHTML = ''; 
                    var pData = props.participants; 
                    if(Array.isArray(pData)) { pData.forEach(p => { let b = document.createElement('span'); b.className='badge rounded-pill bg-success-subtle text-success border border-success-subtle py-2 px-3 fw-normal'; b.innerText=p; wrapper.appendChild(b); }); } 
                    else if (pData) { let b = document.createElement('span'); b.className='badge rounded-pill bg-success-subtle text-success border border-success-subtle py-2 px-3 fw-normal'; b.innerText=pData; wrapper.appendChild(b); } 
                    else { wrapper.innerText = '-'; }
                    
                    var start = event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                    var end = event.end ? event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) : '';
                    document.getElementById('detailTime').innerText = event.start.toLocaleDateString('id-ID', { dateStyle: 'full' }) + ' • ' + start + (end ? ' - ' + end : '');
                    
                    if (props.is_whatsapp_notify == 1) {
                        detailWaActive.style.display = 'flex';
                    }
                    
                    if (props.can_edit) {
                        actionButtons.style.display = 'flex'; 
                        
                        var baseUrl = "{{ url('/agenda') }}"; 
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
                            
                            document.getElementById('editWa').checked = (props.is_whatsapp_notify == 1);
                            new bootstrap.Modal(document.getElementById('editModal')).show();
                        };
                    } else {
                        notOwnerAlert.style.display = 'flex'; 
                        notOwnerAlert.innerHTML = '<i class="bi bi-lock-fill me-2 fs-5"></i><div>Anda hanya bisa melihat agenda ini (Dibuat oleh <strong>' + creatorName + '</strong>).</div>';
                    }

                    new bootstrap.Modal(document.getElementById('detailModal')).show();
                }
            });
            calendar.render();
        });
    </script>
@endsection
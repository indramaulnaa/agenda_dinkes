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
        /* --- STYLE UMUM --- */
        .calendar-card {
            background: white; padding: 25px;
            border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #eaeaea;
        }
        .fc-toolbar-title { font-size: 1.5rem !important; font-weight: 700; color: #1f2937; }
        
        /* HEADER HARI (GRID VIEW) */
        .fc-col-header-cell { background-color: #f9fafb !important; padding: 15px 0 !important; border-bottom: 1px solid #eaeaea !important; }
        .fc-col-header-cell-cushion { color: #6b7280 !important; text-decoration: none !important; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        .fc-daygrid-day-number { color: #374151 !important; text-decoration: none !important; font-weight: 500; margin: 4px; padding: 4px 8px; }
        .fc-day-today { background: none !important; background-color: transparent !important; }
        .fc-day-today .fc-daygrid-day-number { background-color: transparent !important; color: #111827 !important; font-weight: 800 !important; }

        /* TOMBOL TOOLBAR (CLEAN) */
        .fc .fc-button-primary { background-color: transparent !important; border: none !important; color: #6b7280 !important; font-weight: 600; box-shadow: none !important; padding: 8px 16px; transition: 0.2s; }
        .fc .fc-button-primary:hover { background-color: #f3f4f6 !important; color: #111827 !important; }
        .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active { background-color: transparent !important; color: #111827 !important; font-weight: 800 !important; }

        /* --- STYLE KHUSUS LIST VIEW (CARD LOOK) --- */
        .fc-theme-standard .fc-list { border: none !important; }
        .fc-theme-standard .fc-list-day-cushion { background-color: transparent !important; border: none !important; padding: 15px 0 10px 0 !important; }
        .fc-list-event td { border: none !important; background: transparent !important; }
        .fc-list-event:hover td { background: transparent !important; }
        .fc-list-day-text, .fc-list-day-side-text { font-size: 1.1rem; font-weight: 700; color: #111827; text-decoration: none !important; }
        .fc-list-day-cushion a { text-decoration: none !important; pointer-events: none; color: inherit !important; }
        .fc-list-event-title { padding: 0 !important; border: none !important; }
        .fc-list-event-time, .fc-list-event-graphic { display: none !important; }
        .fc-list-empty { display: none !important; }

        /* DESAIN KARTU AGENDA */
        .agenda-list-card {
            background: white; border: 1px solid #e5e7eb; border-radius: 12px;
            padding: 20px; margin-bottom: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            transition: all 0.2s ease-in-out; cursor: pointer;
        }
        .agenda-list-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); border-color: #198754; }

        /* STATUS BADGE */
        .card-status-badge { font-size: 0.75rem; font-weight: 700; padding: 5px 12px; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.5px; }
        .bg-ongoing { background-color: #fff3cd; color: #664d03; } 
        .bg-scheduled { background-color: #cfe2ff; color: #084298; } 
        .bg-selesai { background-color: #d1e7dd; color: #0f5132; } 

        .card-time { font-size: 1rem; font-weight: 800; color: #111827; margin-bottom: 4px; }
        .card-title { font-size: 1.1rem; font-weight: 600; color: #374151; margin-bottom: 8px; line-height: 1.4; }
        .card-location { font-size: 0.9rem; color: #6b7280; display: flex; align-items: center; gap: 6px; }
        .card-participants { margin-top: 10px; padding-top: 10px; border-top: 1px dashed #e5e7eb; font-size: 0.85rem; color: #6b7280; }

        /* STYLE EVENT GRID */
        .fc-daygrid-event { border: none !important; padding: 4px 8px !important; border-radius: 6px; margin-top: 4px !important; cursor: pointer; font-weight: 500; font-size: 0.85rem; transition: none !important; }
        .fc-daygrid-event:hover { opacity: 1 !important; filter: none !important; }
        .event-time-text { font-weight: 900 !important; margin-right: 6px; }

        /* HELPERS */
        .form-label-bold { font-weight: 600; font-size: 0.9rem; color: #374151; }
        .wa-toggle-card { background: #dcfce7; border: 1px solid #bbf7d0; border-radius: 8px; padding: 15px; display: flex; align-items: center; justify-content: space-between;}
        .btn-custom-green { background-color: #198754; border: none; color: white; font-weight: 600; padding: 10px 20px; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; }
        .btn-custom-green:hover { background-color: #157347; color: white; }
        .btn-outline-custom { background: white; border: 1px solid #d1d5db; color: #374151; font-weight: 500; padding: 10px 16px; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;}
        .btn-outline-custom:hover { background-color: #f3f4f6; color: #111827; }
        
        .select2-container .select2-selection--multiple { min-height: 38px; border: 1px solid #ced4da; border-radius: 0.375rem; }
        .select2-container--default .select2-selection--multiple .select2-selection__choice { background-color: #198754 !important; border: 1px solid #146c43 !important; color: white !important; font-size: 0.85rem; margin-top: 6px; padding-left: 5px; display: inline-flex; align-items: center; }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove { color: white !important; border-right: 1px solid #146c43 !important; margin-right: 12px; padding-right: 5px; }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover { background-color: #146c43 !important; color: white !important; }
    </style>

    <div class="d-flex gap-2 justify-content-end mb-4">
        <a href="{{ url('/') }}" target="preview_agenda" class="btn-outline-custom">
            <i class="bi bi-box-arrow-up-right"></i> Lihat Web
        </a>
        <button class="btn btn-custom-green" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-lg"></i> Buat Agenda Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="calendar-card">
        <div id='calendar'></div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Detail Agenda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h4 id="detailTitle" class="fw-bold text-success mb-3"></h4>
                    
                    <div class="d-flex align-items-center mb-2 text-muted">
                        <i class="bi bi-clock me-2 fs-5"></i> 
                        <span id="detailTime"></span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-2 text-muted">
                        <i class="bi bi-geo-alt me-2 fs-5"></i> 
                        <span id="detailLocation"></span>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2 text-muted"><i class="bi bi-people me-2 fs-5"></i> Ditujukan Kepada:</div>
                        <div id="detailParticipantsWrapper" class="d-flex flex-wrap gap-1"></div>
                    </div>

                    <div class="p-3 bg-light rounded text-secondary mb-3" id="detailDesc"></div>

                    <div id="notOwnerAlert" class="alert alert-warning border-0 small mb-0" style="display:none;">
                        <i class="bi bi-lock-fill"></i> Anda hanya bisa melihat agenda ini (Dibuat oleh admin lain).
                    </div>

                    <div id="detailWaActive" class="text-success small fw-bold mb-3" style="display:none;">
                        <i class="bi bi-whatsapp"></i> Notifikasi WhatsApp Aktif
                    </div>

                    <hr>
                    
                    <div id="actionButtons" class="d-flex gap-2" style="display: none;">
                        <button id="btnOpenEdit" class="btn btn-warning text-white flex-grow-1">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
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

    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Input Agenda Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <form action="{{ route('agenda.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label-bold">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="Masukkan nama kegiatan" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-bold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label-bold">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="text" name="start_hour" class="form-control timepicker-24h bg-white" placeholder="Contoh: 07:01" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-bold">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="text" name="end_hour" class="form-control timepicker-24h bg-white" placeholder="Contoh: 12:45">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-bold">Tempat/Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control" placeholder="Masukkan lokasi kegiatan" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-bold">Peserta/Undangan (Bisa Pilih Banyak) <span class="text-danger">*</span></label>
                            <select name="participants[]" class="form-select select2-create" multiple="multiple" style="width: 100%" required>
                                <option value="Seluruh Pegawai Dinas Kesehatan">Seluruh Pegawai Dinas Kesehatan</option>
                                <option value="Sekretariat">Sekretariat</option>
                                <option value="Bidang Kesehatan Masyarakat (Kesmas)">Bidang Kesehatan Masyarakat (Kesmas)</option>
                                <option value="Bidang Pencegahan & Pengendalian Penyakit (P2P)">Bidang Pencegahan & Pengendalian Penyakit (P2P)</option>
                                <option value="Bidang Pelayanan Kesehatan (Yankes)">Bidang Pelayanan Kesehatan (Yankes)</option>
                                <option value="Bidang Sumber Daya Kesehatan (SDK)">Bidang Sumber Daya Kesehatan (SDK)</option>
                                <option value="Kepala Dinas & Pejabat Struktural">Kepala Dinas & Pejabat Struktural</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-bold">Detail Agenda</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Masukkan detail agenda kegiatan (opsional)"></textarea>
                        </div>
                        <div class="wa-toggle-card mb-4">
                            <div>
                                <div class="fw-bold text-success"><i class="bi bi-whatsapp"></i> Kirim Notifikasi WhatsApp</div>
                                <div class="small text-secondary">Otomatis kirim pengingat ke peserta via WhatsApp</div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input fs-4" type="checkbox" name="is_whatsapp_notify" value="1">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
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
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Edit Agenda Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <form id="formEditAgenda" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label-bold">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" id="editTitle" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-bold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" id="editDate" name="date" class="form-control" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label-bold">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="text" id="editStart" name="start_hour" class="form-control timepicker-24h bg-white" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-bold">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="text" id="editEnd" name="end_hour" class="form-control timepicker-24h bg-white">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-bold">Tempat/Lokasi <span class="text-danger">*</span></label>
                            <input type="text" id="editLocation" name="location" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-bold">Peserta/Undangan <span class="text-danger">*</span></label>
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
                        <div class="mb-3">
                            <label class="form-label-bold">Detail Agenda</label>
                            <textarea id="editDescription" name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="wa-toggle-card mb-4">
                            <div>
                                <div class="fw-bold text-success"><i class="bi bi-whatsapp"></i> Kirim Notifikasi WhatsApp</div>
                                <div class="small text-secondary">Update pengingat ke peserta via WhatsApp</div>
                            </div>
                            <div class="form-check form-switch">
                                <input id="editWa" class="form-check-input fs-4" type="checkbox" name="is_whatsapp_notify" value="1">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning text-white fw-bold">Update Agenda</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.select2-create').select2({ dropdownParent: $('#createModal'), placeholder: "Pilih peserta", allowClear: true });
            $('.select2-edit').select2({ dropdownParent: $('#editModal'), placeholder: "Pilih peserta", allowClear: true });
            $(".timepicker-24h").flatpickr({ enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, allowInput: true, minuteIncrement: 1 });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: { left: 'title', center: '', right: 'dayGridMonth,listMonth prev,today,next' },
                buttonText: { today: 'Hari Ini', month: 'Bulan', list: 'List Agenda' },
                
                // FILTER: HANYA GENERAL
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
                                <div><div class="card-time">${timeRange}</div><div class="card-title">${event.title}</div></div>
                                <span class="card-status-badge ${badgeClass}">${badgeLabel}</span>
                            </div>
                            <div class="card-location"><i class="bi bi-geo-alt-fill me-2 text-muted"></i> ${event.extendedProps.location || '-'}</div>
                            <div class="card-participants"><i class="bi bi-people-fill me-1"></i> ${pText}</div>
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

                // --- LOGIKA KLIK & CEK KEPEMILIKAN ---
                eventClick: function(info) {
                    var event = info.event;
                    var props = event.extendedProps;
                    
                    document.getElementById('detailTitle').innerText = event.title;
                    document.getElementById('detailLocation').innerText = props.location || '-';
                    document.getElementById('detailDesc').innerText = props.description || 'Tidak ada deskripsi.';
                    
                    var wrapper = document.getElementById('detailParticipantsWrapper');
                    wrapper.innerHTML = ''; 
                    var pData = props.participants;
                    if(Array.isArray(pData)) { pData.forEach(p => { let b = document.createElement('span'); b.className='badge bg-light text-dark border me-1'; b.innerText=p; wrapper.appendChild(b); }); } 
                    else if (pData) { let b = document.createElement('span'); b.className='badge bg-light text-dark border'; b.innerText=pData; wrapper.appendChild(b); } 
                    else { wrapper.innerText = '-'; }

                    var start = event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                    var end = event.end ? event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) : '';
                    document.getElementById('detailTime').innerText = event.start.toLocaleDateString('id-ID', { dateStyle: 'full' }) + ' • ' + start + (end ? ' - ' + end : '');
                    document.getElementById('detailWaActive').style.display = (props.is_whatsapp_notify == 1) ? 'block' : 'none';

                    // === 2. SET URL DELETE (PENTING: Di luar IF) ===
                    var baseUrl = "{{ url('/agenda') }}"; 
                    document.getElementById('formDelete').action = baseUrl + "/" + event.id;

                    // === 3. CEK KEPEMILIKAN ===
                    var actionButtons = document.getElementById('actionButtons');
                    var notOwnerAlert = document.getElementById('notOwnerAlert');

                    if (props.can_edit) {
                        // Jika MILIK SENDIRI -> Tampilkan Tombol
                        actionButtons.style.display = 'flex';
                        notOwnerAlert.style.display = 'none';
                        
                        document.getElementById('btnOpenEdit').onclick = function() {
                            var detailModal = bootstrap.Modal.getInstance(document.getElementById('detailModal'));
                            detailModal.hide();
                            document.getElementById('formEditAgenda').action = baseUrl + "/" + event.id;
                            document.getElementById('editTitle').value = event.title;
                            document.getElementById('editLocation').value = props.location;
                            document.getElementById('editDescription').value = props.description;
                            $('#editParticipants').val(props.participants).trigger('change');

                            var year = event.start.getFullYear();
                            var month = String(event.start.getMonth() + 1).padStart(2, '0');
                            var day = String(event.start.getDate()).padStart(2, '0');
                            document.getElementById('editDate').value = `${year}-${month}-${day}`;

                            var startHour = String(event.start.getHours()).padStart(2, '0');
                            var startMin = String(event.start.getMinutes()).padStart(2, '0');
                            var timeStr = `${startHour}:${startMin}`;
                            document.getElementById('editStart').value = timeStr;
                            if(document.getElementById('editStart')._flatpickr) document.getElementById('editStart')._flatpickr.setDate(timeStr);

                            if(event.end) {
                                var endHour = String(event.end.getHours()).padStart(2, '0');
                                var endMin = String(event.end.getMinutes()).padStart(2, '0');
                                var endStr = `${endHour}:${endMin}`;
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
                        // Jika BUKAN MILIK SENDIRI -> Sembunyikan Tombol
                        actionButtons.style.display = 'none';
                        notOwnerAlert.style.display = 'block';
                    }

                    new bootstrap.Modal(document.getElementById('detailModal')).show();
                }
            });
            calendar.render();
        });
    </script>
@endsection
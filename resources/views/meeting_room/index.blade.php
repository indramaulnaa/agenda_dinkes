@extends('layouts.admin')

@section('title', 'Booking Meeting Room')
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
        .calendar-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #eaeaea; }
        .fc-toolbar-title { font-size: 1.5rem !important; font-weight: 700; color: #1f2937; }
        .fc-col-header-cell { background-color: #f9fafb !important; padding: 15px 0 !important; border-bottom: 1px solid #eaeaea !important; }
        .fc-col-header-cell-cushion { color: #6b7280 !important; text-decoration: none !important; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        .fc-daygrid-day-number { color: #374151 !important; text-decoration: none !important; font-weight: 500; margin: 4px; padding: 4px 8px; }
        .fc-day-today { background: none !important; }
        .fc .fc-button-primary { background-color: transparent !important; border: none !important; color: #6b7280 !important; font-weight: 600; box-shadow: none !important; padding: 8px 16px; transition: 0.2s; }
        .fc .fc-button-primary:hover { background-color: #f3f4f6 !important; color: #111827 !important; }
        .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active { background-color: #e5e7eb !important; color: #111827 !important; font-weight: 800 !important; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05) !important; }
        .fc-daygrid-event { border: none !important; padding: 4px 8px !important; border-radius: 6px; margin-top: 4px !important; cursor: pointer; font-weight: 500; font-size: 0.85rem; }
        .event-time-text { font-weight: 900 !important; margin-right: 6px; }
        .btn-custom-red { background-color: #dc3545; border: none; color: white; font-weight: 600; padding: 10px 20px; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; }
        .btn-custom-red:hover { background-color: #b02a37; color: white; }
        .form-label-bold { font-weight: 600; font-size: 0.9rem; color: #374151; }
        .select2-container .select2-selection--multiple { min-height: 38px; border: 1px solid #ced4da; border-radius: 0.375rem; }
    </style>

    <div class="d-flex gap-2 justify-content-end mb-4">
        <button class="btn btn-custom-red" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-calendar-plus"></i> Booking Ruangan
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
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0"><h5 class="modal-title fw-bold">Detail Booking</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <h4 id="detailTitle" class="fw-bold text-danger mb-3"></h4>
                    <div class="d-flex align-items-center mb-2 text-muted"><i class="bi bi-clock me-2 fs-5"></i> <span id="detailTime"></span></div>
                    <div class="d-flex align-items-center mb-2 text-muted"><i class="bi bi-geo-alt me-2 fs-5"></i> <span id="detailLocation"></span></div>
                    <div class="mb-3"><div class="d-flex align-items-center mb-2 text-muted"><i class="bi bi-people me-2 fs-5"></i> Divisi/Peserta:</div><div id="detailParticipantsWrapper" class="d-flex flex-wrap gap-1"></div></div>
                    <div class="p-3 bg-light rounded text-secondary mb-3" id="detailDesc"></div>
                    
                    <div id="notOwnerAlert" class="alert alert-warning border-0 small mb-0" style="display:none;"><i class="bi bi-lock-fill"></i> Booking ini dibuat oleh admin lain.</div>
                    <hr>
                    <div id="actionButtons" class="d-flex gap-2" style="display: none;">
                        <button id="btnOpenEdit" class="btn btn-warning text-white flex-grow-1"><i class="bi bi-pencil-square"></i> Edit</button>
                        <form id="formDelete" action="#" method="POST" class="flex-grow-1">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Hapus booking ini?')"><i class="bi bi-trash"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0"><h5 class="modal-title fw-bold text-danger">Booking Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body pt-0">
                    <form action="{{ route('meeting-room.store') }}" method="POST">
                        @csrf
                        <div class="mb-3"><label class="form-label-bold">Pilih Ruangan <span class="text-danger">*</span></label><select name="location" class="form-select form-select-lg" required><option value="" selected disabled>-- Pilih Ruangan --</option><option value="Aula Utama">Aula Utama</option><option value="Ruang Rapat A">Ruang Rapat A</option><option value="Ruang Rapat B">Ruang Rapat B</option><option value="Ruang Diskusi Kecil">Ruang Diskusi Kecil</option></select></div>
                        <div class="mb-3"><label class="form-label-bold">Kegiatan</label><input type="text" name="title" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label-bold">Tanggal</label><input type="date" name="date" class="form-control" required></div>
                        <div class="row mb-3"><div class="col-md-6"><label class="form-label-bold">Mulai</label><input type="text" name="start_hour" class="form-control timepicker-24h bg-white" placeholder="08:00" required></div><div class="col-md-6"><label class="form-label-bold">Selesai</label><input type="text" name="end_hour" class="form-control timepicker-24h bg-white" placeholder="10:00" required></div></div>
                        <div class="mb-3"><label class="form-label-bold">Peserta</label><select name="participants[]" class="form-select select2-create" multiple="multiple" style="width: 100%"><option value="Seluruh Pegawai">Seluruh Pegawai</option><option value="Sekretariat">Sekretariat</option><option value="Bidang Kesmas">Bidang Kesmas</option><option value="Bidang P2P">Bidang P2P</option><option value="Bidang Yankes">Bidang Yankes</option><option value="Bidang SDK">Bidang SDK</option></select></div>
                        <div class="mb-3"><label class="form-label-bold">Catatan</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                        <div class="d-flex justify-content-end gap-2"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-custom-red">Booking</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0"><h5 class="modal-title fw-bold text-warning">Edit Booking</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body pt-0">
                    <form id="formEditBooking" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3"><label class="form-label-bold">Pilih Ruangan <span class="text-danger">*</span></label><select id="editLocation" name="location" class="form-select form-select-lg" required><option value="Aula Utama">Aula Utama</option><option value="Ruang Rapat A">Ruang Rapat A</option><option value="Ruang Rapat B">Ruang Rapat B</option><option value="Ruang Diskusi Kecil">Ruang Diskusi Kecil</option></select></div>
                        <div class="mb-3"><label class="form-label-bold">Kegiatan</label><input type="text" id="editTitle" name="title" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label-bold">Tanggal</label><input type="date" id="editDate" name="date" class="form-control" required></div>
                        <div class="row mb-3"><div class="col-md-6"><label class="form-label-bold">Mulai</label><input type="text" id="editStart" name="start_hour" class="form-control timepicker-24h bg-white" required></div><div class="col-md-6"><label class="form-label-bold">Selesai</label><input type="text" id="editEnd" name="end_hour" class="form-control timepicker-24h bg-white" required></div></div>
                        <div class="mb-3"><label class="form-label-bold">Peserta</label><select id="editParticipants" name="participants[]" class="form-select select2-edit" multiple="multiple" style="width: 100%"><option value="Seluruh Pegawai">Seluruh Pegawai</option><option value="Sekretariat">Sekretariat</option><option value="Bidang Kesmas">Bidang Kesmas</option><option value="Bidang P2P">Bidang P2P</option><option value="Bidang Yankes">Bidang Yankes</option><option value="Bidang SDK">Bidang SDK</option></select></div>
                        <div class="mb-3"><label class="form-label-bold">Catatan</label><textarea id="editDescription" name="description" class="form-control" rows="2"></textarea></div>
                        <div class="d-flex justify-content-end gap-2"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-warning text-white fw-bold">Update Booking</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.select2-create').select2({ dropdownParent: $('#createModal'), placeholder: "Pilih divisi", allowClear: true });
            $('.select2-edit').select2({ dropdownParent: $('#editModal'), placeholder: "Pilih divisi", allowClear: true });
            $(".timepicker-24h").flatpickr({ enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: { left: 'title', center: '', right: 'dayGridMonth,listMonth prev,today,next' },
                buttonText: { today: 'Hari Ini', month: 'Bulan', list: 'List' },
                
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
                                if (dateStr === todayStr) { var titleEl = header.querySelector('.fc-list-day-text'); if(titleEl) { titleEl.innerText = "Hari Ini"; titleEl.style.color = "#198754"; } }
                            }
                        });
                    }
                },
                eventDidMount: function(arg) { if (arg.view.type === 'listMonth') { var now = new Date(); now.setHours(0,0,0,0); if (arg.event.start < now) { arg.el.style.display = 'none'; } } },
                
                eventContent: function(arg) {
                    let event = arg.event;
                    let start = event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                    if (arg.view.type === 'listMonth') {
                        let end = event.end ? event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) : '';
                        let timeRange = end ? `${start} - ${end}` : start;
                        let now = new Date(); let eventEnd = event.end || event.start;
                        let badgeLabel = 'Akan Datang'; let badgeClass = 'bg-scheduled';
                        if (now > eventEnd) { badgeLabel = 'Selesai'; badgeClass = 'bg-selesai'; } else if (now >= event.start && now <= eventEnd) { badgeLabel = 'Sedang Berlangsung'; badgeClass = 'bg-ongoing'; }
                        let pData = event.extendedProps.participants; let pText = Array.isArray(pData) ? pData.join(', ') : pData; if (!pText) pText = "-";
                        let card = document.createElement('div'); card.className = 'agenda-list-card';
                        card.innerHTML = `<div class="d-flex justify-content-between align-items-start"><div><div class="card-time">${timeRange}</div><div class="card-title">${event.title}</div></div><span class="card-status-badge ${badgeClass}">${badgeLabel}</span></div><div class="card-location"><i class="bi bi-geo-alt-fill me-2 text-muted"></i> ${event.extendedProps.location || '-'}</div><div class="card-participants"><i class="bi bi-people-fill me-1"></i> ${pText}</div>`;
                        return { domNodes: [card] };
                    } else {
                        let content = document.createElement('div'); content.style.backgroundColor = event.backgroundColor; content.style.borderColor = event.borderColor; content.style.color = event.textColor; content.style.padding = '4px 8px'; content.style.borderRadius = '6px'; content.style.overflow = 'hidden'; content.style.whiteSpace = 'nowrap'; content.style.textOverflow = 'ellipsis'; content.innerHTML = `<span class="event-time-text">${start}</span> ${event.title}`;
                        return { domNodes: [content] };
                    }
                },

                eventClick: function(info) {
                    var event = info.event;
                    var props = event.extendedProps;
                    
                    document.getElementById('detailTitle').innerText = event.title;
                    document.getElementById('detailLocation').innerText = props.location || '-';
                    document.getElementById('detailDesc').innerText = props.description || 'Tidak ada catatan.';
                    var wrapper = document.getElementById('detailParticipantsWrapper'); wrapper.innerHTML = ''; 
                    var pData = props.participants; if(Array.isArray(pData)) { pData.forEach(p => { let b = document.createElement('span'); b.className='badge bg-light text-dark border me-1'; b.innerText=p; wrapper.appendChild(b); }); } else if (pData) { let b = document.createElement('span'); b.className='badge bg-light text-dark border'; b.innerText=pData; wrapper.appendChild(b); } else { wrapper.innerText = '-'; }

                    var start = event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                    var end = event.end ? event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) : '';
                    document.getElementById('detailTime').innerText = event.start.toLocaleDateString('id-ID', { dateStyle: 'full' }) + ' • ' + start + (end ? ' - ' + end : '');

                    // URL Delete (Gunakan /agenda untuk delete, karena controller handle destroy di sana)
                    // TAPI untuk EDIT, gunakan /meeting-room
                    var baseUrl = "{{ url('/agenda') }}"; 
                    document.getElementById('formDelete').action = baseUrl + "/" + event.id;

                    var actionButtons = document.getElementById('actionButtons');
                    var notOwnerAlert = document.getElementById('notOwnerAlert');

                    if (props.can_edit) {
                        actionButtons.style.display = 'flex';
                        notOwnerAlert.style.display = 'none';

                        // SETUP TOMBOL EDIT
                        document.getElementById('btnOpenEdit').onclick = function() {
                            var detailModal = bootstrap.Modal.getInstance(document.getElementById('detailModal'));
                            detailModal.hide();
                            
                            // ACTION URL KE ROUTE UPDATE MEETING ROOM
                            document.getElementById('formEditBooking').action = "{{ url('/meeting-room') }}/" + event.id;
                            
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
                            }
                            
                            new bootstrap.Modal(document.getElementById('editModal')).show();
                        };

                    } else {
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
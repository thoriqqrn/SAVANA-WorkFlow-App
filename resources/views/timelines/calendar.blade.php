@extends('layouts.app')

@section('title', 'Kalender')
@section('page-title', 'Kalender Timeline')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
.fc {
    --fc-border-color: var(--border-color);
    --fc-page-bg-color: transparent;
    --fc-neutral-bg-color: var(--gray-50);
}
.fc .fc-toolbar-title {
    font-size: 1.2rem;
    font-weight: 600;
}
.fc .fc-button {
    background: var(--primary);
    border: none;
    padding: 6px 12px;
    font-size: 0.85rem;
}
.fc .fc-button:hover {
    background: var(--primary-dark);
}
.fc .fc-button-active {
    background: var(--primary-dark) !important;
}
.fc-event {
    cursor: pointer;
    border: none;
    padding: 2px 6px;
    font-size: 0.8rem;
}
.fc-daygrid-day-number {
    color: var(--text-primary);
    padding: 8px;
}
.fc-col-header-cell-cushion {
    color: var(--text-secondary);
    font-weight: 600;
}
.fc-daygrid-day.fc-day-today {
    background: rgba(124, 58, 237, 0.1) !important;
}

.calendar-legend {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}
.calendar-legend .legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
}
.calendar-legend .legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

/* Event Modal */
.event-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1050;
}
.event-modal.show {
    display: flex;
}
.event-modal-content {
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 24px;
    max-width: 400px;
    width: 90%;
    animation: fadeIn 0.2s ease;
}
</style>
@endpush

@section('content')
<div class="card animate-fadeIn">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar-alt text-primary"></i>
            Kalender
        </h3>
        <div class="d-flex gap-2">
            <a href="{{ route('timelines.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-list"></i> List View
            </a>
            @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
            <a href="{{ route('timelines.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah
            </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <!-- Legend -->
        <div class="calendar-legend">
            <div class="legend-item">
                <span class="legend-dot" style="background: #7C3AED;"></span>
                <span>Timeline Global</span>
            </div>
            <div class="legend-item">
                <span class="legend-dot" style="background: #3B82F6;"></span>
                <span>Timeline Departemen</span>
            </div>
            <div class="legend-item">
                <span class="legend-dot" style="background: #10B981;"></span>
                <span>Program Kerja</span>
            </div>
            <div class="legend-item">
                <span class="legend-dot" style="background: #F59E0B;"></span>
                <span>Deadline Task</span>
            </div>
        </div>
        
        <div id="calendar"></div>
    </div>
</div>

<!-- Event Detail Modal -->
<div id="eventModal" class="event-modal">
    <div class="event-modal-content">
        <div class="d-flex justify-between align-center mb-3">
            <h5 id="eventTitle" class="mb-0"></h5>
            <button type="button" class="btn btn-sm btn-icon btn-secondary" onclick="closeEventModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="eventBody"></div>
        <div id="eventFooter" class="mt-3"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        locale: 'id',
        buttonText: {
            today: 'Hari Ini',
            month: 'Bulan',
            week: 'Minggu',
            list: 'Daftar'
        },
        events: '{{ route("timelines.calendar.data") }}',
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            
            const event = info.event;
            const props = event.extendedProps;
            
            document.getElementById('eventTitle').textContent = event.title.replace(/^[📋✅] /, '');
            
            let body = '';
            if (props.description) {
                body += `<p class="text-muted">${props.description}</p>`;
            }
            
            body += '<div class="d-flex flex-column gap-2">';
            body += `<div><strong>Tanggal:</strong> ${formatDate(event.start)} - ${formatDate(event.end)}</div>`;
            
            if (props.type === 'timeline') {
                body += `<div><strong>Tipe:</strong> <span class="badge badge-primary">${props.timeline_type}</span></div>`;
                if (props.department) body += `<div><strong>Departemen:</strong> ${props.department}</div>`;
                if (props.program) body += `<div><strong>Program:</strong> ${props.program}</div>`;
            } else if (props.type === 'program') {
                body += `<div><strong>Departemen:</strong> ${props.department}</div>`;
                body += `<div><strong>Status:</strong> ${props.status}</div>`;
            } else if (props.type === 'task') {
                body += `<div><strong>Status:</strong> ${props.status}</div>`;
                body += `<div><strong>Prioritas:</strong> ${props.priority}</div>`;
                body += `<div><strong>Progress:</strong> ${props.progress}%</div>`;
            }
            body += '</div>';
            
            document.getElementById('eventBody').innerHTML = body;
            
            let footer = '';
            if (event.url) {
                footer = `<a href="${event.url}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Lihat Detail</a>`;
            }
            document.getElementById('eventFooter').innerHTML = footer;
            
            document.getElementById('eventModal').classList.add('show');
        }
    });
    
    calendar.render();
});

function formatDate(date) {
    if (!date) return '-';
    const d = new Date(date);
    d.setDate(d.getDate() - 1); // Adjust for FullCalendar exclusive end
    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

function closeEventModal() {
    document.getElementById('eventModal').classList.remove('show');
}

// Close modal on outside click
document.getElementById('eventModal').addEventListener('click', function(e) {
    if (e.target === this) closeEventModal();
});
</script>
@endpush

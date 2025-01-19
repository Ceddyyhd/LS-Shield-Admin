@extends('layouts.vertical', ['title' => 'Dashboard', 'subTitle' => 'Dashboard'])

@section('content')
<div class="container-fluid">
    <!-- Announcements Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Ankündigungen</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                        <i class="bx bx-plus me-1"></i> Neue Ankündigung
                    </button>
                </div>
                <div class="card-body-ankuendigung">
                    <!-- Announcements will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Attendance Table -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Anwesenheit</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Anwesend Seit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendanceData as $attendance)
                                <tr>
                                    <td>{{ $attendance->user->name }}</td>
                                    <td>{{ $attendance->timestamp->format('d.m.Y H:i') }} Uhr</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">Keine Anwesenheit erfasst.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Rabatte Table -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rabatte</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Firma</th>
                                <th>Beschreibung</th>
                                <th>Rabatt in %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rabatte as $rabatt)
                                <tr>
                                    <td>{{ $rabatt->display_name }}</td>
                                    <td>{{ $rabatt->description }}</td>
                                    <td>{{ $rabatt->rabatt_percent }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">Keine Rabatte verfügbar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Announcement Modal -->
<div class="modal fade" id="createAnnouncementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.announcements.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Neue Ankündigung</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Titel</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Beschreibung</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priorität</label>
                        <select class="form-select" name="priority" required>
                            <option value="low">Niedrig</option>
                            <option value="mid">Mittel</option>
                            <option value="high">Hoch</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $.ajax({
        url: '{{ route("admin.dashboard.announcements") }}',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data && data.length > 0) {
                const cardBody = $('.card-body-ankuendigung');
                cardBody.empty();

                data.forEach(function(announcement) {
                    let alertClass = '';
                    switch (announcement.priority) {
                        case 'high': alertClass = 'alert-danger'; break;
                        case 'mid': alertClass = 'alert-warning'; break;
                        case 'low': alertClass = 'alert-success'; break;
                        default: alertClass = 'alert-info';
                    }

                    cardBody.append(`
                        <div class="alert ${alertClass} mb-3" id="announcement-${announcement.id}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="alert-heading">${announcement.title}</h5>
                                    <p class="mb-0">${announcement.description}</p>
                                    <small class="text-muted">
                                        Von: ${announcement.created_by} - 
                                        ${new Date(announcement.created_at).toLocaleString('de-DE')}
                                    </small>
                                </div>
                                <button type="button" class="btn-close delete-announcement" 
                                        data-id="${announcement.id}" 
                                        aria-label="Delete"></button>
                            </div>
                        </div>
                    `);
                });

                // Delete handler
                $('.delete-announcement').click(function() {
                    const id = $(this).data('id');
                    if (confirm('Möchten Sie diese Ankündigung wirklich löschen?')) {
                        $.ajax({
                            url: `/admin/announcements/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                $(`#announcement-${id}`).fadeOut();
                            }
                        });
                    }
                });
            }
        }
    });
});
</script>
@endsection
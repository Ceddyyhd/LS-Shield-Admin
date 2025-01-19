@extends('layouts.vertical', ['title' => 'Verwaltung', 'subTitle' => 'Urlaubsverwaltung'])

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar -->
        <div class="col-md-3">
            <div class="sticky-top mb-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Urlaub einreichen</h3>
                    </div>
                    <div class="card-body">
                        <form id="vacationForm" action="{{ route('admin.vacations.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Mitarbeiter</label>
                                <select class="form-select" name="user_id" required>
                                    <option value="">Wählen Sie einen Mitarbeiter</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Start Datum</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">End Datum</label>
                                <input type="date" class="form-control" name="end_date" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="pending">Ausstehend</option>
                                    <option value="approved">Genehmigt</option>
                                    <option value="rejected">Abgelehnt</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notiz</label>
                                <textarea class="form-control" name="note" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Urlaub erstellen</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Pending Vacations -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Urlaubsanträge (Ausstehend)</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mitarbeiter</th>
                                    <th>Start Datum</th>
                                    <th>End Datum</th>
                                    <th>Status</th>
                                    <th>Notiz</th>
                                    <th>Aktion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingVacations as $vacation)
                                <tr>
                                    <td>{{ $vacation->id }}</td>
                                    <td>{{ $vacation->user->name }}</td>
                                    <td>{{ $vacation->start_date->format('d.m.Y') }}</td>
                                    <td>{{ $vacation->end_date->format('d.m.Y') }}</td>
                                    <td><span class="badge bg-warning">Ausstehend</span></td>
                                    <td>{{ $vacation->note }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editVacationModal"
                                                data-vacation-id="{{ $vacation->id }}">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Approved Vacations -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Genehmigte Urlaubsanträge</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mitarbeiter</th>
                                    <th>Start Datum</th>
                                    <th>End Datum</th>
                                    <th>Status</th>
                                    <th>Notiz</th>
                                    <th>Aktion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($approvedVacations as $vacation)
                                <tr>
                                    <td>{{ $vacation->id }}</td>
                                    <td>{{ $vacation->user->name }}</td>
                                    <td>{{ $vacation->start_date->format('d.m.Y') }}</td>
                                    <td>{{ $vacation->end_date->format('d.m.Y') }}</td>
                                    <td><span class="badge bg-success">Genehmigt</span></td>
                                    <td>{{ $vacation->note }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editVacationModal"
                                                data-vacation-id="{{ $vacation->id }}">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Vacation Modal -->
<div class="modal fade" id="editVacationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editVacationForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-vacation-id" name="id">
                <div class="modal-header">
                    <h5 class="modal-title">Urlaub bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Von</label>
                        <input type="date" class="form-control" id="edit-start-date" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bis</label>
                        <input type="date" class="form-control" id="edit-end-date" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="edit-status" name="status" required>
                            <option value="pending">Ausstehend</option>
                            <option value="approved">Genehmigt</option>
                            <option value="rejected">Abgelehnt</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notiz</label>
                        <textarea class="form-control" id="edit-note" name="note" rows="2"></textarea>
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
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Edit modal handler
    $('#editVacationModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var vacationId = button.data('vacation-id');
        var form = $('#editVacationForm');
        
        form.attr('action', `/admin/verwaltung/vacations/${vacationId}`);
        
        $.get(`/admin/verwaltung/vacations/${vacationId}/edit`, function(data) {
            $('#edit-vacation-id').val(data.id);
            $('#edit-start-date').val(data.start_date);
            $('#edit-end-date').val(data.end_date);
            $('#edit-status').val(data.status);
            $('#edit-note').val(data.reason);
        });
    });

    // Form submission handler
    $('#editVacationForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: 'POST',
            url: url,
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    var modal = bootstrap.Modal.getInstance(document.getElementById('editVacationModal'));
                    modal.hide();
                    window.location.reload();
                }
            },
            error: function(xhr) {
                alert('Fehler beim Aktualisieren des Urlaubs');
            }
        });
    });
});
</script>
@endsection
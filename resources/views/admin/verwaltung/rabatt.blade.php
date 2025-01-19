@extends('layouts.vertical', ['title' => 'Verwaltung', 'subTitle' => 'Deckel'])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRabattModal">
                        <i class="mdi mdi-plus me-1"></i> Neuer Rabatt
                    </button>
                </div>
                <div class="card-body">
                    <table id="rabatte-table" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Firma</th>
                                <th>Beschreibung</th>
                                <th>Rabatt in %</th>
                                <th>Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rabatte as $rabatt)
                            <tr>
                                <td>{{ $rabatt->id }}</td>
                                <td>{{ $rabatt->display_name }}</td>
                                <td>{{ $rabatt->description }}</td>
                                <td>{{ $rabatt->rabatt_percent }}%</td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                            data-bs-target="#editRabattModal" 
                                            data-rabatt-id="{{ $rabatt->id }}">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-rabatt" 
                                            data-rabatt-id="{{ $rabatt->id }}">
                                        <i class="bx bx-trash"></i>
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

<!-- Create Modal -->
<div class="modal fade" id="createRabattModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.rabatte.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Neuer Rabatt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Firma</label>
                        <input type="text" class="form-control" name="display_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Beschreibung</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rabatt in %</label>
                        <input type="number" class="form-control" name="rabatt_percent" min="0" max="100" required>
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

<!-- Edit Modal -->
<div class="modal fade" id="editRabattModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editRabattForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Rabatt bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Firma</label>
                        <input type="text" class="form-control" name="display_name" id="edit_display_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Beschreibung</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rabatt in %</label>
                        <input type="number" class="form-control" name="rabatt_percent" id="edit_rabatt_percent" min="0" max="100" required>
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
    // Edit Modal Handler
    $('#editRabattModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var rabattId = button.data('rabatt-id');
        var form = $('#editRabattForm');
        
        form.attr('action', `/admin/verwaltung/rabatte/${rabattId}`);
        
        $.ajax({
            url: `/admin/verwaltung/rabatte/${rabattId}/edit`,
            type: 'GET',
            success: function(data) {
                $('#edit_display_name').val(data.display_name);
                $('#edit_description').val(data.description);
                $('#edit_rabatt_percent').val(data.rabatt_percent);
            },
            error: function() {
                alert('Fehler beim Laden der Daten');
                var modal = bootstrap.Modal.getInstance(document.getElementById('editRabattModal'));
                modal.hide();
            }
        });
    });

    // Form Submit Handler
    $('#editRabattForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                var modal = bootstrap.Modal.getInstance(document.getElementById('editRabattModal'));
                modal.hide();
                location.reload();
            },
            error: function() {
                alert('Fehler beim Speichern der Änderungen');
            }
        });
    });
    $('.delete-rabatt').click(function() {
        var rabattId = $(this).data('rabatt-id');
        
        if (confirm('Möchten Sie diesen Rabatt wirklich löschen?')) {
            $.ajax({
                url: `/admin/verwaltung/rabatte/${rabattId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    location.reload();
                },
                error: function() {
                    alert('Fehler beim Löschen des Rabatts');
                }
            });
        }
    });
});
</script>
@endsection
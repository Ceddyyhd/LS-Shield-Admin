@extends('layouts.vertical', ['title' => 'Verwaltung', 'subTitle' => 'Ausbildungen'])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAusbildungModal">
                        <i class="mdi mdi-plus me-1"></i> Neue Ausbildung
                    </button>
                </div>
                <div class="card-body">
                    <table id="ausbildungen-table" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ausbildungen as $ausbildung)
                            <tr>
                                <td>{{ $ausbildung->id }}</td>
                                <td>{{ $ausbildung->name }}</td>
                                <td>
                                <a href="{{ route('admin.ausbildungen_akte.show', $ausbildung->id) }}" 
       class="btn btn-sm btn-primary">
        <i class="bx bx-folder"></i>
    </a>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                            data-bs-target="#editAusbildungModal" 
                                            data-id="{{ $ausbildung->id }}">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-ausbildung" 
                                            data-id="{{ $ausbildung->id }}">
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
<div class="modal fade" id="createAusbildungModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createAusbildungForm" method="POST" action="{{ route('admin.ausbildungen.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Neue Ausbildung</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
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
<div class="modal fade" id="editAusbildungModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editAusbildungForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Ausbildung bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
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

    // Create Form Handler
    $('#createAusbildungForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                $('#createAusbildungModal').modal('hide');
                window.location.href = "{{ route('admin.ausbildungen.index') }}";
            }
        },
        error: function() {
            alert('Fehler beim Speichern');
        }
    });
});

    // Edit Modal Handler
    $('#editAusbildungModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var form = $('#editAusbildungForm');
    
    form.attr('action', `/admin/ausbildungen/${id}`);
    
    // Debug logging
    console.log('Loading data for ID:', id);
    
    $.ajax({
        url: `/admin/ausbildungen/${id}/edit`,
        type: 'GET',
        success: function(data) {
            console.log('Received data:', data);
            $('#edit_name').val(data.name);
        },
        error: function(xhr, status, error) {
            console.error('Error loading data:', error);
            alert('Fehler beim Laden der Daten');
        }
    });
});

    // Edit Form Handler
    $('#editAusbildungForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#editAusbildungModal').modal('hide');
                location.reload();
            },
            error: function() {
                alert('Fehler beim Speichern');
            }
        });
    });

    // Delete Handler
    $('.delete-ausbildung').click(function() {
        var id = $(this).data('id');
        
        if (confirm('Möchten Sie diese Ausbildung wirklich löschen?')) {
            $.ajax({
                url: `/admin/ausbildungen/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    location.reload();
                },
                error: function() {
                    alert('Fehler beim Löschen');
                }
            });
        }
    });
});
</script>
@endsection
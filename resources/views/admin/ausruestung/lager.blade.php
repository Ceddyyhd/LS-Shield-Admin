@extends('layouts.vertical', ['title' => 'Lager', 'subTitle' => 'Ausrüstung'])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEquipmentModal">
                        <i class="mdi mdi-plus me-1"></i> Neue Ausrüstung
                    </button>
                </div>
                <div class="card-body">
                    <table id="equipment-table" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Key Name</th>
                                <th>Display Name</th>
                                <th>Kategorie</th>
                                <th>Beschreibung</th>
                                <th>Bestand</th>
                                <th>Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equipment as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->key_name }}</td>
                                <td>{{ $item->display_name }}</td>
                                <td>{{ $item->category }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->stock }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info edit-equipment" data-id="{{ $item->id }}">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-equipment" data-id="{{ $item->id }}">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary view-history" data-id="{{ $item->id }}">
                                        <i class="bx bx-history"></i>
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
<div class="modal fade" id="createEquipmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createEquipmentForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Neue Ausrüstung</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Key Name</label>
                        <input type="text" class="form-control" name="key_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Display Name</label>
                        <input type="text" class="form-control" name="display_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategorie</label>
                        <input type="text" class="form-control" name="category" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Beschreibung</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bestand</label>
                        <input type="number" class="form-control" name="stock" required min="0">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_consumable" id="is_consumable">
                            <label class="form-check-label" for="is_consumable">Verbrauchsmaterial</label>
                        </div>
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
<div class="modal fade" id="editEquipmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editEquipmentForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Ausrüstung bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Key Name</label>
                        <input type="text" class="form-control" name="key_name" id="edit_key_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Display Name</label>
                        <input type="text" class="form-control" name="display_name" id="edit_display_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategorie</label>
                        <input type="text" class="form-control" name="category" id="edit_category" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Beschreibung</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bestand</label>
                        <input type="number" class="form-control" name="stock" id="edit_stock" required min="0">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_consumable" id="edit_is_consumable">
                            <label class="form-check-label" for="edit_is_consumable">Verbrauchsmaterial</label>
                        </div>
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

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verlauf</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Benutzer</th>
                            <th>Aktion</th>
                            <th>Menge</th>
                            <th>Geändert von</th>
                        </tr>
                    </thead>
                    <tbody id="history-content"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // Edit button click handler
    $('.edit-equipment').on('click', function() {
        var id = $(this).data('id');
        $.ajax({
            url: `/admin/equipment/${id}/edit`,
            type: 'GET',
            success: function(data) {
                $('#edit_key_name').val(data.key_name);
                $('#edit_display_name').val(data.display_name);
                $('#edit_category').val(data.category);
                $('#edit_description').val(data.description);
                $('#edit_stock').val(data.stock);
                $('#edit_is_consumable').prop('checked', data.is_consumable);
                $('#editEquipmentForm').attr('action', `/admin/equipment/${id}`);
                var editModal = new bootstrap.Modal(document.getElementById('editEquipmentModal'));
                editModal.show();
            },
            error: function(xhr) {
                alert('Fehler beim Laden der Daten');
            }
        });
    });

    $('#createEquipmentForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.set('is_consumable', $('#is_consumable').is(':checked') ? '1' : '0');
        
        $.ajax({
            url: '{{ route("admin.equipment.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                var createModal = bootstrap.Modal.getInstance(document.getElementById('createEquipmentModal'));
                createModal.hide();
                location.reload();
            },
            error: function(xhr) {
                alert('Fehler beim Speichern: ' + xhr.responseText);
            }
        });
    });

    $('.view-history').on('click', function() {
        var id = $(this).data('id');
        console.log('Loading history for ID:', id); // Debug log

        $.ajax({
            url: `/admin/equipment/${id}/history`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(data) {
                console.log('History data:', data); // Debug log
                var html = '';
                data.forEach(function(log) {
                    html += `
                        <tr>
                            <td>${new Date(log.created_at).toLocaleString('de-DE')}</td>
                            <td>${log.user?.name || 'Unbekannt'}</td>
                            <td>${log.action}</td>
                            <td>${log.quantity}</td>
                            <td>${log.changer?.name || 'Unbekannt'}</td>
                        </tr>
                    `;
                });
                $('#history-content').html(html);
                var historyModal = new bootstrap.Modal(document.getElementById('historyModal'));
                historyModal.show();
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr.responseText); // Debug log
                alert('Fehler beim Laden des Verlaufs: ' + error);
            }
        });
    });

    // Single form submission handler
    $('#editEquipmentForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.set('is_consumable', $('#edit_is_consumable').is(':checked') ? '1' : '0');
        formData.append('_method', 'PUT');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                var editModal = bootstrap.Modal.getInstance(document.getElementById('editEquipmentModal'));
                editModal.hide();
                location.reload();
            },
            error: function(xhr) {
                alert('Fehler beim Speichern: ' + xhr.responseText);
            }
        });
    });
});
</script>
@endsection
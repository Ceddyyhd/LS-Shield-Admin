@extends('layouts.vertical', ['title' => 'Verwaltung', 'subTitle' => 'Fahrzeugverwaltung'])

@section('css')
<link href="{{ asset('assets/libs/datatables/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Fahrzeugverwaltung</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                        <i class="mdi mdi-plus"></i> Neues Fahrzeug
                    </button>
                </div>

                <div class="card-body">
                    <table id="vehicles-table" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Modell</th>
                                <th>Kennzeichen</th>
                                <th>Standort</th>
                                <th>Nächste Inspektion</th>
                                <th>Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicles as $vehicle)
                            <tr>
                                <td>{{ $vehicle->id }}</td>
                                <td>{{ $vehicle->model }}</td>
                                <td>{{ $vehicle->license_plate }}</td>
                                <td>{{ $vehicle->location }}</td>
                                <td>
                                    <span class="badge bg-{{ $vehicle->next_inspection->isPast() ? 'danger' : 
                                        ($vehicle->next_inspection->diffInDays(now()) <= 14 ? 'warning' : 'success') }}">
                                        {{ $vehicle->next_inspection->format('d.m.Y') }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info me-1" data-bs-toggle="modal" 
                                            data-bs-target="#editVehicleModal" 
                                            data-vehicle-id="{{ $vehicle->id }}">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#fuelModal"
                                            data-vehicle-id="{{ $vehicle->id }}">
                                        <i class="bx bx-gas-pump"></i>
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

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.vehicles.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Neues Fahrzeug</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Modell</label>
                        <input type="text" class="form-control" name="model" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kennzeichen</label>
                        <input type="text" class="form-control" name="license_plate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Standort</label>
                        <input type="text" class="form-control" name="location" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nächste Inspektion</label>
                        <input type="date" class="form-control" name="next_inspection" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notizen</label>
                        <textarea class="form-control" name="notes"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="turbo_tuning" value="1">
                            <label class="form-check-label">Turbo Tuning</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="engine_tuning" value="1">
                            <label class="form-check-label">Motor Tuning</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="transmission_tuning" value="1">
                            <label class="form-check-label">Getriebe Tuning</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="brake_tuning" value="1">
                            <label class="form-check-label">Brems Tuning</label>
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

<!-- Edit Vehicle Modal -->
<div class="modal fade" id="editVehicleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editVehicleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Fahrzeug bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Modell</label>
                        <input type="text" class="form-control" name="model" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kennzeichen</label>
                        <input type="text" class="form-control" name="license_plate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Standort</label>
                        <input type="text" class="form-control" name="location" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nächste Inspektion</label>
                        <input type="date" class="form-control" name="next_inspection" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notizen</label>
                        <textarea class="form-control" name="notes"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="turbo_tuning" value="1">
                            <label class="form-check-label">Turbo Tuning</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="engine_tuning" value="1">
                            <label class="form-check-label">Motor Tuning</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="transmission_tuning" value="1">
                            <label class="form-check-label">Getriebe Tuning</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="brake_tuning" value="1">
                            <label class="form-check-label">Brems Tuning</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="decommissioned" value="1">
                            <label class="form-check-label">Außer Dienst</label>
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

<!-- Fuel Modal -->
<div class="modal fade" id="fuelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="fuelForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tankung eintragen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kennzeichen</label>
                        <input type="text" class="form-control" name="license_plate" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Preis</label>
                        <input type="number" step="0.01" class="form-control" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tankstelle/Ort</label>
                        <input type="text" class="form-control" name="fuel_location" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_deckel" value="1">
                            <label class="form-check-label">Auf Deckel</label>
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
@endsection

@section('script')
<script src="{{ asset('assets/libs/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/dataTables.bootstrap5.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Debug data loading
    console.log('Table data:', {!! json_encode($vehicles) !!});

    // Handle edit modal
    $('#editVehicleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var vehicleId = button.data('vehicle-id');
        var form = $('#editVehicleForm');
        
        // Debug AJAX request
        console.log('Fetching vehicle:', vehicleId);
        
        $.ajax({
            url: `/admin/verwaltung/vehicles/${vehicleId}/edit`,
            method: 'GET',
            success: function(data) {
                console.log('Received vehicle data:', data);
                
                // Update form action
                form.attr('action', `/admin/verwaltung/vehicles/${vehicleId}`);
                
                // Populate form fields
                $('#editVehicleForm input[name="model"]').val(data.model);
                $('#editVehicleForm input[name="license_plate"]').val(data.license_plate);
                $('#editVehicleForm input[name="location"]').val(data.location);
                $('#editVehicleForm input[name="next_inspection"]').val(data.next_inspection);
                $('#editVehicleForm textarea[name="notes"]').val(data.notes);
                
                // Set checkboxes
                $('#editVehicleForm input[name="turbo_tuning"]').prop('checked', data.turbo_tuning);
                $('#editVehicleForm input[name="engine_tuning"]').prop('checked', data.engine_tuning);
                $('#editVehicleForm input[name="transmission_tuning"]').prop('checked', data.transmission_tuning);
                $('#editVehicleForm input[name="brake_tuning"]').prop('checked', data.brake_tuning);
                $('#editVehicleForm input[name="decommissioned"]').prop('checked', data.decommissioned);
            },
            error: function(xhr) {
                console.error('Error loading vehicle:', xhr);
                alert('Fehler beim Laden der Fahrzeugdaten');
            }
        });
    });
    $('#fuelModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var vehicleId = button.data('vehicle-id');
    var form = $('#fuelForm');
    form.attr('action', `/admin/verwaltung/vehicles/${vehicleId}/fuel`);
    
    // Load vehicle data for license plate
    $.get(`/admin/verwaltung/vehicles/${vehicleId}/edit`, function(data) {
        form.find('input[name="license_plate"]').val(data.license_plate);
    });
});
});
</script>
@endsection
@extends('layouts.vertical', ['title' => 'Employee Profile', 'subTitle' => 'Profile'])

@section('content')
<style>
    .rater {
        display: inline-block;
        vertical-align: middle;
    }
    .form-check-label {
        display: inline-block;
        vertical-align: middle;
        margin-left: 10px;
    }
    .timeline {
        list-style: none;
        padding: 0;
    }
    .timeline-item {
        margin-bottom: 20px;
        position: relative;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -20px;
        width: 10px;
        height: 10px;
        background-color: #007bff;
        border-radius: 50%;
    }
    .timeline-item .timeline-content {
        margin-left: 20px;
        padding: 10px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
    }
    .timeline-item .timeline-content .timeline-header {
        font-weight: bold;
    }
    .timeline-item .timeline-content .timeline-body {
        margin-top: 5px;
    }
    .timeline-item .timeline-content .timeline-footer {
        margin-top: 10px;
        font-size: 0.9em;
        color: #6c757d;
    }
    .profile-info {
        display: flex;
        align-items: center;
    }
    .profile-info img {
        border-radius: 50%;
        margin-right: 20px;
    }
    .profile-info h3 {
        margin: 0;
    }
    .profile-info .edit-rank {
        margin-left: 10px;
        cursor: pointer;
    }
</style>
<div class="row">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="profile-info">
                    <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('users/standard.png') }}" alt="User profile picture" width="100" height="100">
                    <div>
                        <h3 class="profile-username">{{ $user->name }}</h3>
                        <p class="text-muted">
                            {{ $user->role->name }}
                            @if(Auth::user()->hasPermission('edit_employee'))
                                <i class="fas fa-pen edit-rank" data-bs-toggle="modal" data-bs-target="#editRankModal"></i>
                            @endif
                        </p>
                    </div>
                </div>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Umail</b> <a class="float-right">{{ $user->umail }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Kontonummer</b> <a class="float-right">{{ $user->kontonummer }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Letzte Beförderung Durch</b> <a class="float-right">{{ $user->rank_last_changed_by }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Telefonnummer</b> <a class="float-right">{{ $user->nummer }}</a>
                    </li>
                </ul>
                @if(Auth::user()->hasPermission('edit_employee'))
                    <a href="#" class="btn btn-primary btn-block" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"><b>Edit Employee</b></a>
                @endif
            </div>
        </div>
    </div>



    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#documents" data-bs-toggle="tab">Documents</a></li>
                    <li class="nav-item"><a class="nav-link" href="#equipment" data-bs-toggle="tab">Equipment</a></li>
                    <li class="nav-item"><a class="nav-link" href="#notes" data-bs-toggle="tab">Notes</a></li>
                    <li class="nav-item"><a class="nav-link" href="#ausbildungen" data-bs-toggle="tab">Ausbildungen</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane" id="documents">
                        <!-- Documents content -->
                        <form action="{{ route('employee.update', $user->id) }}" method="POST">
                        @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <h5>Waffenschein</h5>
                                <select class="form-select" id="waffenschein" name="waffenschein">
                                    <option value="none" {{ $user->waffenschein == 'none' ? 'selected' : '' }}>Keiner Vorhanden</option>
                                    <option value="small" {{ $user->waffenschein == 'small' ? 'selected' : '' }}>Kleiner Waffenschein</option>
                                    <option value="large" {{ $user->waffenschein == 'large' ? 'selected' : '' }}>Großer & Kleiner Waffenschein</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <h5>Führerscheine</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="C" id="licenseC" name="licenses[]" {{ in_array('C', $user->licenses ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="licenseC">C</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="A" id="licenseA" name="licenses[]" {{ in_array('A', $user->licenses ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="licenseA">A</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="M2" id="licenseM2" name="licenses[]" {{ in_array('M2', $user->licenses ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="licenseM2">M2</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="PTL" id="licensePTL" name="licenses[]" {{ in_array('PTL', $user->licenses ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="licensePTL">PTL</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>

                        @if(Auth::user()->hasPermission('upload_file'))
                            <button type="button" class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">Upload Document</button>
                        @endif

                        <div class="mt-4">
                            <h5>Uploaded Documents:</h5>
                            @if(Auth::user()->hasPermission('view_documents'))
                                <ul>
                                    @forelse ($documents as $doc)
                                        <li>
                                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank">{{ $doc->file_name }}</a>
                                            ({{ $doc->uploaded_at }})
                                            @if(Auth::user()->hasPermission('delete_documents'))
                                                <form action="{{ route('documents.delete') }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="document_id" value="{{ $doc->id }}">
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            @endif
                                        </li>
                                    @empty
                                        <li>No documents available.</li>
                                    @endforelse
                                </ul>
                            @else
                                <p>You do not have permission to view the uploaded documents.</p>
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="equipment">
    <!-- Equipment content -->
    <form method="POST" action="{{ route('employee.update', $user->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <h5>Equipment</h5>
            @if($equipment)
                @php
                    $groupedEquipment = $equipment->groupBy('category');
                @endphp
                @foreach($groupedEquipment as $category => $items)
                    <h6>{{ $category }}</h6>
                    @foreach($items as $item)
                        <div class="form-check mb-3">
                            <input type="hidden" name="equipment[{{ $item->id }}][id]" value="{{ $item->id }}">
                            @if($item->is_consumable)
                                <!-- Consumable Equipment -->
                                <label class="form-check-label" for="equipment{{ $item->id }}">{{ $item->display_name }} (Stock: {{ $item->stock }})</label>
                                <input type="number" class="form-control" name="equipment[{{ $item->id }}][quantity]" value="" min="0">
                            @else
                                <!-- Non-Consumable Equipment -->
                                <input type="hidden" name="equipment[{{ $item->id }}][quantity]" value="1">
                                <input class="form-check-input" type="checkbox" value="1" id="equipment{{ $item->id }}" name="equipment[{{ $item->id }}][checked]" {{ $user->equipment->contains($item->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="equipment{{ $item->id }}">{{ $item->display_name }} (Stock: {{ $item->stock }})</label>
                            @endif
                        </div>
                    @endforeach
                @endforeach
            @else
                <p>No equipment available.</p>
            @endif
        </div>
        <button type="submit" class="btn btn-primary" id="saveEquipmentButton">Save</button>
    </form>
    <!-- Button to open equipment logs modal -->
    <button type="button" class="btn btn-secondary mt-3" data-bs-toggle="modal" data-bs-target="#equipmentLogsModal">View Equipment Logs</button>
</div>

<!-- Equipment Logs Modal -->
<div class="modal fade" id="equipmentLogsModal" tabindex="-1" aria-labelledby="equipmentLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="equipmentLogsModalLabel">Equipment Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($equipmentLogs->isNotEmpty())
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Equipment</th>
                                <th>Quantity</th>
                                <th>Action</th>
                                <th>Changed By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equipmentLogs as $log)
                                <tr>
                                    <td>{{ $log->created_at }}</td>
                                    <td>{{ $log->equipment->display_name }}</td>
                                    <td>{{ $log->quantity }}</td>
                                    <td>{{ ucfirst($log->action) }}</td>
                                    <td>{{ $log->changer->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No equipment logs available.</p>
                @endif
            </div>
        </div>
    </div>
</div>

                    <div class="tab-pane" id="notes">
                        <!-- Notes content -->
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addNoteModal">Add Note</button>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5 class="card-title mb-3">Notes Timeline</h5>
                                @foreach($notes->sortByDesc('created_at') as $note)
                                    <div class="d-flex flex-row fs-18 align-items-center mb-3">
                                        <h5 class="mb-0">{{ $note->created_at->diffForHumans() }}</h5>
                                    </div>
                                    <ul class="list-unstyled left-timeline">
                                        <li class="left-timeline-list">
                                            <div class="card d-inline-block">
                                                <div class="card-body">
                                                    <h5 class="mt-0 fs-16">
                                                        {{ $note->title }}
                                                        @if($note->type == 'Termination')
                                                            <span class="badge bg-danger ms-1 align-items-center">Termination</span>
                                                        @elseif($note->type == 'Note')
                                                            <span class="badge bg-info ms-1 align-items-center">Note</span>
                                                        @elseif($note->type == 'Warning')
                                                            <span class="badge bg-warning ms-1 align-items-center">Warning</span>
                                                        @endif
                                                    </h5>
                                                    <p class="text-muted mb-0">{{ $note->content }}</p>
                                                    <div class="timeline-footer mt-2">
                                                        Created by {{ $note->creator->name }} on {{ $note->created_at }}
                                                        <div class="mt-2">
                                                            <form action="{{ route('notes.delete', $note->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm mt-2">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="ausbildungen">
                        <!-- Ausbildungen content -->
                        <form method="POST" action="{{ route('employee.update', $user->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <h5>Ausbildungen</h5>
                                @foreach($ausbildungen as $ausbildung)
                                    <div class="form-check mb-3">
                                        <input type="hidden" name="ausbildungen[{{ $ausbildung->id }}][id]" value="{{ $ausbildung->id }}">
                                        <input type="hidden" name="ausbildungen[{{ $ausbildung->id }}][rating]" id="rating-{{ $ausbildung->id }}" value="{{ $user->ausbildungen->find($ausbildung->id)->pivot->rating ?? 0 }}">
                                        <input class="form-check-input" type="checkbox" value="{{ $ausbildung->id }}" id="ausbildung{{ $ausbildung->id }}" name="ausbildungen[{{ $ausbildung->id }}][checked]" {{ $user->ausbildungen->contains($ausbildung->id) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ausbildung{{ $ausbildung->id }}">{{ $ausbildung->name }}</label>
                                        <div id="rater-{{ $ausbildung->id }}" class="rater" data-rating="{{ $user->ausbildungen->find($ausbildung->id)->pivot->rating ?? 0 }}"></div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNoteModalLabel">Add Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('notes.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="note_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="note_title" name="note_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="note_type" class="form-label">Type</label>
                        <select class="form-control" id="note_type" name="note_type" required>
                            <option value="Note">Note</option>
                            <option value="Warning">Warning</option>
                            <option value="Termination">Termination</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="note_content" class="form-label">Content</label>
                        <textarea class="form-control" id="note_content" name="note_content" rows="3" required></textarea>
                    </div>
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <button type="submit" class="btn btn-primary">Add Note</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDocumentModalLabel">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="mb-3">
                        <label for="documentName" class="form-label">Document Name</label>
                        <input type="text" class="form-control" id="documentName" name="document_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="documentFile" class="form-label">Choose file</label>
                        <input type="file" class="form-control" id="documentFile" name="document" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="nummer" class="form-label">Nummer</label>
                        <input type="text" class="form-control" id="nummer" name="nummer" value="{{ $user->nummer }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="kontonummer" class="form-label">Kontonummer</label>
                        <input type="text" class="form-control" id="kontonummer" name="kontonummer" value="{{ $user->kontonummer }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="umail" class="form-label">Umail</label>
                        <input type="text" class="form-control" id="umail" name="umail" value="{{ $user->umail }}" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="gekuendigt" name="gekuendigt" value="1" {{ $user->gekuendigt == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="gekuendigt">Gekündigt</label>
                    </div>
                    @if($user->bewerber == 1)
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="bewerber" name="bewerber" value="1" {{ $user->bewerber == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="bewerber">Bewerber</label>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary">Update Employee</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editRankModal" tabindex="-1" aria-labelledby="editRankModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRankModalLabel">Edit Rank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('employee.updateRank', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role_id" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" id="saveRankButton">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@vite(['resources/js/components/extended-rating.js'])
@endsection

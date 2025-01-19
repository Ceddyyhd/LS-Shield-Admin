@extends('layouts.vertical', ['title' => 'Verbesserungsvorschläge', 'subTitle' => 'Übersicht'])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- Header Section -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Verbesserungsvorschläge</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSuggestionModal">
                        <i class="bx bx-plus me-1"></i> Neuer Vorschlag
                    </button>
                </div>
                
                <!-- Table Section -->
                <div class="card-body">
                    @if($suggestions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>Titel</th>
                                        <th>Bereich</th>
                                        <th>Status</th>
                                        <th>Bewertung</th>
                                        <th>Erstellt am</th>
                                        <th>Aktionen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suggestions as $suggestion)
                                    <tr>
                                        <td>{{ $suggestion->title }}</td>
                                        <td>{{ $suggestion->area }}</td>
                                        <td>
                                            <span class="badge bg-{{ $suggestion->status_color }}">
                                                {{ $suggestion->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button onclick="vote({{ $suggestion->id }}, true)" 
                                                        class="btn btn-sm {{ $suggestion->userVoted && $suggestion->userVote?->is_upvote ? 'btn-success' : 'btn-outline-success' }}">
                                                    <i class="bx bx-up-arrow-alt"></i> 
                                                    {{ $suggestion->upvotes_count ?? 0 }}
                                                </button>
                                                <button onclick="vote({{ $suggestion->id }}, false)" 
                                                        class="btn btn-sm {{ $suggestion->userVoted && !$suggestion->userVote?->is_upvote ? 'btn-danger' : 'btn-outline-danger' }}">
                                                    <i class="bx bx-down-arrow-alt"></i> 
                                                    {{ $suggestion->downvotes_count ?? 0 }}
                                                </button>
                                            </div>
                                        </td>
                                        <td>{{ $suggestion->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $suggestion->id }}">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                @if(Auth::id() === $suggestion->user_id)
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $suggestion->id }}">
                                                        <i class="bx bx-edit"></i>
                                                    </button>
                                                    <form action="{{ route('admin.suggestions.destroy', $suggestion) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Wirklich löschen?')">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Keine Vorschläge vorhanden.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createSuggestionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.suggestions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Neuer Verbesserungsvorschlag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Titel</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bereich</label>
                        <select class="form-select" name="area" required>
                            <option value="">Bitte wählen...</option>
                            <option value="IT">IT</option>
                            <option value="Prozesse">Prozesse</option>
                            <option value="Ausstattung">Ausstattung</option>
                            <option value="Sonstiges">Sonstiges</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Beschreibung</label>
                        <textarea class="form-control" name="description" rows="4" required></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="is_anonymous" id="is_anonymous">
                        <label class="form-check-label" for="is_anonymous">Anonym einreichen</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit & View Modals -->
@foreach($suggestions as $suggestion)
    <!-- View Modal -->
    <div class="modal fade" id="viewModal{{ $suggestion->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $suggestion->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Bereich:</strong> {{ $suggestion->area }}</p>
                    <p><strong>Status:</strong> {{ $suggestion->status }}</p>
                    <p><strong>Erstellt von:</strong> {{ $suggestion->is_anonymous ? 'Anonym' : $suggestion->user->name }}</p>
                    <p><strong>Beschreibung:</strong></p>
                    <p>{{ $suggestion->description }}</p>
                    @if($suggestion->notes)
                        <p><strong>Notizen:</strong></p>
                        <p>{{ $suggestion->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    @if(Auth::id() === $suggestion->user_id)
    <div class="modal fade" id="editModal{{ $suggestion->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.suggestions.update', $suggestion) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Vorschlag bearbeiten</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Titel</label>
                            <input type="text" class="form-control" name="title" value="{{ $suggestion->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bereich</label>
                            <select class="form-select" name="area" required>
                                <option value="IT" {{ $suggestion->area === 'IT' ? 'selected' : '' }}>IT</option>
                                <option value="Prozesse" {{ $suggestion->area === 'Prozesse' ? 'selected' : '' }}>Prozesse</option>
                                <option value="Ausstattung" {{ $suggestion->area === 'Ausstattung' ? 'selected' : '' }}>Ausstattung</option>
                                <option value="Sonstiges" {{ $suggestion->area === 'Sonstiges' ? 'selected' : '' }}>Sonstiges</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="eingegangen" {{ $suggestion->status === 'eingegangen' ? 'selected' : '' }}>Eingegangen</option>
                                <option value="in_bearbeitung" {{ $suggestion->status === 'in_bearbeitung' ? 'selected' : '' }}>In Bearbeitung</option>
                                <option value="rueckfragen" {{ $suggestion->status === 'rueckfragen' ? 'selected' : '' }}>Rückfragen</option>
                                <option value="abgeschlossen" {{ $suggestion->status === 'abgeschlossen' ? 'selected' : '' }}>Abgeschlossen</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Beschreibung</label>
                            <textarea class="form-control" name="description" rows="4" required>{{ $suggestion->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notizen</label>
                            <textarea class="form-control" name="notes" rows="3">{{ $suggestion->notes }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Abbrechen</button>
                        <button type="submit" class="btn btn-primary">Speichern</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

@endsection

@section('script')
<script>
function vote(suggestionId, isUpvote) {
    fetch(`/admin/suggestions/${suggestionId}/vote`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ is_upvote: isUpvote })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Fehler beim Abstimmen');
    });
}
</script>
@endsection
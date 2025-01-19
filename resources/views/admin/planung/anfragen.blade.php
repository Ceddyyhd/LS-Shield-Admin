@extends('layouts.vertical', ['title' => 'Planung', 'subTitle' => 'Anfragen'])

@section('content')
<div class="container">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Anfragen</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Anfragen</li>
            </ol>
        </div>
    </div>

    @if(Auth::user()->hasPermission('anfrage_create'))
    <div class="mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-anfrage-create">
            Anfrage erstellen
        </button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ansprechpartner</th>
                        <th>Anfrage</th>
                        <th>Status</th>
                        <th>Erstellt von</th>
                        <th>Erstellt Am</th>
                        <th>Details einblenden</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($anfragen as $anfrage)
                    <tr>
                        <td>{{ $anfrage->id }}</td>
                        <td>{{ $anfrage->vorname_nachname }}</td>
                        <td>{{ Str::limit($anfrage->anfrage, 50, '...') }}</td>
                        <td id="status-{{ $anfrage->id }}">{{ $anfrage->status }}</td>
                        <td>{{ $anfrage->erstellt_von }}</td>
                        <td>{{ $anfrage->datum_uhrzeit }}</td>
                        <td>
                            <button class="btn btn-link toggle-details" type="button" data-id="{{ $anfrage->id }}">
                                Details einblenden
                            </button>
                        </td>
                    </tr>
                    <tr id="details-{{ $anfrage->id }}" class="details-row" style="display: none;">
                        <td colspan="7">
                            <div class="p-3">
                                <div class="mb-3">
                                    <strong>Datum & Uhrzeit:</strong>
                                    <div>{{ $anfrage->datum_uhrzeit }}</div>
                                </div>
                                <div class="mb-3">
                                    <strong>Telefonnummer:</strong>
                                    <div>{{ $anfrage->telefonnummer }}</div>
                                </div>
                                <div class="mb-3">
                                    <strong>Anfrage:</strong>
                                    <div>{{ $anfrage->anfrage }}</div>
                                </div>
                                <div class="mb-3" id="buttons-{{ $anfrage->id }}">
                                    <form action="{{ route('anfragen.updateStatus') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $anfrage->id }}">
                                        @if($anfrage->status === 'Eingetroffen' && Auth::user()->hasPermission('change_to_in_bearbeitung'))
                                        <button class="btn btn-block btn-outline-warning" type="submit" name="status" value="in Bearbeitung">in Bearbeitung</button>
                                        @elseif($anfrage->status === 'in Bearbeitung' && Auth::user()->hasPermission('change_to_in_planung'))
                                        <button class="btn btn-block btn-outline-info btn-lg" type="submit" name="status" value="in Planung">in Planung</button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Anfrage erstellen Modal -->
<div class="modal fade" id="modal-anfrage-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Anfrage erstellen</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('anfragen.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
                        </div>
                        <div class="form-group">
                            <label for="nummer">Tel. Nr.</label>
                            <input type="text" class="form-control" id="nummer" name="nummer" placeholder="Enter nummer" required>
                        </div>
                        <div class="form-group">
                            <label for="anfrage">Anfrage</label>
                            <textarea name="anfrage" id="anfrage" class="form-control" rows="4" placeholder="Bitte teilen Sie uns Ihre Anfrage mit." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Schließen</button>
                        <button type="submit" class="btn btn-primary">Speichern</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-details').forEach(function(button) {
        button.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var detailsRow = document.getElementById('details-' + id);
            if (detailsRow.style.display === 'none') {
                detailsRow.style.display = 'table-row';
                this.textContent = 'Details ausblenden';
            } else {
                detailsRow.style.display = 'none';
                this.textContent = 'Details einblenden';
            }
        });
    });
});
</script>
@endsection
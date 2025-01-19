@extends('layouts.vertical', ['title' => 'Team Verwaltung', 'subTitle' => 'Trainings'])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Trainings</h3>
                    @if(Auth::user()->hasPermission('add_trainings'))
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#createTrainingModal">
                            Training erstellen
                        </button>
                    @endif
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Datum & Uhrzeit</th>
                                    <th>Grund</th>
                                    <th>Trainingsleitung</th>
                                    <th>Info</th>
                                    <th>Teilnehmer</th>
                                    <th>Aktionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trainings as $training)
                                <tr>
                                    <td>{{ $training->id }}</td>
                                    <td>{{ $training->datum_zeit->format('d.m.Y H:i') }}</td>
                                    <td>{{ $training->grund }}</td>
                                    <td>{{ $training->leitung }}</td>
                                    <td>{{ $training->info }}</td>
                                    <td>
                                        <button class="btn btn-link" data-bs-toggle="collapse" 
                                                data-bs-target="#participants-{{ $training->id }}">
                                            {{ $training->users->count() }} Teilnehmer
                                        </button>
                                        <div class="collapse" id="participants-{{ $training->id }}">
                                            <ul class="list-unstyled">
                                                @foreach($training->users as $user)
                                                    <li>{{ $user->name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                    @if($training->users->contains(auth()->id()))
                                        <form action="{{ route('training.unregister', $training->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Abmelden</button>
                                        </form>
                                    @else
                                        <form action="{{ route('training.register', $training->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">Anmelden</button>
                                        </form>
                                    @endif
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

<div class="modal fade" id="createTrainingModal" tabindex="-1" aria-labelledby="createTrainingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTrainingModalLabel">Training erstellen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('training.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="grund" class="form-label">Grund</label>
                        <input type="text" class="form-control" id="grund" name="grund" required>
                    </div>
                    <div class="mb-3">
                        <label for="info" class="form-label">Info</label>
                        <textarea class="form-control" id="info" name="info"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="leitung" class="form-label">Trainingsleitung</label>
                        <input type="text" class="form-control" id="leitung" name="leitung" required>
                    </div>
                    <div class="mb-3">
                        <label for="datum_zeit" class="form-label">Datum & Uhrzeit</label>
                        <input type="datetime-local" class="form-control" id="datum_zeit" name="datum_zeit" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    

    $('.register-btn').click(function() {
        const trainingId = $(this).data('training');
        $.ajax({
            url: `/admin/member/training/${trainingId}/register`,
            method: 'POST',
            data: {_token: '{{ csrf_token() }}'},
            success: function() {
                location.reload();
            }
        });
    });

    $('.unregister-btn').click(function() {
        const trainingId = $(this).data('training');
        $.ajax({
            url: `/admin/member/training/${trainingId}/unregister`,
            method: 'POST',
            data: {_token: '{{ csrf_token() }}'},
            success: function() {
                location.reload();
            }
        });
    });
});
</script>
@endpush
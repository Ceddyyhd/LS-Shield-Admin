@extends('layouts.vertical', ['title' => 'Team Verwaltung', 'subTitle' => 'Urlaub'])

@section('css')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-3">
            <!-- Vacation Request Form -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Urlaub beantragen</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('vacation.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="vacation_type_id" class="form-label">Art des Urlaubs</label>
                            <select class="form-control" id="vacation_type_id" name="vacation_type_id" required>
                                @foreach($vacationTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Von</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Bis</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Grund (Optional)</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Beantragen</button>
                    </form>
                </div>
            </div>

            <!-- My Vacations List -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Meine Anträge</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Datum</th>
                                    <th>Status</th>
                                    <th>Aktion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myVacations as $vacation)
                                <tr>
                                    <td>
                                        {{ $vacation->start_date->format('d.m.Y') }} -<br>
                                        {{ $vacation->end_date->format('d.m.Y') }}
                                    </td>
                                    <td>
                                        @switch($vacation->status)
                                            @case('approved')
                                                <span class="badge bg-success">Genehmigt</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Abgelehnt</span>
                                                @break
                                            @default
                                                <span class="badge bg-warning">Ausstehend</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($vacation->status === 'pending')
                                            <form action="{{ route('vacation.destroy', $vacation->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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

        <!-- Right Column - Calendar -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Urlaubsübersicht</h3>
                </div>
                <div class="card-body">
                    <div id="calendar" data-events="{{ json_encode($events) }}"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
@vite(['resources/js/pages/app-calendar.js'])
@endsection

@extends('layouts.vertical', ['title' => 'Finanzverwaltung', 'subTitle' => 'Gehälter'])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Mitarbeiter Finanzen</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newEntryModal">
                        Neuen Eintrag hinzufügen
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-striped">
                            <thead>
                                <tr>
                                    <th>Mitarbeiter</th>
                                    <th>Kontonummer</th>
                                    <th>Gehalt</th>
                                    <th>Anteil</th>
                                    <th>Trinkgeld</th>
                                    <th>Aktionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $employee)
                                <tr>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->kontonummer }}</td>
                                    <td>{{ number_format($employee->salary?->salary ?? 0, 2, ',', '.') }} €</td>
                                    <td>{{ number_format($employee->salary?->share ?? 0, 2, ',', '.') }} €</td>
                                    <td>{{ number_format($employee->salary?->tips ?? 0, 2, ',', '.') }} €</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#historyModal{{ $employee->id }}">
                                            <i class="bx bx-history"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#payoutModal{{ $employee->id }}">
                                            <i class="bx bx-money"></i>
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

<!-- Neuer Eintrag Modal -->
<div class="modal fade" id="newEntryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.finance.salaries.entry') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Neuen Eintrag hinzufügen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mitarbeiter</label>
                        <select class="form-select" name="employee_id" required>
                            <option value="">Bitte wählen</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Art</label>
                        <select class="form-select" name="type" required>
                            <option value="salary">Gehalt</option>
                            <option value="share">Anteil</option>
                            <option value="tips">Trinkgeld</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Betrag</label>
                        <input type="number" step="0.01" class="form-control" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notiz</label>
                        <input type="text" class="form-control" name="note">
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

@foreach($employees as $employee)
    <!-- Historie Modal -->
    <div class="modal fade" id="historyModal{{ $employee->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Historie - {{ $employee->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Art</th>
                                    <th>Betrag</th>
                                    <th>Notiz</th>
                                    <th>Erstellt am</th>
                                    <th>Erstellt von</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->salaryHistory ?? [] as $history)
                                <tr>
                                    <td>{{ $history->type }}</td>
                                    <td>{{ number_format($history->amount, 2, ',', '.') }} €</td>
                                    <td>{{ $history->note }}</td>
                                    <td>{{ $history->created_at->format('d.m.Y H:i') }}</td>
                                    <td>{{ $history->createdBy->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auszahlung Modal -->
    <div class="modal fade" id="payoutModal{{ $employee->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.finance.salaries.payout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Auszahlung - {{ $employee->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Gehalt</label>
                            <input type="number" step="0.01" class="form-control" name="salary" 
                                   value="{{ $employee->salary?->salary ?? 0 }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Anteil</label>
                            <input type="number" step="0.01" class="form-control" name="share" 
                                   value="{{ $employee->salary?->share ?? 0 }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Trinkgeld</label>
                            <input type="number" step="0.01" class="form-control" name="tips" 
                                   value="{{ $employee->salary?->tips ?? 0 }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                        <button type="submit" class="btn btn-primary">Auszahlen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection
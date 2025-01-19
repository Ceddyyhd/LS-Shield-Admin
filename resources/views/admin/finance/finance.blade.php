@extends('layouts.vertical', ['title' => 'Finanzverwaltung', 'subTitle' => 'Konto'])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Kontostand Box -->
                        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-0 fw-bold mb-2">
                        {{ number_format($balance, 2, ',', '.') }} €
                        </h3>
                        <p class="text-muted">
                            Kontostand
                        </p>
                    </div>
                    <div>
                        <div class="avatar-lg d-inline-block me-1">
                            <span class="avatar-title bg-info-subtle text-info rounded-circle">
                                <iconify-icon icon="iconamoon:credit-card-duotone" class="fs-32"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-0 fw-bold mb-2">
                        {{ number_format($income, 2, ',', '.') }} €
                        </h3>
                        <p class="text-muted">
                            Einnahmen
                        </p>
                        
                    </div>
                    <div>
                        <div class="avatar-lg d-inline-block me-1">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <iconify-icon icon="iconamoon:store-duotone" class="fs-32"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>

    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-0 fw-bold mb-2">
                        {{ number_format($expenses, 2, ',', '.') }} €
                        </h3>
                        <p class="text-muted">
                            Ausgaben
                        </p>
                    </div>
                    <div>
                        <div class="avatar-lg d-inline-block me-1">
                            <span class="avatar-title bg-success-subtle text-success rounded-circle">
                                <iconify-icon icon="iconamoon:3d-duotone" class="fs-32"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>

    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newEntryModal">
                                Neuer Kassen Eintrag
                            </button>
                    </div>
                    <div>
                        <div class="avatar-lg d-inline-block me-1">
                            <span class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                <iconify-icon icon="iconamoon:3d-duotone" class="fs-32"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
                    </div>

                    <!-- Finanztabelle -->
                    <div class="table-responsive mt-4">
                        <table class="table table-centered table-striped">
                            <thead>
                                <tr>
                                    <th>Typ</th>
                                    <th>Kategorie</th>
                                    <th>Notiz</th>
                                    <th>Erstellt von</th>
                                    <th>Betrag</th>
                                    <th>Datum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entries as $entry)
                                <tr>
                                    <td>{{ $entry->type }}</td>
                                    <td>{{ $entry->category }}</td>
                                    <td>{{ $entry->note }}</td>
                                    <td>{{ $entry->user->name }}</td>
                                    <td>{{ number_format($entry->amount, 2, ',', '.') }} €</td>
                                    <td>{{ $entry->created_at->format('d.m.Y H:i') }}</td>
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

<!-- Modal für neuen Eintrag -->
<div class="modal fade" id="newEntryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Neuer Kassen Eintrag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
            <form action="{{ route('admin.finance.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Typ</label>
        <select class="form-select" name="type" required>
            <option value="Einnahme">Einnahme</option>
            <option value="Ausgabe">Ausgabe</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Kategorie</label>
        <input type="text" class="form-control" name="category" required 
               placeholder="z.B. IT, Marketing, Personal">
    </div>

    <div class="mb-3">
        <label class="form-label">Notiz</label>
        <input type="text" class="form-control" name="note">
    </div>

    <div class="mb-3">
        <label class="form-label">Betrag</label>
        <input type="number" step="0.01" class="form-control" name="amount" required>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
        <button type="submit" class="btn btn-primary">Speichern</button>
    </div>
</form>
            </div>
        </div>
    </div>
</div>
@endsection

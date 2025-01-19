@extends('layouts.vertical', ['title' => 'Planung', 'subTitle' => 'Events'])

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Events</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Events</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 1%">#</th>
                            <th style="width: 15%">Ansprechpartner</th>
                            <th style="width: 20%">Event</th>
                            <th style="width: 20%">Anmerkung</th>
                            <th style="width: 15%">Datum & Uhrzeit</th>
                            <th style="width: 20%">Team Members</th>
                            <th>Status</th>
                            <th style="width: 20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr>
                            <td>{{ $event->id }}</td>
                            <td>
                                <a>{{ $event->vorname_nachname }}</a><br/>
                                <small>Created {{ \Carbon\Carbon::parse($event->datum_uhrzeit)->format('d.m.Y') }}</small>
                            </td>
                            <td><span>{{ $event->event }}</span></td>
                            <td><span>{{ $event->anmerkung }}</span></td>
                            <td><span>{{ $event->datum_uhrzeit }}</span></td>
                            <td>
                                <ul class="list-inline">
                                    @forelse($event->teamMembers as $member)
                                        <li class="list-inline-item" data-bs-toggle="tooltip" title="{{ $member->name }}">
                                            <img alt="Avatar" class="table-avatar" src="{{ $member->profile_image ? asset('storage/' . $member->profile_image) : asset('users/standard.png') }}" alt="User profile picture" width="40" height="40">
                                        </li>
                                    @empty
                                        <li>No team members available</li>
                                    @endforelse
                                </ul>
                            </td>
                            <td>
                                @switch($event->status)
                                    @case('in Planung')
                                        <span class="badge bg-warning">In Planung</span>
                                        @break
                                    @case('in Durchführung')
                                        <span class="badge bg-danger">In Durchführung</span>
                                        @break
                                    @case('Abgeschlossen')
                                        <span class="badge bg-success">Abgeschlossen</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" href="/admin/planung/eventplanung/{{ $event->id }}">
                                <i class="fas fa-folder"></i> View
                            </a>
                                
                                @can('eventplanung_kopieren')
                                <button class="btn btn-info btn-sm duplicate-event" data-id="{{ $event->id }}">
                                    <i class="fas fa-copy"></i> Kopieren
                                </button>
                                @endcan

                                @can('eventplanung_delete')
                                <button class="btn btn-danger btn-sm delete-event" data-id="{{ $event->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $('.duplicate-event').on('click', function() {
        const eventId = $(this).data('id');
        
        $.ajax({
            url: `/admin/planung/eventplanung/duplicate/${eventId}`, // Exact route path
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Event erfolgreich dupliziert');
                    location.reload();
                }
            },
            error: function() {
                toastr.error('Fehler beim Duplizieren des Events');
            }
        });
    });

    // Delete event handler
    $('.delete-event').on('click', function() {
        const eventId = $(this).data('id');
        
        Swal.fire({
            title: 'Sind Sie sicher?',
            text: "Möchten Sie dieses Event wirklich löschen?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ja, löschen',
            cancelButtonText: 'Abbrechen',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/planung/eventplanung/delete/${eventId}`, // Exact route path
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('button[data-id="'+eventId+'"]').closest('tr').fadeOut();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Es gab einen Fehler beim Löschen des Events.');
                    }
                });
            }
        });
    });
});
</script>
@endsection
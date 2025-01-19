@extends('layouts.vertical', ['title' => 'Planung', 'subTitle' => 'Event Akte'])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <!-- Event Info Card -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                <h4 class="profile-username text-center">Event</h4>
                <p class="text-muted text-center">{{ $event->event }}</p>
                <h4 class="profile-username text-center">Anmerkung</h4>
                <p class="text-muted text-center">{{ $event->anmerkung }}</p>
                <h4 class="profile-username text-center">Ansprechpartner</h4>
                <p class="text-muted text-center">{{ $event->vorname_nachname }}</p>
                <h4 class="profile-username text-center">Status</h4>
                    <p class="text-muted text-center">{{ $event->status }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Telefon</b> <a class="float-right">{{ $event->telefonnummer }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Datum</b> <a class="float-right">{{ \Carbon\Carbon::parse($event->datum_uhrzeit_event)->format('d.m.Y H:i') }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Ort</b> <a class="float-right">{{ $event->ort }}</a>
                        </li>
                        </ul>

@if($event->team_verteilung)
    <h4 class="profile-username text-center mt-4">Teams</h4>
    @foreach(json_decode($event->team_verteilung) as $team)
        <div class="team-info mb-3">
            <h5 class="text-primary">{{ $team->team_name }} - {{ $team->area_name }}</h5>
            <ul class="list-unstyled">
                @foreach($team->employee_names as $member)
                    <li>
                        {{ $member->name }}
                        @if($member->is_team_lead == "1")
                            <span class="badge bg-primary">Lead</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
@endif
</div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills" id="eventTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#details">Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#details-bearbeiten">Details Bearbeiten</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#team">Team</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#anmeldung">Anmeldung</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#dienstplan">Dienstplan</a>
                    </li>
                </ul>
            </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="details">
                            @if($event->summernote_content)
                            <div class="post">
                                <h5>Weitere Informationen</h5>
                                {!! $event->summernote_content !!}
                            </div>
                            @endif
                        </div>

                        <div class="tab-pane" id="details-bearbeiten">
    <form action="{{ route('eventplanung.updateContent', $event->id) }}" method="POST">
        @csrf
        <div id="snow-editor" style="height: 300px;">
            {!! $event->summernote_content ?? '' !!}
        </div>
        <input type="hidden" name="summernote_content" id="hiddenContent">
        <button type="submit" class="btn btn-primary mt-3" onclick="setContent()">Speichern</button>
    </form>
</div>

            <script>
                var quill = new Quill('#snow-editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: {
                            container: [[{ 'font': [] }, { 'size': [] }], ['bold', 'italic', 'underline', 'strike'], 
                                    [{ 'color': [] }, { 'background': [] }], [{ 'script': 'super' }, { 'script': 'sub' }], 
                                    [{ 'header': [false, 1, 2, 3, 4, 5, 6] }, 'blockquote', 'code-block'], 
                                    [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }], 
                                    ['direction', { 'align': [] }], ['link', 'image', 'video'], ['clean']],
                            handlers: {
                                image: imageHandler
                            }
                        }
                    }
                });

                function resizeImage(file) {
    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (e) => {
            const img = new Image();
            img.src = e.target.result;
            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;
                
                // Calculate aspect ratio to maintain proportions
                if (width > height) {
                    if (width > 500) {
                        height = Math.round((height * 500) / width);
                        width = 500;
                    }
                } else {
                    if (height > 500) {
                        width = Math.round((width * 500) / height);
                        height = 500;
                    }
                }
                
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                
                canvas.toBlob((blob) => {
                    resolve(new File([blob], file.name, {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    }));
                }, 'image/jpeg', 0.8);
            };
        };
    });
}

function imageHandler() {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = async () => {
        const file = input.files[0];
        const resizedFile = await resizeImage(file);
        const formData = new FormData();
        formData.append('image', resizedFile);

        try {
            const response = await fetch('{{ route("eventplanung.uploadImage") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            if (!response.ok) throw new Error('Upload failed');

            const data = await response.json();
            if (data.url) {
                const range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', data.url);
                quill.setSelection(range.index + 1);
            }
        } catch (error) {
            console.error('Upload error:', error);
            alert('Image upload failed');
        }
    };
}

                function setContent() {
                    document.getElementById('hiddenContent').value = quill.root.innerHTML;
                }
            </script>

                        

<div class="tab-pane" id="team">
    <div class="team-container">
        <!-- Team Template -->
        <div id="team-template" style="display:none">
            <div class="team-section mb-4">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control team-name" placeholder="Team Name">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control area-name" placeholder="Bereich">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="team-members">
                            <div class="member-row mb-2">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control member-name" placeholder="Team Lead" data-is-lead="1">
                                    </div>
                                    <div class="col-md-1">
                                        <span class="badge bg-primary">Lead</span>
                                    </div>
                                </div>
                            </div>
                            <div class="member-row mb-2">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control member-name" placeholder="Team Member">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="teams-container"></div>
        
        <button type="button" class="btn btn-success mt-3" id="add-team">Neues Team</button>
        <button type="button" class="btn btn-primary mt-3 ml-2" id="save-teams">Teams Speichern</button>
    </div>

    <script>
        $(document).ready(function() {
            let teamCount = 0;
            const existingTeams = @json($event->team_verteilung ? json_decode($event->team_verteilung) : []);

            function addTeam(teamData = null) {
                teamCount++;
                const template = $('#team-template').html();
                const $team = $(template);
                $team.find('.card-header').prepend(`<h5>Team ${teamCount}</h5>`);
                
                if (teamData) {
                    $team.find('.team-name').val(teamData.team_name);
                    $team.find('.area-name').val(teamData.area_name);
                    
                    // Clear default member rows
                    $team.find('.team-members').empty();
                    
                    // Add existing members
                    teamData.employee_names.forEach(employee => {
                        addMember($team.find('.team-members'), employee);
                    });
                }

                $('#teams-container').append($team);
                initializeTeamListeners($team);
            }

            function addMember($container, memberData = null) {
                const isLead = memberData?.is_team_lead === "1";
                const memberHtml = `
                    <div class="member-row mb-2">
                        <div class="row">
                            <div class="col-md-5">
                                <input type="text" class="form-control member-name" 
                                       placeholder="${isLead ? 'Team Lead' : 'Team Member'}" 
                                       value="${memberData?.name || ''}"
                                       data-is-lead="${isLead ? '1' : '0'}">
                            </div>
                            ${isLead ? '<div class="col-md-1"><span class="badge bg-primary">Lead</span></div>' : ''}
                        </div>
                    </div>
                `;
                $container.append(memberHtml);
            }

            function initializeTeamListeners($team) {
            // Monitor all member inputs for changes
            $team.find('.team-members').on('input', '.member-name', function() {
                const $memberRow = $(this).closest('.member-row');
                // Check if this is the last input and has content
                if ($memberRow.is(':last-child') && $(this).val().trim() !== '') {
                    // Add new member row
                    addMember($team.find('.team-members'));
                }
            });
        }

            $('#add-team').click(() => addTeam());

            $('#save-teams').click(function() {
    const teams = [];
    $('.team-section').each(function() {
        const $team = $(this);
        const team = {
            team_name: $team.find('.team-name').val(),
            area_name: $team.find('.area-name').val(),
            employee_names: []
        };

        $team.find('.member-row').each(function() {
            const $member = $(this).find('.member-name');
            const name = $member.val();
            if (name) {
                team.employee_names.push({
                    name: name,
                    is_team_lead: $member.data('is-lead') ? "1" : "0"
                });
            }
        });

        if (team.team_name && team.area_name && team.employee_names.length > 0) {
            teams.push(team);
        }
    });

    $.ajax({
    url: '{{ route("eventplanung.updateTeams", $event->id) }}',
    method: 'POST',
    data: {
        _token: '{{ csrf_token() }}',
        teams: JSON.stringify(teams)
    },
    success: function(response) {
        if (response.success) {
            toastr.success('Teams wurden gespeichert');
            // Increase delay to ensure toastr message is visible
            setTimeout(function() {
                window.location.href = window.location.href;
            }, 2000);
        }
    },
    error: function() {
        toastr.error('Fehler beim Speichern der Teams');
    }
});
});

            // Initialize existing teams if any
            if (existingTeams.length > 0) {
                existingTeams.forEach(team => addTeam(team));
            } else {
                addTeam();
            }
        });
    </script>
</div>



<div class="tab-pane" id="anmeldung">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Neue Anmeldung</h5>
                </div>
                <div class="card-body">
                <form action="{{ route('eventplanung.register', $event->id) }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="employee_id">Mitarbeiter</label>
                    <select class="form-control" id="employee_id" name="employee_id" required>
                        <option value="">Mitarbeiter auswählen</option>
                        @php
                            $registeredEmployeeIds = $event->registeredEmployees->pluck('employee_id')->toArray();
                            $availableEmployees = \App\Models\User::whereNotIn('id', $registeredEmployeeIds)->get();
                        @endphp
                        @foreach($availableEmployees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                        <div class="form-group mb-3">
                            <label for="notizen">Notizen</label>
                            <textarea class="form-control" id="notizen" name="notizen" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Anmelden</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Angemeldete Mitarbeiter</h5>
                </div>
                <div class="card-body">
    @if($event->registeredEmployees && $event->registeredEmployees->count() > 0)
        @foreach($event->registeredEmployees as $registration)
            <div class="registered-employee mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6>{{ $registration->employee ? $registration->employee->name : 'Unbekannter Mitarbeiter' }}</h6>
                        @if($registration->notizen)
                            <small class="text-muted">{{ $registration->notizen }}</small>
                        @endif
                    </div>
                    <button class="btn btn-sm btn-danger delete-registration" 
                            data-id="{{ $registration->id }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endforeach
    @else
        <p>Keine Mitarbeiter angemeldet</p>
    @endif
</div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#registrationForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route("eventplanung.register", $event->id) }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        employee_id: $('#employee_id').val(),
                        notizen: $('#notizen').val()
                    },
                    success: function(response) {
                        if(response.success) {
                            toastr.success('Anmeldung erfolgreich');
                            location.reload();
                        }
                    },
                    error: function() {
                        toastr.error('Fehler bei der Anmeldung');
                    }
                });
            });

            $('.delete-registration').click(function() {
                const id = $(this).data('id');
                if(confirm('Anmeldung wirklich löschen?')) {
                    $.ajax({
                        url: '{{ url("/admin/planung/eventplanung/unregister") }}/' + id,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if(response.success) {
                                toastr.success('Anmeldung gelöscht');
                                location.reload();
                            }
                        }
                    });
                }
            });
        });
    </script>
</div>



<div class="tab-pane" id="dienstplan">
    <div class="card">
        <div class="card-header">
        <h5>Dienstplan</h5>
    @php
        $totalHours = \App\Models\DienstplanZeiten::where('event_id', $event->id)
            ->sum('worked_hours');
    @endphp
    <a>Insgesamte Gearbeitete Zeit: <span id="total-hours">{{ number_format($totalHours, 2) }}</span> Stunden</a>
</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mitarbeiter</th>
                            <th>Startzeit</th>
                            <th>Endzeit</th>
                            <th>Gearbeitete Stunden</th>
                            <th>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
    @foreach($event->registeredEmployees as $registration)
        @php
            $dienstplan = \App\Models\DienstplanZeiten::where('event_id', $event->id)
                ->where('employee_id', $registration->employee_id)
                ->first();
        @endphp
        <tr>
            <td>{{ $registration->employee->name }}</td>
            <td>
                <input type="datetime-local" 
                       class="form-control start-time" 
                       data-employee="{{ $registration->employee_id }}"
                       value="{{ $dienstplan && $dienstplan->start_time ? \Carbon\Carbon::parse($dienstplan->start_time)->format('Y-m-d\TH:i') : '' }}">
            </td>
            <td>
                <input type="datetime-local" 
                       class="form-control end-time"
                       data-employee="{{ $registration->employee_id }}"
                       value="{{ $dienstplan && $dienstplan->end_time ? \Carbon\Carbon::parse($dienstplan->end_time)->format('Y-m-d\TH:i') : '' }}">
            </td>
            <td class="worked-hours">
                {{ $dienstplan ? number_format($dienstplan->worked_hours, 2) : '0.00' }}
            </td>
            <td>
                <button class="btn btn-primary btn-sm save-times" 
                        data-employee="{{ $registration->employee_id }}">
                    Speichern
                </button>
            </td>
        </tr>
    @endforeach
</tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('.save-times').click(function() {
            const employeeId = $(this).data('employee');
            const row = $(this).closest('tr');
            const startTime = row.find('.start-time').val();
            const endTime = row.find('.end-time').val();

            $.ajax({
                url: '{{ route("eventplanung.saveTimes", $event->id) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    employee_id: employeeId,
                    start_time: startTime,
                    end_time: endTime
                },
                success: function(response) {
                    if(response.success) {
                        row.find('.worked-hours').text(response.worked_hours);
                        // Update total hours
                        $.get('{{ route("eventplanung.getTotalHours", $event->id) }}', function(data) {
                            $('#total-hours').text(data.total_hours);
                        });
                        toastr.success('Zeiten wurden gespeichert');
                    }
                },
                error: function() {
                    toastr.error('Fehler beim Speichern der Zeiten');
                }
            });
        });
    });
</script>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
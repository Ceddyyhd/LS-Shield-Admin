@extends('layouts.vertical', ['title' => 'Team Management', 'subTitle' => 'Team List'])

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-3">Team</h1>
        </div>
        <div class="col-auto">
            @if(Auth::user()->hasPermission('add_users'))
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                    <i class="bx bx-plus me-1"></i>Add Member
                </button>
            @endif
        </div>
    </div>
    <div class="row mb-4">
        <div class="col">
            <a href="{{ route('users.index', ['filter' => 'active']) }}" class="btn btn-success">Mitarbeiter Anzeigen</a>
            <a href="{{ route('users.index', ['filter' => 'terminated']) }}" class="btn btn-warning">Gekündigte Mitarbeiter Anzeigen</a>
            <a href="{{ route('users.index', ['filter' => 'applicants']) }}" class="btn btn-info">Bewerber Anzeigen</a>
        </div>
    </div>
    <div class="table-responsive table-centered">
        <table class="table text-nowrap mb-0">
            <thead class="bg-light bg-opacity-50">
                <tr>
                    <th class="border-0 py-2">ID</th>
                    <th class="border-0 py-2">Email</th>
                    <th class="border-0 py-2">Umail</th>
                    <th class="border-0 py-2">Name</th>
                    <th class="border-0 py-2">Nummer</th>
                    <th class="border-0 py-2">Created At</th>
                    <th class="border-0 py-2">Role</th>
                    <th class="border-0 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @if(Auth::user()->hasPermission('view_users'))
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->umail }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->nummer }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>{{ $user->role ? $user->role->name : 'No Role' }}</td>
                            <td>
                                @if(Auth::user()->hasPermission('delete_users'))
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                @endif
                                @if(Auth::user()->hasPermission('view_users'))
                                    <a href="{{ route('employee.show', $user->id) }}" class="btn btn-info">Profile</a>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMemberModalLabel">Add New Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="umail" class="form-label">Umail</label>
                        <input type="email" class="form-control" id="umail" name="umail" required>
                    </div>
                    <div class="mb-3">
                        <label for="nummer" class="form-label">Nummer</label>
                        <input type="text" class="form-control" id="nummer" name="nummer" required>
                    </div>
                    <div class="mb-3">
                        <label for="kontonummer" class="form-label">Kontonummer</label>
                        <input type="text" class="form-control" id="kontonummer" name="kontonummer" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role_id" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profile Image</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image">
                    </div>
                    <input type="hidden" name="bewerber" value="1">
                    <button type="submit" class="btn btn-primary">Add Member</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
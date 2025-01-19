@extends('layouts.vertical', ['title' => 'Role Management', 'subTitle' => 'Role List'])

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between gap-3">
                    <div class="search-bar">
                        <span><i class="bx bx-search-alt"></i></span>
                        <input type="search" class="form-control" id="search" placeholder="Search role..." />
                    </div>
                    <div>
                        <a href="#!" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                            <i class="bx bx-plus me-1"></i>Add Role
                        </a>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <div>
                <div class="table-responsive table-centered">
                    <table class="table text-nowrap mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="border-0 py-2">ID</th>
                                <th class="border-0 py-2">Name</th>
                                <th class="border-0 py-2">Level</th>
                                <th class="border-0 py-2">Value</th>
                                <th class="border-0 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->level }}</td>
                                    <td>{{ $role->value }}</td>
                                    <td>
                                        <a href="#!" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->id }}">Edit</a>
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Role Modal -->
                                <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel{{ $role->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editRoleModalLabel{{ $role->id }}">Edit Role</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('roles.update', $role->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Name</label>
                                                        <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="level" class="form-label">Level</label>
                                                        <input type="text" class="form-control" id="level" name="level" value="{{ $role->level }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="permissions" class="form-label">Permissions</label>
                                                        <div class="accordion" id="permissionsAccordion">
                                                            @foreach($permissions as $bereich => $perms)
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingBereich{{ $bereich }}">
                                                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBereich{{ $bereich }}" aria-expanded="true" aria-controls="collapseBereich{{ $bereich }}">
                                                                            {{ $bereiche->firstWhere('id', $bereich)->display_name }}
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseBereich{{ $bereich }}" class="accordion-collapse collapse" aria-labelledby="headingBereich{{ $bereich }}" data-bs-parent="#permissionsAccordion">
                                                                        <div class="accordion-body">
                                                                            @foreach($perms as $permission)
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="permission{{ $permission->id }}" {{ in_array($permission->name, $role->permissions) ? 'checked' : '' }}>
                                                                                    <label class="form-check-label" for="permission{{ $permission->id }}">
                                                                                        {{ $permission->display_name }}
                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="value" class="form-label">Value</label>
                                                        <input type="number" class="form-control" id="value" name="value" value="{{ $role->value }}" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update Role</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoleModalLabel">Add New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="level" class="form-label">Level</label>
                        <input type="text" class="form-control" id="level" name="level" required>
                    </div>
                    <div class="mb-3">
                        <label for="permissions" class="form-label">Permissions</label>
                        <div class="accordion" id="permissionsAccordion">
                            @foreach($permissions as $bereich => $perms)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingBereich{{ $bereich }}">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBereich{{ $bereich }}" aria-expanded="true" aria-controls="collapseBereich{{ $bereich }}">
                                            {{ $bereiche->firstWhere('id', $bereich)->display_name }}
                                        </button>
                                    </h2>
                                    <div id="collapseBereich{{ $bereich }}" class="accordion-collapse collapse" aria-labelledby="headingBereich{{ $bereich }}" data-bs-parent="#permissionsAccordion">
                                        <div class="accordion-body">
                                            @foreach($perms as $permission)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="permission{{ $permission->id }}">
                                                    <label class="form-check-label" for="permission{{ $permission->id }}">
                                                        {{ $permission->display_name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <input type="number" class="form-control" id="value" name="value" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Role</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
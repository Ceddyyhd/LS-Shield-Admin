@extends('layouts.vertical', ['title' => 'Schedule', 'subTitle' => 'Calendar'])

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="d-grid">
                        </div>
                        <div id="external-events">
                            <br />
                            
                        </div>
                    </div>
                    <!-- end col-->

                    <div class="col-xl-9">
                        <div class="mt-4 mt-lg-0">
                            <div id="calendar"></div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end card body-->
        </div>
        <!-- end card -->

        <!-- Add New Event MODAL -->
        <div class="modal fade" id="event-modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="needs-validation" name="event-form" id="forms-event" novalidate>
                        <div class="modal-header p-3 border-bottom-0">
                            <h5 class="modal-title" id="modal-title">
                                Event
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-3 pb-3 pt-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="control-label form-label">Event
                                            Name</label>
                                        <input class="form-control" placeholder="Insert Event Name" type="text" name="title" id="event-title" required />
                                        <div class="invalid-feedback">
                                            Please provide a
                                            valid event name
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="control-label form-label">Category</label>
                                        <select class="form-select" name="category" id="event-category" required>
                                            <option value="bg-primary">
                                                Blue
                                            </option>
                                            <option value="bg-secondary">
                                                Gray Dark
                                            </option>
                                            <option value="bg-success">
                                                Green
                                            </option>
                                            <option value="bg-info">
                                                Cyan
                                            </option>
                                            <option value="bg-warning">
                                                Yellow
                                            </option>
                                            <option value="bg-danger">
                                                Red
                                            </option>
                                            <option value="bg-dark">
                                                Dark
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select a
                                            valid event
                                            category
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-danger" id="btn-delete-event">
                                        Delete
                                    </button>
                                </div>
                                <div class="col-6 text-end">
                                    <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="btn-save-event">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- end modal-content-->
            </div>
            <!-- end modal dialog-->
        </div>
        <!-- end modal-->
    </div>
    <!-- end col -->
</div>

@endsection

@section('script')
@vite(['resources/js/pages/app-calendar.js'])
@endsection
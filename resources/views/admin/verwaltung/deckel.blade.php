@extends('layouts.vertical', ['title' => 'Verwaltung', 'subTitle' => 'Deckel'])

@section('css')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Tank - Deckel</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="deckel-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>Betrag</th>
                                    <th>Aktionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($locations as $location)
                                <tr>
                                    <td>{{ $location->location ?: 'Unbekannt' }}</td>
                                    <td>{{ number_format($location->total_betrag, 2, ',', '.') }} €</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm delete-location" 
                                                data-location="{{ $location->location }}">
                                            <i class="bx bx-trash"></i>
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
@endsection

@section('script')
<script>
$(document).ready(function() {

    $('.delete-location').click(function() {
        const location = $(this).data('location');
        
        if (confirm(`Möchten Sie wirklich alle Einträge für die Location "${location}" löschen und in die Finanzen übertragen?`)) {
            $.ajax({
                url: '{{ route("admin.deckel.delete-location") }}',
                method: 'POST',
                data: { 
                    _token: '{{ csrf_token() }}',
                    location: location 
                },
                success: function(response) {
                    if (response.success) {
                        $(`.delete-location[data-location="${location}"]`).closest('tr').remove();
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Fehler: ' + response.message);
                    }
                }
            });
        }
    });
});
</script>
@endsection
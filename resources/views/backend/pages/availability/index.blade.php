@extends('backend.layouts.master')

@section('title', 'My Availability')

@section('page-title', 'My Availability')
@section('breadcrumb-home_route', route('availability.index'))
@section('breadcrumb-home_title', 'Availability List')
@section('breadcrumb-current', 'My Availability')

@section('admin-content')
    <div class="container mt-4">
        <h4 class="text-center mb-3">📋 My Availability List</h4>

        @if ($availabilities->isEmpty())
            <div class="alert alert-info text-center">You haven't set any availability yet.</div>
        @else
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="availabilityTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Preferred Time</th>
                                <th>Custom Time</th>
                                <th>Note</th>
                                @if (Auth::user()->can('availability.edit') || Auth::user()->hasRole('super admin') || Auth::user()->is_superadmin == 1)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($availabilities as $availability)
                                <tr>
                                    <td data-order="{{ \Carbon\Carbon::parse($availability->date)->format('Y-m-d') }}">
                                        {{ \Carbon\Carbon::parse($availability->date)->format('d M Y') }}
                                    </td>
                                    <td>
                                        {{ $availability->preferred_time ? ucfirst($availability->preferred_time) : 'N/A' }}
                                    </td>
                                    <td>
                                        @if ($availability->start_time && $availability->end_time)
                                            {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $availability->note ?? '-' }}</td>

                                    @if (Auth::user()->can('availability.edit') || Auth::user()->hasRole('super admin') || Auth::user()->is_superadmin == 1)
                                        <td class="form-inline text-nowrap">
                                            <form action="{{ route('availability.edit', $availability->id) }}" method="GET"
                                                class="d-inline">
                                                <button type="submit" class="btn btn-primary btn-sm mr-1">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('availability.destroy', $availability->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this availability?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Destroy any previous instance (global scripts also try to init #dataTable)
            if ($.fn.DataTable.isDataTable('#availabilityTable')) {
                $('#availabilityTable').DataTable().destroy();
            }

            $('#availabilityTable').DataTable({
                order: [], // ← keep the exact order coming from Laravel (month groups)
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No entries available",
                    zeroRecords: "No matching records found"
                },
                columnDefs: [{
                        orderable: false,
                        targets: -1
                    } // Action column not sortable
                ]
            });
        });
    </script>
@endpush

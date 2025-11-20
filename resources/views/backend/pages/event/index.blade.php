@extends('backend.layouts.master')
@section('title', 'Catering Events')

@section('admin-content')
    <div class="container my-4">
        <h3 class="mb-4 text-success fw-bold text-center">Catering Events</h3>

        <!-- Form -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">Add New Event(s)</div>
            <div class="card-body">
                <form id="eventForm">
                    @csrf
                    <input type="hidden" id="event_id" name="event_id">

                    <div id="eventInputs">
                        <div class="row mb-2 eventRow">
                            <div class="col-md-4">
                                <label>Date</label>
                                <input type="date" name="event_date[]" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Note</label>
                                <input type="text" name="note[]" class="form-control" placeholder="Event details"
                                    required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger removeRow w-100">Remove</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="addRow" class="btn btn-info btn-sm mb-3">+ Add More</button>
                    <button type="submit" class="btn btn-success w-100">Save All</button>
                </form>
            </div>
        </div>

        <!-- Search -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <input type="date" id="searchDate" class="form-control" placeholder="Search by date">
            </div>
        </div>

        <!-- Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Events List</div>
            <div class="card-body">
                <table class="table table-bordered text-center" id="eventsTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr id="row-{{ $event->id }}">
                                <td>{{ \Carbon\Carbon::parse($event->event_date)->format('d F Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($event->event_date)->format('l') }}</td>
                                <td>{{ $event->note }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm editBtn" data-id="{{ $event->id }}"
                                        data-date="{{ $event->event_date }}" data-note="{{ $event->note }}">Edit</button>
                                    <button class="btn btn-danger btn-sm deleteBtn"
                                        data-id="{{ $event->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // Add More Rows
            $('#addRow').on('click', function() {
                let row = `
        <div class="row mb-2 eventRow">
            <div class="col-md-4">
                <input type="date" name="event_date[]" class="form-control" required>
            </div>
            <div class="col-md-6">
                <input type="text" name="note[]" class="form-control" placeholder="Event details" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger removeRow w-100">Remove</button>
            </div>
        </div>`;
                $('#eventInputs').append(row);
            });

            // Remove Row
            $(document).on('click', '.removeRow', function() {
                $(this).closest('.eventRow').remove();
            });

            // Store or Update Multiple Events
            $('#eventForm').on('submit', function(e) {
                e.preventDefault();

                let id = $('#event_id').val();
                let url = id ? '/admin/events/' + id : '/admin/events';
                let method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: $(this).serialize() + (id ? '&_method=PUT' : ''),
                    success: function(res) {
                        if (res.success) {
                            alert(res.message);

                            if (id) {
                                // update row
                                let e = res.event;
                                let row = `
                <tr id="row-${e.id}">
                    <td>${e.event_date}</td>
                    <td>${e.day}</td>
                    <td>${e.note}</td>
                    <td>
                        <button class="btn btn-warning btn-sm editBtn" 
                            data-id="${e.id}" 
                            data-date="${e.event_date}" 
                            data-note="${e.note}">Edit</button>
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="${e.id}">Delete</button>
                    </td>
                </tr>`;
                                $('#row-' + e.id).replaceWith(row);
                            } else {
                                // for store, reload page or append dynamically
                                location.reload();
                            }

                            $('#eventForm')[0].reset();
                            $('#event_id').val('');
                            $('#eventInputs').html(`
                <div class="row mb-2 eventRow">
                    <div class="col-md-4">
                        <label>Date</label>
                        <input type="date" name="event_date[]" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Note</label>
                        <input type="text" name="note[]" class="form-control" placeholder="Event details" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger removeRow w-100">Remove</button>
                    </div>
                </div>
            `);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            alert(Object.values(xhr.responseJSON.errors).flat().join("\n"));
                        }
                    }
                });

            });

            // Edit
            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                let date = $(this).data('date');
                let note = $(this).data('note');

                $('#event_id').val(id);

                // reset old rows
                $('#eventInputs').html(`
            <div class="row mb-2 eventRow">
                <div class="col-md-4">
                    <label>Date</label>
                    <input type="date" name="event_date[]" class="form-control" value="${date}" required>
                </div>
                <div class="col-md-6">
                    <label>Note</label>
                    <input type="text" name="note[]" class="form-control" value="${note}" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger removeRow w-100">Remove</button>
                </div>
            </div>
        `);
            });

            // Delete
            $(document).on('click', '.deleteBtn', function() {
                if (confirm('Are you sure to delete?')) {
                    let id = $(this).data('id');
                    $.ajax({
                        url: '/admin/events/' + id,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            if (res.success) {
                                alert(res.message);
                                $('#row-' + id).remove();
                            }
                        }
                    });

                }
            });

            // Search by date
            $('#searchDate').on('change', function() {
                let val = $(this).val().toLowerCase();
                $("#eventsTable tbody tr").filter(function() {
                    $(this).toggle($(this).find("td:first").text().toLowerCase().indexOf(val) > -1)
                });
            });

        });
    </script>
@endpush

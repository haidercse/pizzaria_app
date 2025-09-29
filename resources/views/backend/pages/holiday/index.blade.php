@extends('backend.layouts.master')
@section('title', 'Holiday Management')

@section('admin-content')
    <div class="container mt-3">
        <h4 class="mb-3">Holiday Management</h4>

        {{-- Add Holiday Form --}}
        <form id="holidayForm" class="row g-2 mb-3">
            @csrf
            <input type="hidden" id="holiday_id">
            <div class="col-md-4">
                <input type="date" name="date" id="date" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="name" id="name" class="form-control form-control-sm"
                    placeholder="Holiday name" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                <button type="button" id="cancelEdit" class="btn btn-sm btn-secondary d-none">Cancel</button>
            </div>
        </form>

        {{-- Holiday Table --}}
        <table class="table table-bordered table-sm table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th style="width: 150px;">Date</th>
                    <th>Holiday</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody id="holidayTable">
                @foreach ($holidays as $holiday)
                    <tr id="row-{{ $holiday->id }}">
                        <td><span class="badge bg-info">{{ \Carbon\Carbon::parse($holiday->date)->format('d M, Y') }}</span>
                        </td>
                        <td><strong>{{ $holiday->name }}</strong></td>
                        <td>
                            <button class="btn btn-sm btn-warning editBtn" data-id="{{ $holiday->id }}"
                                data-date="{{ $holiday->date }}" data-name="{{ $holiday->name }}">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $holiday->id }}">
                                <i class="bi bi-trash"></i> Del
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Add / Update Holiday
            $("#holidayForm").submit(function(e) {
                e.preventDefault();
                let id = $("#holiday_id").val();
                let url = id ? "/holidays/" + id : "/holidays";
                let type = id ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    type: type,
                    data: {
                        _token: "{{ csrf_token() }}",
                        date: $("#date").val(),
                        name: $("#name").val()
                    },
                    success: function(res) {
                        if (res.status === "success") {
                            if (id) {
                                // Update row
                                $("#row-" + id).html(`
                            <td>${res.holiday.date}</td>
                            <td>${res.holiday.name}</td>
                            <td>
                                <button class="btn btn-sm btn-warning editBtn" data-id="${res.holiday.id}" data-date="${res.holiday.date}" data-name="${res.holiday.name}">Edit</button>
                                <button class="btn btn-sm btn-danger deleteBtn" data-id="${res.holiday.id}">Del</button>
                            </td>
                        `);
                            } else {
                                // Add new row
                                $("#holidayTable").append(`
                            <tr id="row-${res.holiday.id}">
                                <td>${res.holiday.date}</td>
                                <td>${res.holiday.name}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning editBtn" data-id="${res.holiday.id}" data-date="${res.holiday.date}" data-name="${res.holiday.name}">Edit</button>
                                    <button class="btn btn-sm btn-danger deleteBtn" data-id="${res.holiday.id}">Del</button>
                                </td>
                            </tr>
                        `);
                            }
                            $("#holidayForm")[0].reset();
                            $("#holiday_id").val("");
                            $("#cancelEdit").addClass("d-none");
                        }
                    }
                });
            });

            // Edit
            $(document).on("click", ".editBtn", function() {
                $("#holiday_id").val($(this).data("id"));
                $("#date").val($(this).data("date"));
                $("#name").val($(this).data("name"));
                $("#cancelEdit").removeClass("d-none");
            });

            // Cancel Edit
            $("#cancelEdit").click(function() {
                $("#holidayForm")[0].reset();
                $("#holiday_id").val("");
                $(this).addClass("d-none");
            });

            // Delete
            $(document).on("click", ".deleteBtn", function() {
                let id = $(this).data("id");
                if (!confirm("Are you sure?")) return;

                $.ajax({
                    url: "/holidays/" + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.status === "success") {
                            $("#row-" + id).remove();
                        }
                    }
                });
            });
        });
    </script>
@endpush

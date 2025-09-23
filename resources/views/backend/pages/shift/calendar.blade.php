@extends('backend.layouts.master')

@section('admin-content')
    <div class="container">
        <h3 class="mb-4">Shift Manager</h3>
        <div class="mb-3">
            <label for="monthFilter">Select Month:</label>
            <input type="month" id="monthFilter" class="form-control" value="{{ $selectedMonth }}">
        </div>
        @php
            $startOfMonth = \Carbon\Carbon::parse($selectedMonth . '-01');
            $daysInMonth = $startOfMonth->daysInMonth;
        @endphp

        <div class="mb-3 date-badges-container">
            @for ($i = 0; $i < $daysInMonth; $i++)
                @php
                    $date = $startOfMonth->copy()->addDays($i);
                    $isSelected = $date->toDateString() == $selectedDate;
                @endphp
                <span class="badge {{ $isSelected ? 'bg-warning text-dark' : 'bg-secondary' }} date-badge rounded-pill"
                    style="cursor: pointer; padding: 8px 12px; margin: 2px; transition: all 0.3s; font-size: 14px; background: {{ $isSelected ? '#ffeb3b' : '#6c757d' }}; color: {{ $isSelected ? '#212529' : '#fff' }}"
                    data-date="{{ $date->toDateString() }}" onclick="loadShifts('{{ $date->toDateString() }}')">
                    {{ $date->format('d') }} <br>
                    <small>{{ $date->format('D') }}</small>
                </span>
            @endfor
        </div>


        {{-- Search Bar --}}
        <input type="text" id="searchUser" class="form-control mb-3" placeholder="Search user...">

        {{-- Table --}}
        <table class="table table-bordered table-striped" id="shiftTable" data-date="{{ $selectedDate }}">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Preferred Time</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Employee Assigned Time</th>
                    <th>Hours</th>
                    <th>Total Hours</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $emp)
                    @php
                        $availability = $emp
                            ->availabilities()
                            ->where('date', $selectedDate) // je date select kora ache
                            ->first();
                    @endphp
                    <tr>
                        <td>{{ $emp->name }}</td>
                        <td>{{ $availability?->preferred_time ?? 'N/A' }}</td>
                        <td>
                            <select name="start_time[]" class="form-select start-time" data-id="{{ $emp->id }}">
                                @for ($h = 0; $h < 24; $h++)
                                    @for ($m = 0; $m < 60; $m += 30)
                                        @php
                                            $time = sprintf('%02d:%02d', $h, $m);
                                            $dbStartTime = substr($availability?->start_time, 0, 5);
                                        @endphp
                                        <option value="{{ $time }}" {{ $dbStartTime == $time ? 'selected' : '' }}>
                                            {{ $time }}
                                        </option>
                                    @endfor
                                @endfor
                            </select>
                        </td>
                        <td>
                            <select name="end_time[]" class="form-select end-time" data-id="{{ $emp->id }}">
                                @for ($h = 0; $h < 24; $h++)
                                    @for ($m = 0; $m < 60; $m += 30)
                                        @php
                                            $time = sprintf('%02d:%02d', $h, $m);
                                            $dbEndTime = substr($availability?->end_time, 0, 5);
                                        @endphp
                                        <option value="{{ $time }}" {{ $dbEndTime == $time ? 'selected' : '' }}>
                                            {{ $time }}
                                        </option>
                                    @endfor
                                @endfor
                            </select>
                        </td>
                        <td class="assigned-time">
                            @if ($availability)
                                {{ $availability->user_start_time }} - {{ $availability->user_end_time }}
                            @else
                                00:00 - N/A
                            @endif
                        </td>
                        <td class="hours">{{ $availability?->hours ?? 0 }}</td>
                        <td class="total-hours">{{ $emp->total_hours ?? 0 }}</td>

                        <td>
                            <button class="btn btn-sm btn-success save-shift" data-id="{{ $emp->id }}">Save</button>
                            <button class="btn btn-sm btn-info view-shift" data-id="{{ $emp->id }}"
                                data-date="{{ $selectedDate }}">View</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="shiftModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Shift Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="shiftModalBody">
                    <!-- Modal content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Load shifts for a specific date
            window.loadShifts = function(date) {
                $('.date-badge').removeClass('bg-warning text-dark').addClass('bg-secondary');
                $(`.date-badge[data-date="${date}"]`).removeClass('bg-secondary').addClass(
                    'bg-warning text-dark');

                $('#shiftTable').html(`
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="spinner-border text-primary"></div>
                                Loading shifts...
                            </td>
                        </tr>
                    </tbody>
                `);

                $.get("{{ url('shift-manager/ajax') }}/" + date, function(data) {
                    $('#shiftTable').replaceWith(data);
                    updateHours();
                }).fail(function(xhr, status, error) {
                    $('#shiftTable').html(`
                        <tbody>
                            <tr>
                                <td colspan="8" class="text-center text-danger">Error loading shifts</td>
                            </tr>
                        </tbody>
                    `);
                });
            };

            // Calculate hours for a row
            function updateHours() {
                $('#shiftTable tbody tr').each(function() {
                    let row = $(this);
                    let startTime = row.find('.start-time').val();
                    let endTime = row.find('.end-time').val();
                    let hoursCell = row.find('.hours');

                    if (startTime && endTime) {
                        let [sh, sm] = startTime.split(':').map(Number);
                        let [eh, em] = endTime.split(':').map(Number);

                        let startMinutes = sh * 60 + sm;
                        let endMinutes = eh * 60 + em;

                        let diffMinutes = endMinutes - startMinutes;
                        if (diffMinutes < 0) diffMinutes += 24 * 60; // Handle overnight shifts

                        let hours = (diffMinutes / 60).toFixed(2);
                        hoursCell.text(hours);
                    } else {
                        hoursCell.text('0');
                    }
                });
            }

            // Update hours when start or end time changes
            $(document).on('change', '#shiftTable .start-time, #shiftTable .end-time', function() {
                let row = $(this).closest('tr');
                updateHoursForRow(row);
            });

            // Calculate hours for a specific row
            function updateHoursForRow(row) {
                let startTime = row.find('.start-time').val();
                let endTime = row.find('.end-time').val();
                let hoursCell = row.find('.hours');

                if (startTime && endTime) {
                    let [sh, sm] = startTime.split(':').map(Number);
                    let [eh, em] = endTime.split(':').map(Number);

                    let startMinutes = sh * 60 + sm;
                    let endMinutes = eh * 60 + em;

                    let diffMinutes = endMinutes - startMinutes;
                    if (diffMinutes < 0) diffMinutes += 24 * 60; // Handle overnight shifts

                    let hours = (diffMinutes / 60).toFixed(2);
                    hoursCell.text(hours);
                } else {
                    hoursCell.text('0');
                }
            }

            // Save shift
            $(document).on('click', '.save-shift', function() {
                let row = $(this).closest('tr');
                let employeeId = $(this).data('id');
                let start = row.find('.start-time').val();
                let end = row.find('.end-time').val();
                let preferred = row.find('td:nth-child(2)').text();
                let hours = row.find('.hours').text();
                let date = row.closest('table').data('date');
                $.ajax({
                    url: "{{ route('shift.save') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        employee_id: employeeId,
                        start_time: start,
                        end_time: end,
                        preferred_time: preferred,
                        hours: hours,
                        date: date,
                    },
                    success: function(res) {
                        if (res.success) {
                            row.find('.hours').text(res.hours);
                            row.find('.total-hours').text(res.total_hours);
                            row.find('.assigned-time').text(res.user_start_time + ' - ' + res
                                .user_end_time);

                            // ✅ Select option ke selected kore dao
                            row.find('.start-time').val(res.start_time);
                            row.find('.end-time').val(res.end_time);

                            alert(res.message);
                        } else {
                            alert(res.message);
                        }
                    },

                    error: function(xhr) {
                        alert('Error saving shift: ' + xhr.responseJSON.message);
                    }
                });
            });

            // View shift with modal
            $(document).on('click', '.view-shift', function() {
                let empId = $(this).data('id');
                let date = $(this).data('date');

                $('#shiftModal').modal('show');
                $('#shiftModalBody').html(
                    '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                );

                $.get("{{ url('shift/view') }}/" + empId + "?date=" + date, function(data) {
                    $('#shiftModalBody').html(data);
                }).fail(function(xhr, status, error) {
                    $('#shiftModalBody').html('<div class="text-danger">Error loading data.</div>');
                });
            });

            // Search
            $('#searchUser').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $("#shiftTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $('#monthFilter').on('change', function() {
                let month = $(this).val(); // e.g., "2025-09"
                window.location.href = '{{ route('shift-manager.index') }}?month=' + month;
            });

            // Initial hours calculation
            updateHours();
        });
    </script>
@endpush

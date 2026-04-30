@extends('backend.layouts.master')

@section('admin-content')

    <div class="container">
        <h3 class="mb-4">Shift Manager</h3>
        <div class="mb-3">
            <label for="monthFilter">Select Month:</label>
            <input type="month" id="monthFilter" class="form-control" value="{{ $selectedMonth }}">
            <input type="hidden" id="selectedMonth" value="{{ $selectedMonth }}">
        </div>
        <div class="mb-3">
            <div id="weekButtons" class="d-flex flex-wrap gap-2"></div>
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
                    <th>Predefined Shift</th>
                    <th>Employee Assigned Time</th>
                    <th>Hours</th>
                    <th>Total Hours</th>
                    <th>Place</th>
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
                        <td>
                            <select class="form-select predefined-shift">
                                <option value="">Select Shift</option>
                                <option value="16:00-22:00">16:00 - 22:00</option>
                                <option value="15:00-22:00">15:00 - 22:00</option>
                                <option value="10:30-22:00">10:30 - 22:00</option>
                                <option value="11:00-22:00">11:00 - 22:00</option>
                                <option value="11:30-22:00">11:30 - 22:00</option>
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
                            <select name="place" class="form-select place" required>

                                <option value="nusle" {{ $availability?->place == 'nusle' ? 'selected' : '' }}>Nusle
                                </option>
                                <option value="andel" {{ $availability?->place == 'andel' ? 'selected' : '' }}>Andel
                                </option>
                                <option value="event" {{ $availability?->place == 'event' ? 'selected' : '' }}>Event
                                </option>
                            </select>
                        </td>
                        <td>
                            <button style="display: none" class="btn btn-sm btn-success save-shift"
                                data-id="{{ $emp->id }}">Update</button>
                            <button class="btn btn-sm btn-danger delete-shift"
                                data-id="{{ $emp->id }}">Delete</button>

                            {{-- <button class="btn btn-sm btn-info view-shift" data-id="{{ $emp->id }}"
                                data-date="{{ $selectedDate }}">View</button> --}}
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

    <div class="modal fade" id="weekModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Weekly Overview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3 text-center">
                        <button class="btn btn-success branch-btn" data-branch="nusle">Nusle</button>
                        <button class="btn btn-warning branch-btn" data-branch="andel">Andel</button>
                    </div>

                    <div id="weekOverviewContent">
                        <div class="text-center">Select branch...</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script>
        $(document).ready(function() {

            // 🔥 IMPORTANT: moment.js check
            if (typeof moment === 'undefined') {
                console.error('Moment.js not loaded!');
            }

            let selectedMonth = $('#selectedMonth').val();
            generateWeekButtons(selectedMonth);

            // =============================
            // Load shifts for a specific date
            // =============================
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
                }).fail(function() {
                    $('#shiftTable').html(`
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center text-danger">Error loading shifts</td>
                        </tr>
                    </tbody>
                `);
                });
            };

            // =============================
            // Hours calculation (ALL rows)
            // =============================
            function updateHours() {
                $('#shiftTable tbody tr').each(function() {
                    updateHoursForRow($(this));
                });
            }

            // =============================
            // Single row hours calculation
            // =============================
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
                    if (diffMinutes < 0) diffMinutes += 24 * 60;

                    let hours = (diffMinutes / 60).toFixed(2);
                    hoursCell.text(hours);
                } else {
                    hoursCell.text('0');
                }
            }

            // =============================
            // Change trigger (auto save)
            // =============================
            $(document).on('change', '#shiftTable .start-time, #shiftTable .end-time, #shiftTable .place',
                function() {
                    let row = $(this).closest('tr');
                    updateHoursForRow(row);
                    row.find('.save-shift').trigger('click');
                });

            // =============================
            // Save shift
            // =============================
            $(document).on('click', '.save-shift', function() {
                let row = $(this).closest('tr');
                let employeeId = $(this).data('id');

                $.ajax({
                    url: "{{ route('shift.save') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        employee_id: employeeId,
                        start_time: row.find('.start-time').val(),
                        end_time: row.find('.end-time').val(),
                        preferred_time: row.find('td:nth-child(2)').text(),
                        hours: row.find('.hours').text(),
                        date: row.closest('table').data('date'),
                        place: row.find('.place').val(),
                        selected_month: $('#selectedMonth').val()
                    },
                    success: function(res) {
                        if (res.success) {
                            row.find('.hours').text(res.hours);
                            row.find('.total-hours').text(res.total_hours);
                            row.find('.assigned-time').text(res.user_start_time + ' - ' + res
                                .user_end_time);
                            row.find('.start-time').val(res.start_time);
                            row.find('.end-time').val(res.end_time);
                            row.find('.place').val(res.place);
                        }
                    }
                });
            });

            // =============================
            // Delete shift
            // =============================
            $(document).on('click', '.delete-shift', function() {
                if (!confirm("Are you sure?")) return;

                $.post("{{ route('shift.delete') }}", {
                    _token: "{{ csrf_token() }}",
                    employee_id: $(this).data('id'),
                    date: $('#shiftTable').data('date')
                }, function(res) {
                    if (res.success) {
                        alert(res.message);
                        loadShifts($('#shiftTable').data('date'));
                    }
                });
            });

            // =============================
            // Predefined shift
            // =============================
            $(document).on('change', '.predefined-shift', function() {
                let row = $(this).closest('tr');
                let value = $(this).val();

                if (value) {
                    let [start, end] = value.split('-');

                    row.find('.start-time').val(start);
                    row.find('.end-time').val(end);

                    updateHoursForRow(row);
                    row.find('.save-shift').trigger('click');
                }
            });

            // =============================
            // Search
            // =============================
            $('#searchUser').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $("#shiftTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // =============================
            // Month change
            // =============================
            $('#monthFilter').on('change', function() {
                let month = $(this).val();
                window.location.href = '{{ route('shift-manager.index') }}?month=' + month;
            });

            // =============================
            // Highlight rows
            // =============================
            function highlightAssignedRows() {
                $('#shiftTable tbody tr').each(function() {
                    let row = $(this);
                    let start = row.find('.start-time').val();
                    let end = row.find('.end-time').val();

                    if (start !== "00:00" || end !== "00:00") {
                        row.css('background-color', '#d4edda');
                    } else {
                        row.css('background-color', '');
                    }
                });
            }

            updateHours();
            highlightAssignedRows();

            // =============================
            // WEEK BUTTON CLICK
            // =============================
            let selectedWeek = {
                start: null,
                end: null
            };

            $(document).on('click', '.week-btn', function() {
                selectedWeek.start = $(this).data('start');
                selectedWeek.end = $(this).data('end');

                $('#weekModal').modal('show');
            });

            // =============================
            // Branch click
            // =============================
            $(document).on('click', '.branch-btn', function() {

                if (!selectedWeek.start || !selectedWeek.end) {
                    alert('Please select a week first!');
                    return;
                }

                let branch = $(this).data('branch');

                $('#weekOverviewContent').html('<div class="spinner-border"></div>');

                $.get("{{ route('shift.week.overview') }}", {
                    start_date: selectedWeek.start,
                    end_date: selectedWeek.end,
                    branch: branch
                }, function(res) {
                    $('#weekOverviewContent').html(res);
                });
            });

        });

        // =============================
        // Generate week buttons
        // =============================
        function generateWeekButtons(month) {
            let start = moment(month + "-01").startOf('month');
            let end = moment(month + "-01").endOf('month');

            let container = $('#weekButtons');
            container.empty();

            let current = start.clone().startOf('week').add(1, 'day');

            let weekIndex = 1;

            while (current.isBefore(end)) {
                let weekStart = current.clone();
                let weekEnd = current.clone().add(6, 'days');

                let label = weekStart.format('DD MMM') + ' - ' + weekEnd.format('DD MMM');

                container.append(`
                <button class="btn btn-sm btn-primary week-btn"
                    data-start="${weekStart.format('YYYY-MM-DD')}"
                    data-end="${weekEnd.format('YYYY-MM-DD')}">
                    Week ${weekIndex}<br>${label}
                </button>
            `);

                current.add(7, 'days');
                weekIndex++;
            }
        }
    </script>
@endpush

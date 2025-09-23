<table class="table table-bordered table-striped" id="shiftTable" data-date="{{ $selectedDate }}">
    <thead>
        <tr>
            <th>Name</th>
            <th>Preferred Time</th>
            <th>Start</th>
            <th>End</th>
            <th>Assigned Time (Start to End)</th>
            <th>Hours</th>
            <th>Total Hours</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $emp)
            @php
                $shift = \App\Models\ShiftAssignment::where('employee_id', $emp->id)
                    ->where('date', $selectedDate)
                    ->first();
                $availability = \App\Models\EmployeeAvailability::where('employee_id', $emp->id)
                    ->where('date', $selectedDate)
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
                <td class="hours">0</td>
                <td class="total_hours">{{ $emp->total_hours }}</td>

                <td>
                    <button class="btn btn-sm btn-success save-shift" data-id="{{ $emp->id }}">Save</button>
                    <button class="btn btn-sm btn-info view-shift" data-id="{{ $emp->id }}"
                        data-date="{{ $selectedDate }}">View</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

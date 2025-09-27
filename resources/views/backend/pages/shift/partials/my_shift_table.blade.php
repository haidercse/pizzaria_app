<table class="table table-bordered table-striped align-middle text-center">
    <thead class="table-primary">
        <tr>
            <th style="width: 15%;">Date</th>
            <th style="width: 15%;">Time</th>
            <th style="width: 15%;">Place</th>
            <th style="width: 25%;">With Whom</th>
            <th style="width: 30%;">Day Task</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($shifts as $date => $dayShifts)
            @foreach ($dayShifts as $index => $shift)
                <tr>
                    {{-- Date শুধু প্রথম row তে দেখাবে --}}
                    @if ($index === 0)
                        <td rowspan="{{ count($dayShifts) }}" class="fw-bold align-middle">
                            {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
                        </td>
                    @endif

                    {{-- Time --}}
                    <td>
                        {{ $shift->start_time ? \Carbon\Carbon::parse($shift->start_time)->format('H:i') : 'N/A' }}
                        -
                        {{ $shift->end_time ? \Carbon\Carbon::parse($shift->end_time)->format('H:i') : 'N/A' }}
                        <br>
                        <small class="text-muted">({{ $shift->hours ?? 0 }}h)</small>
                    </td>

                    {{-- Place --}}
                    <td>
                        @php
                            $color = $badgeColors[$shift->place] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $color }} p-2">
                            {{ ucfirst($shift->place ?? 'N/A') }}
                        </span>
                    </td>

                    {{-- With Whom --}}
                    <td>{{ $shift->employee->name }}</td>

                    {{-- Day Task --}}
                    <td>
                        @php
                        if ($shift->dayTask) {
                            $taskName = $shift->dayTask->task_name;
                            $taskColor = 'badge bg-warning';
                        } else {
                            $taskName = 'No Task Assigned';
                            $taskColor = '';
                        }
                        @endphp
                       <span class="{{ $taskColor }} p-2">{{ $taskName }}</span>
                    </td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="5" class="text-muted text-center">
                    No shifts available for this week.
                </td>
            </tr>
        @endforelse

        {{-- Weekly Total --}}
        <tr>
            <td colspan="5" class="text-muted text-center fw-bold">
                Weekly Total Hour: {{ $weeklyTotal ?? 0 }} hours
            </td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-striped align-middle text-center">
    <thead class="table-primary">
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Place</th>
            <th>Employee</th>
            <th>Day Task</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($shifts as $date => $dayShifts)
            @foreach ($dayShifts as $index => $shift)
                <tr>
                    @if ($index === 0)
                        <td rowspan="{{ count($dayShifts) }}" class="fw-bold align-middle">
                            {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
                        </td>
                    @endif

                    <td>
                        {{ $shift->start_time ? \Carbon\Carbon::parse($shift->start_time)->format('H:i') : 'N/A' }}
                        -
                        {{ $shift->end_time ? \Carbon\Carbon::parse($shift->end_time)->format('H:i') : 'N/A' }}
                        <br>
                        <small class="text-muted">({{ $shift->hours ?? 0 }}h)</small>
                    </td>

                    <td>
                        @php
                            $color = $badgeColors[$shift->place] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $color }} p-2">
                            {{ ucfirst($shift->place ?? 'N/A') }}
                        </span>
                    </td>

                    {{-- With Whom --}}
                    <td>
                        @php
                            // ওই দিনের সব availability collect করে একসাথে দেখানোর জন্য
                            $availabilities = $dayShifts->where('place', $shift->place);
                        @endphp

                        @if ($availabilities->count() > 0)
                            @foreach ($availabilities as $av)
                                {{ $av->employee->name }}
                                ({{ $av->start_time ? \Carbon\Carbon::parse($av->start_time)->format('H:i') : 'N/A' }}
                                -
                                {{ $av->end_time ? \Carbon\Carbon::parse($av->end_time)->format('H:i') : 'N/A' }})
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @else
                            <span class="text-muted">No Employee</span>
                        @endif
                    </td>


                    <td>
                        @php
                            $taskName = $shift->dayTask->task_name ?? 'No Task Assigned';
                            $taskColor = $shift->dayTask ? 'badge bg-warning' : '';
                        @endphp
                        <span class="{{ $taskColor }} p-2">{{ $taskName }}</span>
                    </td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="5" class="text-muted text-center">No shifts found</td>
            </tr>
        @endforelse

        <tr>
            <td colspan="5" class="text-muted text-center fw-bold">
                Weekly Total Hour: {{ $weeklyTotal ?? 0 }} hours
            </td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-striped align-middle text-center">
    <thead class="table-primary">
        <tr>
            <th style="width: 15%;">Date</th>
            <th style="width: 15%;">Time</th>
            <th style="width: 15%;">Place</th>
            <th style="width: 25%;">With Whom</th>
        </tr>
    </thead>

    <tbody>
        <p>Monthly Total Hours:
            <span class="badge badge-info">{{ $monthlyTotal ?? 0 }}</span>
            hours
        </p>

        @forelse ($shifts as $date => $dayShifts)

            @php
                // Only logged in user's valid shifts
$myShifts = $dayShifts->filter(function ($s) {
    return $s->employee_id == auth()->id() &&
        ($s->hours ?? 0) > 0 &&
        $s->start_time &&
        $s->end_time &&
        \Carbon\Carbon::parse($s->start_time)->format('H:i') != '00:00' &&
        \Carbon\Carbon::parse($s->end_time)->format('H:i') != '00:00';
                });

                if ($myShifts->count() == 0) {
                    continue;
                }
            @endphp

            @foreach ($myShifts as $shift)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}</td>

                    {{-- Time --}}
                    <td>
                        {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                        -
                        {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                        <br>
                        <small class="text-muted">({{ $shift->hours ?? 0 }}h)</small>
                    </td>

                    {{-- Place --}}
                    <td>
                        @php
                            $badgeColors = [
                                'andel' => 'bg-warning text-dark',
                                'nusle' => 'bg-danger',
                            ];
                            $color = $badgeColors[$shift->place] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $color }} p-2">
                            {{ ucfirst($shift->place ?? 'N/A') }}
                        </span>
                    </td>

                    {{-- With Whom --}}
                    <td>
                        @php
                            // Colleagues same date + same place + valid shift + not me
                            $partners = $dayShifts->filter(function ($av) use ($shift) {
                                return $av->employee_id != auth()->id() &&
                                    $av->place == $shift->place &&
                                    ($av->hours ?? 0) > 0 &&
                                    $av->start_time &&
                                    $av->end_time &&
                                    \Carbon\Carbon::parse($av->start_time)->format('H:i') != '00:00' &&
                                    \Carbon\Carbon::parse($av->end_time)->format('H:i') != '00:00';
                            });
                        @endphp

                        @if ($partners->count() > 0)
                            @foreach ($partners as $p)
                                {{ $p->employee->name }}
                                ({{ \Carbon\Carbon::parse($p->start_time)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($p->end_time)->format('H:i') }})
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @else
                            <span class="text-muted">No Employee</span>
                        @endif
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

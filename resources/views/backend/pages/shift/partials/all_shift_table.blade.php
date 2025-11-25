<table class="table table-bordered table-striped align-middle text-center">
    <thead class="table-primary">
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Place</th>
            <th>Employee</th>
            {{-- <th>Day Task</th> --}}
        </tr>
    </thead>
    <tbody>
        @forelse ($shifts as $date => $dayShifts)

            @php
                // Group shifts by place (andel, nusle, event etc)
                $placeGroups = $dayShifts->groupBy('place');
            @endphp

            @foreach ($placeGroups as $place => $placeShifts)
                <tr>
                    {{-- Date column – only 1 time per date --}}
                    <td>
                        {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
                    </td>

                    {{-- Time column – show combined time range --}}
                    @php
                        // Show first shift time range; or combine as you like
                        $first = $placeShifts->first();
                    @endphp
                    <td>
                        {{ \Carbon\Carbon::parse($first->start_time)->format('H:i') }}
                        -
                        {{ \Carbon\Carbon::parse($first->end_time)->format('H:i') }}
                        <br>
                        <small class="text-muted">
                            ({{ $placeShifts->sum('hours') }}h)
                        </small>
                    </td>

                    {{-- Place --}}
                    <td>
                        @php
                            $badgeColors = [
                                'andel' => 'bg-warning text-dark',
                                'nusle' => 'bg-danger',
                                'event' => 'bg-info text-dark',
                            ];
                            $color = $badgeColors[$place] ?? 'bg-secondary';
                        @endphp

                        <span class="badge {{ $color }} p-2">
                            {{ ucfirst($place) }}
                        </span>
                    </td>

                    {{-- Employee List --}}
                    <td>
                        @foreach ($placeShifts as $shift)
                            {{ $shift->employee->name }}
                            ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
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

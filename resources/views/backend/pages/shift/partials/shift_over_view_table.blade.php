<div id="shiftTableWrapper">
    <div class="table-responsive shift-table-wrapper">
        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th class="sticky-col">User</th>
                    @for ($d = 1; $d <= $daysInMonth; $d++)
                        <th>{{ $d }}</th>
                    @endfor
                    <th>Total Hours</th>
                    <th>Place Summary</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    @php
                        $availabilities = $user->availabilities->keyBy(function ($item) {
                            return \Carbon\Carbon::parse($item->date)->day;
                        });
                        $monthlyTotal = $user->availabilities->sum('hours');
                        $placeHours = [];
                        foreach ($user->availabilities as $shift) {
                            if ($shift->place) {
                                $placeHours[$shift->place] = ($placeHours[$shift->place] ?? 0) + ($shift->hours ?? 0);
                            }
                        }
                    @endphp
                    <tr>
                        <td class="fw-bold sticky-col">{{ $user->name }}</td>
                        @for ($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $badgeColors = [
                                    'andel' => 'bg-warning text-dark', // yellow
                                    'nusle' => 'bg-danger', // red
                                ];
                                $shift = $availabilities[$d] ?? null;
                                $badgeClass =
                                    $shift && isset($badgeColors[$shift->place])
                                        ? $badgeColors[$shift->place]
                                        : 'bg-info';
                            @endphp
                            <td style="min-width: 120px;">
                                @if ($shift && $shift->start_time && $shift->end_time && $shift->hours > 0)
                                    <div class="badge {{ $badgeClass }} p-2 w-100">
                                        <div>
                                            {{ $shift->start_time ? \Carbon\Carbon::parse($shift->start_time)->format('H:i') : 'N/A' }}
                                            -
                                            {{ $shift->end_time ? \Carbon\Carbon::parse($shift->end_time)->format('H:i') : 'N/A' }}
                                        </div>
                                        <div>{{ ucfirst($shift->place ?? 'N/A') }}</div>
                                        <div>Hours: {{ $shift->hours ?? 0 }}</div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        @endfor
                        <td class="fw-bold">{{ $monthlyTotal }}</td>
                        <td>
                            @if (count($placeHours))
                                @foreach ($placeHours as $place => $hours)
                                    @php $class = $badgeColors[$place] ?? 'bg-secondary'; @endphp
                                    <span class="badge {{ $class }} p-2 mb-1 d-block">
                                        {{ ucfirst($place) }}: {{ $hours }}h
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $daysInMonth + 3 }}" class="text-muted">No shifts available for this month.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="table-secondary fw-bold">
                <tr>
                    <td>Total (all users)</td>
                    @for ($d = 1; $d <= $daysInMonth; $d++)
                        <td>{{ $dailyTotals[$d] ?? 0 }}</td>
                    @endfor
                    <td>{{ array_sum($dailyTotals) }}</td>
                    <td>-</td>
                </tr>
            </tfoot>
        </table>
        <p>Total Hours (All Users): {{ $totalHoursAllUsers }}</p>
    </div>
</div>

@php
    $badgeColors = [
        'andel' => 'bg-warning text-dark',
        'nusle' => 'bg-success',
        'event' => 'bg-info text-dark',
    ];
@endphp

{{-- Monthly total --}}
<p class="text-muted">
    Monthly Total Hour:
    <span class="badge bg-warning">{{ $monthlyTotal ?? 0 }}</span>
</p>

<table class="table table-bordered table-striped align-middle text-center">
    <thead class="table-primary">
        <tr>
            <th style="width: 20%;">Date</th>
            <th style="width: 20%;">Time</th>
            <th style="width: 20%;">Place</th>
            <th style="width: 40%;">With Whom</th>
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

                    {{-- Place with Badge --}}
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
                        {{ $shift->employee->name }}
                    </td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="4" class="text-muted text-center">
                    No shifts available for this week.
                </td>
            </tr>
        @endforelse

        {{-- Weekly Total --}}
        <tr>
            <td colspan="4" class="text-muted text-center fw-bold">
                Weekly Total Hour: {{ $weeklyTotal ?? 0 }} hours
            </td>
        </tr>
    </tbody>
</table>

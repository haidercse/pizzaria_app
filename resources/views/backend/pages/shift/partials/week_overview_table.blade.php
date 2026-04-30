@php
    $startDate = \Carbon\Carbon::parse($start);
    $endDate = \Carbon\Carbon::parse($end);

    $days = [];
    for ($d = $startDate->copy(); $d <= $endDate; $d->addDay()) {
        $dateStr = $d->toDateString();
        $days[] = [
            'label' => $d->format('l (d M)'),
            'date' => $dateStr,
            'shifts' => $shifts[$dateStr] ?? collect(),
        ];
    }
@endphp

<style>
    .day-card {
        border-radius: 12px;
        padding: 10px;
        background: #f8f9fa;
        height: 100%;
    }

    .day-title {
        font-weight: bold;
        font-size: 14px;
        margin-bottom: 6px;
    }

    .shift-row {
        font-size: 13px;
        padding: 4px 6px;
        border-radius: 6px;
        margin-bottom: 4px;
        background: #e9ecef;
    }

    .hours-summary {
        background: #212529;
        color: #fff;
        padding: 10px;
        border-radius: 10px;
        text-align: center;
    }
</style>

{{-- ✅ Weekly Summary --}}

<div class="mb-3">
    <div class="hours-summary">
        <h6>Total Hours (Week)</h6>
        <h4>{{ round($totalWeekHours, 2) }}</h4>
    </div>
</div>

{{-- ✅ 7 Days Grid --}}
<div class="row g-2">

    @foreach ($days as $day)
        <div class="col-md-3 col-lg-3 col-6">
            <div class="day-card">

                <div class="day-title">
                    {{ $day['label'] }}
                </div>

                @if ($day['shifts']->count() > 0)
                    @foreach ($day['shifts'] as $shift)
                        <div class="shift-row">
                            <strong>{{ $shift->employee->name }}</strong><br>
                            {{ substr($shift->start_time, 0, 5) }} - {{ substr($shift->end_time, 0, 5) }}
                            | {{ $shift->hours }}h
                            <span class="badge bg-secondary float-end">{{ $shift->place }}</span>
                        </div>
                    @endforeach
                @else
                    <small class="text-muted">No shifts</small>
                @endif

            </div>
        </div>
    @endforeach

</div>

<div class="row mt-3">

    {{-- HOURS PER WEEK --}}
    <div class="col-md-6">
        <div class="summary-box">
            <h6 class="text-center bg-warning">HOURS PER WEEK</h6>
            <table class="table table-sm table-bordered text-center">
                <tbody>
                    @foreach ($hoursPerWeek as $name => $hours)
                        <tr>
                            <td class="name-cell">{{ $name }}</td>
                            <td><strong>{{ $hours }}</strong></td>
                        </tr>
                    @endforeach
                    <tr class="table-danger">
                        <td><strong>TOTAL</strong></td>
                        <td><strong>{{ $hoursPerWeek->sum() }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- TOTAL HOURS PER WEEK --}}
    <div class="col-md-6">
        <div class="summary-box">
            <h6 class="text-center bg-warning">TOTAL HOURS PER WEEK</h6>
            <table class="table table-sm table-bordered text-center">
                <tbody>
                    @foreach ($totalHoursPerWeek as $name => $hours)
                        <tr>
                            <td class="name-cell">{{ $name }}</td>
                            <td><strong>{{ $hours }}</strong></td>
                        </tr>
                    @endforeach
                    <tr class="table-danger">
                        <td><strong>TOTAL</strong></td>
                        <td><strong>{{ $totalHoursPerWeek->sum() }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

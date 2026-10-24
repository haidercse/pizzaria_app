{{-- resources/views/backend/pages/schedules/weekly.blade.php --}}
@php
    use Carbon\Carbon;

    // Build every date in the requested range.
    $days = [];
    $date = $startDate->copy()->startOfDay();
    while ($date->lte($endDate->copy()->startOfDay())) {
        $days[] = $date->copy();
        $date->addDay();
    }

    // Original controller data is expected to be grouped by YYYY-MM-DD.
    $shiftsByDay = collect($shifts);
    $allShifts = $shiftsByDay->flatten(1)->filter(function ($shift) {
        return (float) ($shift->hours ?? 0) > 0 || (float) ($shift->calculated_hours ?? 0) > 0;
    });

    // Build location groups for the "All" view. Known locations appear first;
    // any other locations (including Event) are added automatically.
    $normalisePlace = fn ($value) => str_replace('anděl', 'andel', mb_strtolower(trim((string) $value)));
    $placeGroups = collect();
    if (filled($place ?? null)) {
        $placeGroups->push(['key' => $normalisePlace($place), 'label' => ucfirst($place)]);
    } else {
        $knownOrder = ['nusle', 'andel', 'event'];
        $availablePlaces = $allShifts
            ->map(fn ($shift) => trim((string) ($shift->place ?? '')))
            ->filter()
            ->unique(fn ($value) => $normalisePlace($value));
        $orderedPlaces = $availablePlaces->sortBy(function ($value) use ($knownOrder, $normalisePlace) {
            $index = array_search($normalisePlace($value), $knownOrder, true);
            return $index === false ? 100 : $index;
        })->values();
        foreach ($orderedPlaces as $value) {
            $placeGroups->push(['key' => $normalisePlace($value), 'label' => ucfirst($value)]);
        }
    }

    // Dynamically get employees and their saved colors from the shifts.
    $employees = $allShifts
        ->filter(fn ($shift) => $shift->employee)
        ->mapWithKeys(fn ($shift) => [$shift->employee_id => $shift->employee])
        ->sortBy(fn ($employee) => mb_strtolower($employee->name ?? ''));

    // Excel-like time rows. Change these values if your business hours differ.
    $slotStart = '10:30';
    $slotEnd = '22:30';
    $slotIntervalMinutes = 60;
    $timeSlots = [];
    $cursor = Carbon::createFromFormat('H:i', $slotStart);
    $lastSlot = Carbon::createFromFormat('H:i', $slotEnd);
    while ($cursor->lte($lastSlot)) {
        $timeSlots[] = $cursor->format('H:i');
        $cursor->addMinutes($slotIntervalMinutes);
    }

    $employeeColor = function ($employee) {
        $color = $employee?->color ?? '#4a90d9';
        return preg_match('/^#[0-9A-Fa-f]{6}$/', $color) ? $color : '#4a90d9';
    };

    $employeeInitial = fn ($employee) => mb_strtoupper(mb_substr(trim($employee->name ?? '?'), 0, 1));
    $formatHours = fn ($hours) => rtrim(rtrim(number_format((float) $hours, 1), '0'), '.');

    // Hours summary uses the stored hours when positive, otherwise calculated_hours.
    $employeeSummaries = $employees->map(function ($employee, $employeeId) use ($allShifts) {
        $employeeShifts = $allShifts->filter(fn ($shift) => (string) $shift->employee_id === (string) $employeeId);
        $hours = $employeeShifts->sum(fn ($shift) => (float) ($shift->hours ?? 0) > 0
            ? (float) $shift->hours
            : (float) ($shift->calculated_hours ?? 0));
        return ['employee' => $employee, 'hours' => $hours];
    });

    // Check whether an employee is working at a particular hourly slot.
    $employeeWorksAt = function ($dayShifts, $employeeId, $time, $dayDate) {
        $slotTime = Carbon::createFromFormat('Y-m-d H:i', $dayDate.' '.$time);

        return $dayShifts->contains(function ($shift) use ($employeeId, $slotTime) {
            if ((string) $shift->employee_id !== (string) $employeeId) {
                return false;
            }
            $shiftDate = $shift->date ?? $shift->shift_date ?? null;
            $dateString = $shiftDate ? Carbon::parse($shiftDate)->format('Y-m-d') : $slotTime->format('Y-m-d');
            $start = Carbon::parse($dateString.' '.$shift->start_time);
            $end = Carbon::parse($dateString.' '.$shift->end_time);
            if ($end->lte($start)) {
                $end->addDay();
            }
            return $slotTime->gte($start) && $slotTime->lt($end);
        });
    };
@endphp

<style>
    .excel-weekly-schedule,
    .excel-weekly-schedule * { box-sizing: border-box; }
    .excel-weekly-schedule {
        --grid-border: #555;
        --grid-light-border: #d2d2d2;
        width: 100%; min-width: 0; color: #111;
        font: 12px Arial, Helvetica, sans-serif;
    }
    .excel-weekly-schedule__top {
        display: flex; align-items: flex-start; gap: 12px; width: 100%;
    }
    .excel-weekly-schedule__location-section {
        width: 100%; min-width: 0; margin-bottom: 22px;
        padding: 12px; border: 1px solid #d8dee8; border-radius: 8px;
        background: #fff;
    }
    .excel-weekly-schedule__location-title {
        margin: 0 0 10px; font-size: 16px; font-weight: 700; color: #263238;
    }
    .excel-weekly-schedule__main { flex: 1 1 auto; min-width: 0; }
    .excel-weekly-schedule__scroll { width: 100%; overflow-x: auto; overscroll-behavior-x: contain; }
    .excel-weekly-schedule__table {
        border-collapse: collapse; table-layout: fixed; width: max-content; min-width: 100%;
        background: #fff; font-size: 11px;
    }
    .excel-weekly-schedule__table th,
    .excel-weekly-schedule__table td {
        border: 1px solid var(--grid-border); height: 20px; min-width: 23px;
        padding: 1px 3px; text-align: center; white-space: nowrap; font-weight: 400;
    }
    .excel-weekly-schedule__table .date-title {
        text-align: left; font-size: 15px; font-weight: 700; text-decoration: underline;
        border: 0; height: 24px; padding-left: 5px;
    }
    .excel-weekly-schedule__table .day-title { font-weight: 700; background: #fff; }
    .excel-weekly-schedule__table .day-start {
        border-left: 2px solid #94a3b8 !important;
        box-shadow: inset 3px 0 5px rgba(71, 85, 105, .13);
    }
    .excel-weekly-schedule__table .time-heading,
    .excel-weekly-schedule__table .time-cell {
        width: 51px; min-width: 51px; background: #fff; text-align: right;
        font-weight: 600; padding-right: 5px;
    }
    .excel-weekly-schedule__table .employee-heading { font-weight: 700; padding: 0; }
    .excel-weekly-schedule__table .employee-cell { font-weight: 400; }
    .excel-weekly-schedule__table .totals-row td { font-weight: 700; border-top: 2px solid #333; }
    .excel-weekly-schedule__table .total-label { background: #eee; }
    .excel-weekly-schedule__hours { flex: 0 0 210px; width: 210px; }
    .excel-weekly-schedule__hours table { border-collapse: collapse; width: 100%; table-layout: fixed; font-size: 13px; }
    .excel-weekly-schedule__hours th,
    .excel-weekly-schedule__hours td { border: 1px solid #222; padding: 4px 5px; height: 24px; }
    .excel-weekly-schedule__hours th { font-weight: 700; text-align: center; }
    .excel-weekly-schedule__hours .employee-name { font-weight: 700; }
    .excel-weekly-schedule__hours .hours-value { text-align: center; font-weight: 700; }
    .excel-weekly-schedule__legend { display: flex; flex-wrap: wrap; gap: 6px 10px; margin-top: 10px; }
    .excel-weekly-schedule__legend-item { display: inline-flex; align-items: center; gap: 4px; }
    .excel-weekly-schedule__swatch { display: inline-block; width: 12px; height: 12px; border: 1px solid #777; }
    
    @media (max-width: 900px) {
        .excel-weekly-schedule__top { flex-direction: column; }
        .excel-weekly-schedule__hours {
            order: 2; display: block; width: 100%; max-width: none; flex: 0 0 auto;
            margin-top: 12px;
        }
        .excel-weekly-schedule__main { order: 1; width: 100%; }
        .excel-weekly-schedule__hours table { table-layout: auto; }
        .excel-weekly-schedule__hours th,
        .excel-weekly-schedule__hours td { padding: 8px; }
    }
</style>

@foreach ($placeGroups as $placeGroup)
    @php
        $locationShifts = $allShifts->filter(fn ($shift) => $normalisePlace($shift->place ?? '') === $placeGroup['key'])->values();
        if ($locationShifts->isEmpty()) {
            continue;
        }
        $locationShiftsByDay = $locationShifts->groupBy(fn ($shift) => Carbon::parse($shift->date ?? $shift->shift_date)->toDateString());
        $locationEmployees = $locationShifts
            ->filter(fn ($shift) => $shift->employee)
            ->mapWithKeys(fn ($shift) => [$shift->employee_id => $shift->employee])
            ->sortBy(fn ($employee) => mb_strtolower($employee->name ?? ''));
        $locationEmployeeSummaries = $locationEmployees->map(function ($employee, $employeeId) use ($locationShifts) {
            $employeeShifts = $locationShifts->filter(fn ($shift) => (string) $shift->employee_id === (string) $employeeId);
            $hours = $employeeShifts->sum(fn ($shift) => (float) ($shift->hours ?? 0) > 0
                ? (float) $shift->hours
                : (float) ($shift->calculated_hours ?? 0));
            return ['employee' => $employee, 'hours' => $hours];
        });
        $locationEmployeeWorksAt = function ($dayShifts, $employeeId, $time, $dayDate) {
            $slotTime = Carbon::createFromFormat('Y-m-d H:i', $dayDate.' '.$time);
            return $dayShifts->contains(function ($shift) use ($employeeId, $slotTime) {
                if ((string) $shift->employee_id !== (string) $employeeId) return false;
                $shiftDate = $shift->date ?? $shift->shift_date ?? null;
                $dateString = $shiftDate ? Carbon::parse($shiftDate)->format('Y-m-d') : $slotTime->format('Y-m-d');
                $start = Carbon::parse($dateString.' '.$shift->start_time);
                $end = Carbon::parse($dateString.' '.$shift->end_time);
                if ($end->lte($start)) $end->addDay();
                return $slotTime->gte($start) && $slotTime->lt($end);
            });
        };
        $locationTimeSlots = array_values(array_filter($timeSlots, fn ($time) => $time !== '22:30'));
    @endphp
<section class="excel-weekly-schedule__location-section">
    <h3 class="excel-weekly-schedule__location-title">{{ $placeGroup['label'] }}</h3>
    <section class="excel-weekly-schedule">
    <div class="excel-weekly-schedule__top">
        <div class="excel-weekly-schedule__main">
            <div class="excel-weekly-schedule__scroll">
                <table class="excel-weekly-schedule__table">
                    <thead>
                        <tr>
                            <th class="date-title" colspan="{{ 1 + (count($days) * max($locationEmployees->count(), 1)) }}">
                                {{ $startDate->format('d') }}-{{ $endDate->format('d M') }}
                            </th>
                        </tr>
                        <tr>
                            <th class="day-title"></th>
                            @foreach ($days as $day)
                                <th class="day-title day-start"
                                    colspan="{{ max($locationEmployees->count(), 1) }}">{{ $day->format('l') }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="time-heading"></th>
                            @foreach ($days as $day)
                                @forelse ($locationEmployees as $employee)
                                    <th class="employee-heading {{ $loop->first ? 'day-start' : '' }}"
                                        style="background: {{ $employeeColor($employee) }}; color: #111;" title="{{ $employee->name }}">
                                        {{ $employeeInitial($employee) }}
                                    </th>
                                @empty
                                    <th class="employee-heading day-start">—</th>
                                @endforelse
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($timeSlots as $time)
                            <tr>
                                <td class="time-cell">{{ $time }}</td>
                                @foreach ($days as $day)
                                    @php $dayShifts = $locationShiftsByDay->get($day->toDateString(), collect()); @endphp
                                    @forelse ($locationEmployees as $employeeId => $employee)
                                        @php $working = $locationEmployeeWorksAt($dayShifts, $employeeId, $time, $day->toDateString()); @endphp
                                        <td class="employee-cell {{ $loop->first ? 'day-start' : '' }}"
                                            style="{{ $working ? 'background: '.$employeeColor($employee).'; color: #111;' : 'background: #fff;' }}"
                                            title="{{ $employee->name }} · {{ $day->format('d M') }} · {{ $time }}">
                                            {{ $working ? '1' : '' }}
                                        </td>
                                    @empty
                                        <td class="day-start"></td>
                                    @endforelse
                                @endforeach
                            </tr>
                        @endforeach
                        <tr class="totals-row">
                            <td class="total-label"></td>
                            @foreach ($days as $day)
                                @php $dayShifts = $locationShiftsByDay->get($day->toDateString(), collect()); @endphp
                                @forelse ($locationEmployees as $employeeId => $employee)
                                    @php
                                        $slotTotal = collect($timeSlots)->filter(fn ($time) => $locationEmployeeWorksAt($dayShifts, $employeeId, $time, $day->toDateString()))->count();
                                    @endphp
                                    <td class="{{ $loop->first ? 'day-start' : '' }}"
                                        style="background: {{ $employeeColor($employee) }}">{{ $slotTotal }}</td>
                                @empty
                                    <td class="day-start">0</td>
                                @endforelse
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="excel-weekly-schedule__legend">
                @foreach ($locationEmployees as $employee)
                    <span class="excel-weekly-schedule__legend-item">
                        <i class="excel-weekly-schedule__swatch" style="background: {{ $employeeColor($employee) }}"></i>
                        {{ $employee->name }}
                    </span>
                @endforeach
            </div>
        </div>

        <aside class="excel-weekly-schedule__hours">
            <table>
                <thead><tr><th></th><th>HOURS PER WEEK</th></tr></thead>
                <tbody>
                    @forelse ($locationEmployeeSummaries as $summary)
                        <tr>
                            <td class="employee-name" style="background: {{ $employeeColor($summary['employee']) }}">
                                {{ $summary['employee']->name }}
                            </td>
                            <td class="hours-value">{{ $formatHours($summary['hours']) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2">No shifts scheduled</td></tr>
                    @endforelse
                </tbody>
            </table>
        </aside>
    </div>
</section>
</section>
@endforeach
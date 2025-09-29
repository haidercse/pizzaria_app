<div class="table-responsive shift-table-wrapper">
    <table class="table table-bordered text-center align-middle">
        <thead>
            <tr>
                <th class="sticky-col">User</th>
                @for ($d = 1; $d <= $daysInMonth; $d++)
                    <th>{{ $d }}</th>
                @endfor
                <th>Total Hours</th>
                <th>Salary</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
             @if(($user->monthly_total_hours ?? 0) > 0)
                @php
                    $checkouts = $user->checkouts->keyBy(function ($c) {
                        return \Carbon\Carbon::parse($c->date)->day;
                    });
                    $monthlyTotal = $user->checkouts->sum('worked_hours');
                    $hourlyRate = $user->contract->hourly_rate ?? 0;
                    $salary = $monthlyTotal * $hourlyRate;
                @endphp
                <tr>
                    <td class="fw-bold sticky-col">{{ $user->name }}</td>
                    @for ($d = 1; $d <= $daysInMonth; $d++)
                        @php $checkout = $checkouts[$d] ?? null; @endphp
                        <td style="min-width: 100px;">
                            @if ($checkout)
                                <div class="badge {{ $badgeColors[$checkout->place] ?? 'bg-info' }} p-2 w-100">
                                    {{ ucfirst($checkout->place) }}<br>
                                    {{ $checkout->worked_hours }}h
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    @endfor
                    <td class="fw-bold">{{ $monthlyTotal }}</td>
                    <td class="fw-bold text-success">{{ number_format($user->calculated_salary, 2) }} Kč</td>

                </tr>
                @endif
            @empty
                <tr>
                    <td colspan="{{ $daysInMonth + 3 }}" class="text-muted">No checkouts this month.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot class="table-secondary fw-bold">
            <tr>
                <td>Total (all users)</td>
                @for ($d = 1; $d <= $daysInMonth; $d++)
                    <td>{{ $dailyTotals[$d] ?? 0 }}</td>
                @endfor
                <td>{{ $totalHoursAllUsers }}</td>
                <td>
                    {{ number_format(
                        $users->sum(function ($u) {
                            return $u->calculated_salary ?? 0;
                        }),
                        2,
                    ) }}
                    Kč
                </td>
            </tr>
        </tfoot>
    </table>
</div>

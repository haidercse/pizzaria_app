<div class="table-responsive shift-table-wrapper">
    <table class="table table-bordered text-center align-middle">

        <thead>
            <tr>
                <th class="sticky-col">User</th>

                @for ($d = 1; $d <= $daysInMonth; $d++)
                    <th>{{ $d }}</th>
                @endfor

                <th>Nusle Hours</th>
                <th>Andel Hours</th>

                <th>Total Hours</th>
                <th>Nusle Salary</th>
                <th>Andel Salary</th>

                <th>Salary</th>


            </tr>
        </thead>

        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td class="fw-bold sticky-col">{{ $user->name }}</td>

                    @for ($d = 1; $d <= $daysInMonth; $d++)
                        @php $checkout = $user->checkouts->firstWhere('date', \Carbon\Carbon::parse($startOfMonth)->day($d)->format('Y-m-d')); @endphp

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

                    <td class="fw-bold">{{ $user->nusle_hours }}</td>
                    <td class="fw-bold">{{ $user->andel_hours }}</td>

                    <td class="fw-bold">{{ $user->monthly_total_hours }}</td>

                
                    <td class="fw-bold text-primary">
                        {{ number_format($user->nusle_salary, 2) }} Kč
                    </td>

                    <td class="fw-bold text-info">
                        {{ number_format($user->andel_salary, 2) }} Kč
                    </td>

                    <td class="fw-bold text-success">
                        {{ number_format($user->calculated_salary, 2) }} Kč
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="{{ $daysInMonth + 7 }}" class="text-muted">
                        No checkouts this month.
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

                <td>{{ $users->sum('nusle_hours') }}</td>
                <td>{{ $users->sum('andel_hours') }}</td>

                <td>{{ $totalHoursAllUsers }}</td>

                <td>
                    {{ number_format($users->sum('calculated_salary'), 2) }} Kč
                </td>

                <td>
                    {{ number_format($users->sum('nusle_salary'), 2) }} Kč
                </td>

                <td>
                    {{ number_format($users->sum('andel_salary'), 2) }} Kč
                </td>
            </tr>
        </tfoot>
    </table>
</div>

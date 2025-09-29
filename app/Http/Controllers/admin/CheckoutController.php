<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeCheckout;
use App\Models\Holiday;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $days = [];
        for ($i = 0; $i < $monthEnd->day; $i++) {
            $date = $monthStart->copy()->addDays($i);
            $days[$date->toDateString()] = [
                'date' => $date,
                'checkout' => null,
            ];
        }

        $checkouts = EmployeeCheckout::where('employee_id', $userId)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get()
            ->keyBy('date');

        foreach ($checkouts as $date => $checkout) {
            if (isset($days[$date])) {
                $days[$date]['checkout'] = $checkout;
            }
        }

        return view('backend.pages.checkout.index', compact('days'));
    }
    public function create()
    {
        $today = Carbon::now();
        return view('backend.pages.checkout.create', compact('today'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'place' => 'required|string|max:255',
            'worked_hours' => 'required|numeric|min:0|max:24',
            'date' => 'required|date',
            'day' => 'required|string',
        ]);

        $userId = auth()->id();
        $date = $request->date;

        // Check if already exists, if yes -> update instead
        $checkout = EmployeeCheckout::where('employee_id', $userId)
            ->where('date', $date)
            ->first();

        if ($checkout) {
            $checkout->update([
                'place' => $request->place,
                'worked_hours' => $request->worked_hours,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Checkout updated successfully.',
                'id' => $checkout->id
            ]);
        }

        // Create new checkout
        $checkout = EmployeeCheckout::create([
            'employee_id' => $userId,
            'place' => $request->place,
            'worked_hours' => $request->worked_hours,
            'date' => $date,
            'day' => $request->day,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Checkout saved successfully.',
            'id' => $checkout->id
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'worked_hours' => 'required|numeric|min:0|max:24',
            'place' => 'required|string|max:255',
        ]);

        $checkout = EmployeeCheckout::where('id', $id)
            ->where('employee_id', auth()->id())
            ->firstOrFail();

        $checkout->update([
            'worked_hours' => $request->worked_hours,
            'place' => $request->place,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Checkout updated successfully.'
        ]);
    }

    public function monthlyOverview(Request $request)
    {
        $selectedMonth = $request->get('month', now()->format('Y-m'));
        $startOfMonth = \Carbon\Carbon::parse($selectedMonth . '-01');
        $daysInMonth = $startOfMonth->daysInMonth;

        $users = User::with([
            'checkouts' => function ($q) use ($startOfMonth) {
                $q->whereMonth('date', $startOfMonth->month)
                    ->whereYear('date', $startOfMonth->year);
            },
            'contract'
        ])->get();

        // holiday list নিলাম
        $holidays = Holiday::pluck('date')->toArray();

        $dailyTotals = [];
        foreach ($users as $user) {
            $userSalary = 0;

            foreach ($user->checkouts as $checkout) {
                $rate = $user->contract->hourly_rate ?? 0;
                $dayOfWeek = \Carbon\Carbon::parse($checkout->date)->format('l');
                $isHoliday = in_array($checkout->date, $holidays);

                if ($isHoliday) {
                    // Holiday → 30% extra
                    $userSalary += $checkout->worked_hours * $rate * 1.3;
                } elseif (in_array($dayOfWeek, ['Saturday', 'Sunday'])) {
                    // Weekend → 10% extra
                    $userSalary += $checkout->worked_hours * $rate * 1.1;
                } else {
                    // Normal
                    $userSalary += $checkout->worked_hours * $rate;
                }

                // daily totals হিসাব আগের মতো
                $day = \Carbon\Carbon::parse($checkout->date)->day;
                $dailyTotals[$day] = ($dailyTotals[$day] ?? 0) + $checkout->worked_hours;
            }

            // এখানে salary attach করলাম
            $user->calculated_salary = $userSalary;
            $user->monthly_total_hours = $user->checkouts->sum('worked_hours');
        }
        $users = $users->filter(function ($u) {
            return ($u->monthly_total_hours ?? 0) > 0;
        });
        $totalHoursAllUsers = array_sum($dailyTotals);

        $badgeColors = [
            'andel' => 'bg-primary',
            'nusle' => 'bg-success',
            'event' => 'bg-warning text-dark',
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('backend.pages.checkout.partials.monthly_table', compact(
                    'users',
                    'selectedMonth',
                    'startOfMonth',
                    'daysInMonth',
                    'dailyTotals',
                    'totalHoursAllUsers',
                    'badgeColors'
                ))->render()
            ]);
        }

        return view('backend.pages.checkout.monthly_overview', compact(
            'users',
            'selectedMonth',
            'startOfMonth',
            'daysInMonth',
            'dailyTotals',
            'totalHoursAllUsers',
            'badgeColors'
        ));
    }
}

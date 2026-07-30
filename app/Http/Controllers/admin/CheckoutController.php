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


        $holidays = Holiday::pluck('date')->toArray();


        /*
        |--------------------------------------------------------------------------
        | Total Branch Hours
        |--------------------------------------------------------------------------
        */

        $allCheckouts = $users->flatMap(function ($user) {
            return $user->checkouts;
        });


        $totalNusleHours = $allCheckouts
            ->where('place', 'nusle')
            ->sum('worked_hours');


        $totalAndelHours = $allCheckouts
            ->where('place', 'andel')
            ->sum('worked_hours');


        $totalEventHours = $allCheckouts
            ->where('place', 'event')
            ->sum('worked_hours');


        /*
        |--------------------------------------------------------------------------
        | Manager Input Tips
        |--------------------------------------------------------------------------
        */

        $tipRow = EmployeeCheckout::whereMonth('date', $startOfMonth->month)
            ->whereYear('date', $startOfMonth->year)
            ->whereNotNull('nusle_total_tips')
            ->first();


        $nusleTotalTips = $tipRow->nusle_total_tips ?? 0;

        $andelTotalTips = $tipRow->andel_total_tips ?? 0;



        $dailyTotals = [];


        foreach ($users as $user) {


            $nusleHours = 0;
            $andelHours = 0;
            $eventHours = 0;


            $nusleSalary = 0;
            $andelSalary = 0;



            foreach ($user->checkouts as $checkout) {


                $rate = $user->contract->hourly_rate ?? 0;


                $dayOfWeek = \Carbon\Carbon::parse($checkout->date)
                    ->format('l');


                $isHoliday = in_array($checkout->date, $holidays);



                if ($isHoliday) {

                    $multiplier = 1.2;

                } else {

                    $multiplier = 1;

                }



                $amount =
                    $checkout->worked_hours
                    *
                    $rate
                    *
                    $multiplier;



                /*
                |--------------------------------------------------------------------------
                | Branch Hours + Salary
                |--------------------------------------------------------------------------
                */


                if ($checkout->place == 'nusle') {

                    $nusleHours += $checkout->worked_hours;

                    $nusleSalary += $amount;

                }



                if ($checkout->place == 'andel') {

                    $andelHours += $checkout->worked_hours;

                    $andelSalary += $amount;

                }



                if ($checkout->place == 'event') {

                    $eventHours += $checkout->worked_hours;

                }



                /*
                | Daily total
                */

                $day = \Carbon\Carbon::parse($checkout->date)->day;

                $dailyTotals[$day] =
                    ($dailyTotals[$day] ?? 0)
                    +
                    $checkout->worked_hours;


            }



            /*
            |--------------------------------------------------------------------------
            | Tips Calculation
            |--------------------------------------------------------------------------
            */


            $nusleTips = 0;

            if ($totalNusleHours > 0) {

                $nusleTips =
                    ($nusleHours / $totalNusleHours)
                    *
                    $nusleTotalTips;

            }



            $andelTips = 0;


            if ($totalAndelHours > 0) {

                $andelTips =
                    ($andelHours / $totalAndelHours)
                    *
                    $andelTotalTips;

            }




            /*
            |--------------------------------------------------------------------------
            | Attach Data For Blade
            |--------------------------------------------------------------------------
            */


            $user->nusle_hours = $nusleHours;

            $user->andel_hours = $andelHours;

            $user->event_hours = $eventHours;



            $user->nusle_tips = $nusleTips;

            $user->andel_tips = $andelTips;



            $user->nusle_salary = $nusleSalary;

            $user->andel_salary = $andelSalary;



            $user->monthly_total_hours =
                $nusleHours
                +
                $andelHours
                +
                $eventHours;



            /*
            Salary + Tips
            */

            $user->calculated_salary =
                $nusleSalary
                +
                $andelSalary
                +
                $nusleTips
                +
                $andelTips;

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

                'html' => view(
                    'backend.pages.checkout.partials.monthly_table',
                    compact(
                        'users',
                        'selectedMonth',
                        'startOfMonth',
                        'daysInMonth',
                        'dailyTotals',
                        'totalHoursAllUsers',
                        'badgeColors',
                        'nusleTotalTips',
                        'andelTotalTips'
                    )
                )->render(),

                'nusleTotalTips' => $nusleTotalTips,

                'andelTotalTips' => $andelTotalTips

            ]);

        }



        return view(
            'backend.pages.checkout.monthly_overview',
            compact(
                'users',
                'selectedMonth',
                'startOfMonth',
                'daysInMonth',
                'dailyTotals',
                'totalHoursAllUsers',
                'badgeColors',
                'nusleTotalTips',
                'andelTotalTips'
            )
        );
    }

    public function saveMonthlyTips(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'nusle_total_tips' => 'nullable|numeric',
            'andel_total_tips' => 'nullable|numeric',
        ]);

        $month = \Carbon\Carbon::parse($request->month . '-01');

        EmployeeCheckout::whereMonth('date', $month->month)
            ->whereYear('date', $month->year)
            ->update([
                'nusle_total_tips' => $request->nusle_total_tips ?? 0,
                'andel_total_tips' => $request->andel_total_tips ?? 0,
            ]);

        return response()->json([
            'success' => true
        ]);
    }
}

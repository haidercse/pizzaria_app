<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAvailability;
use App\Models\ShiftAssignment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftManagerController extends Controller
{
    public function index(Request $request)
    {

        $selectedMonth = $request->get('month', now()->format('Y-m'));
        $selectedDate = $request->get('date', $selectedMonth . '-01');

        // Users & total_hours load
        $users = User::with(['availabilities' => function ($q) use ($selectedDate) {
            $q->where('date', $selectedDate);
        }])
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        foreach ($users as $user) {
            $user->total_hours = $this->calculateTotalHours($user->id, $selectedDate);
        }

        return view('backend.pages.shift.calendar', compact('users', 'selectedDate', 'selectedMonth'));
    }

    private function calculateTotalHours($employeeId, $selectedDate)
    {
        $total_hours = EmployeeAvailability::where('employee_id', $employeeId)
            ->whereMonth('date', Carbon::parse($selectedDate)->month)
            ->whereYear('date', Carbon::parse($selectedDate)->year)
            ->select(DB::raw('SUM(hours) as total'))
            ->first()
            ->total;

        // শুধু DB থেকে sum of saved hours
        return $total_hours ?? 0;
    }



    public function save(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $date = $request->input('date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $hours = $request->input('hours', 0);
        $preferredTime = $request->input('preferred_time', 'Any');
        $place = $request->input('place', null);
        $selectedMonth = $request->selected_month; // ✅ YYYY-MM ফরম্যাটে

        // year & month বের করা
        $year = Carbon::createFromFormat('Y-m', $selectedMonth)->year;
        $month = Carbon::createFromFormat('Y-m', $selectedMonth)->month;

        // Save or update
        $availability = EmployeeAvailability::updateOrCreate(
            [
                'employee_id' => $employeeId,
                'date' => $date,  // ensure this is the date user selected
            ],
            [
                'start_time' => $startTime,
                'end_time' => $endTime,
                'preferred_time' => $preferredTime,
                'hours' => $hours,
                'place' => $place,
                'year' => $year,
                'month' => $month,
            ]
        );


        // Recalculate total hours
        $totalHours = $this->calculateTotalHours($employeeId, $date);

        return response()->json([
            'message' => 'Shift saved successfully!',
            'hours' => $hours,
            'total_hours' => $totalHours,
            'start_time' => $availability->start_time,
            'end_time' => $availability->end_time,
            'user_start_time' => $availability->user_start_time,
            'user_end_time' => $availability->user_end_time,
            'place' => $availability->place,
            'year' => $year,
            'month' => $month,
            'success' => true
        ]);
    }
    public function delete(Request $request)
    {
        $av = EmployeeAvailability::where('employee_id', $request->employee_id)
            ->where('date', $request->date)
            ->first();

        if (!$av) {
            return response()->json(['success' => false, 'message' => 'No shift found']);
        }

        $av->delete();

        return response()->json(['success' => true, 'message' => 'Shift deleted successfully']);
    }


    public function view($employeeId)
    {
        $shifts = ShiftAssignment::where('employee_id', $employeeId)->get();

        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        $lastMonthHours = ShiftAssignment::where('employee_id', $employeeId)
            ->whereBetween('date', [$lastMonthStart, $lastMonthEnd])
            ->get()
            ->sum(function ($shift) {
                $start = Carbon::parse($shift->start_time);
                $end = Carbon::parse($shift->end_time);
                if ($end->lessThan($start)) {
                    $end->addDay();
                }
                return $end->floatDiffInHours($start);
            });

        $isFullTime = $lastMonthHours >= 160 ? 'Full Time' : 'Part Time';
        $note = EmployeeAvailability::where('employee_id', $employeeId)->latest()->value('note') ?? 'No note available';

        return view('backend.pages.shift.history', compact('shifts', 'isFullTime', 'lastMonthHours', 'note'));
    }


    public function ajaxLoad($date)
    {
        $selectedDate = $date;
        $users = User::with(['availabilities' => function ($q) use ($selectedDate) {
            $q->where('date', $selectedDate);
        }])
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();


        foreach ($users as $user) {
            $user->total_hours = $this->calculateTotalHours($user->id, $selectedDate);
        }

        return view('backend.pages.shift.partials.shift-table', compact('users', 'selectedDate'));
    }

    // use উপরে আছে: use Carbon\Carbon; use App\Models\User;

    public function shiftShow(Request $request)
    {
        $selectedMonth = $request->get('month', now()->format('Y-m'));
        $startOfMonth = \Carbon\Carbon::parse($selectedMonth . '-01');
        $daysInMonth = $startOfMonth->daysInMonth;

        $users = \App\Models\User::with(['availabilities' => function ($q) use ($startOfMonth) {
            $q->whereYear('date', $startOfMonth->year)
                ->whereMonth('date', $startOfMonth->month);
        }])->get();

        // Daily totals
        $dailyTotals = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = $startOfMonth->copy()->day($d)->toDateString();
            $dailyTotals[$d] = round(\App\Models\EmployeeAvailability::where('date', $date)->sum('hours'), 2);
        }

        // Total hours
        $totalHoursAllUsers = $users->sum(function ($user) {
            return $user->availabilities->sum('hours');
        });

        $compact = compact('users', 'selectedMonth', 'startOfMonth', 'daysInMonth', 'dailyTotals', 'totalHoursAllUsers');

        if ($request->ajax()) {
            return view('backend.pages.shift.partials.shift_over_view_table', $compact)->render();
        }

        return view('backend.pages.shift.shift_show', $compact);
    }

    public function employeeShifts(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = \Carbon\Carbon::parse($startDate)->addDays(6)->toDateString();

        $shifts = EmployeeAvailability::with(['employee', 'dayTask'])
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        // সাপ্তাহিক মোট ঘন্টা
        // $weeklyTotal = $shifts->flatten()->sum('hours');
        $weeklyTotal = $shifts->map(function ($dayShifts) {
            return $dayShifts->filter(function ($s) {
                return $s->employee_id == auth()->id() &&
                    ($s->hours ?? 0) > 0 &&
                    $s->start_time &&
                    $s->end_time &&
                    \Carbon\Carbon::parse($s->start_time)->format('H:i') != '00:00' &&
                    \Carbon\Carbon::parse($s->end_time)->format('H:i') != '00:00';
            })->sum('hours');
        })->sum();
        // সব মাসের জন্য সপ্তাহ ভাগ করা
        $monthStart = \Carbon\Carbon::parse(now()->startOfMonth());
        $weeks = [];
        for ($i = 0; $i < 6; $i++) {
            $weekStart = $monthStart->copy()->addDays($i * 7);
            $weekEnd = $weekStart->copy()->addDays(6);
            $weeks[] = [
                'start' => $weekStart->toDateString(),
                'end'   => $weekEnd->toDateString(),
                'label' => $weekStart->format('d M') . ' - ' . $weekEnd->format('d M'),
            ];
        }
        // মাসিক মোট ঘন্টা
        $monthEnd = $monthStart->copy()->endOfMonth();
        $monthlyTotal = EmployeeAvailability::whereBetween('date', [$monthStart, $monthEnd])->sum('hours');

        if ($request->ajax()) {
            return view('backend.pages.shift.partials.my_shift_table', compact('shifts', 'startDate', 'endDate', 'weeklyTotal', 'monthlyTotal'))->render();
        }

        return view('backend.pages.shift.my_shift', compact('shifts', 'startDate', 'endDate', 'weeks', 'weeklyTotal', 'monthlyTotal'));
    }
    //employee shift filter by month
    // public function employeeShiftsMonth(Request $request)
    // {
    //     $month = $request->get('month');
    //     $monthObj = Carbon::createFromFormat('Y-m', $month);

    //     // Week list generate
    //     $weeks = [];
    //     $monthStart = $monthObj->copy()->startOfMonth();

    //     for ($i = 0; $i < 5; $i++) {
    //         $weekStart = $monthStart->copy()->addDays($i * 7);
    //         $weekEnd   = $weekStart->copy()->addDays(6);
    //         $weeks[] = [
    //             'start' => $weekStart->toDateString(),
    //             'end'   => $weekEnd->toDateString(),
    //             'label' => $weekStart->format('d M') . ' - ' . $weekEnd->format('d M'),
    //         ];
    //     }

    //     // Default: first week load
    //     $startDate = $weeks[0]['start'];
    //     $endDate = Carbon::parse($startDate)->addDays(6)->toDateString();

    //     // get shifts for that week
    //     $shifts = EmployeeAvailability::with(['employee', 'dayTask'])
    //         ->whereBetween('date', [$startDate, $endDate])
    //         ->orderBy('date')
    //         ->get()
    //         ->groupBy('date');

    //     $weeklyTotal = $shifts->flatten()->sum('hours');

    //     $monthEnd = $monthObj->copy()->endOfMonth();
    //     $monthlyTotal = EmployeeAvailability::whereBetween('date', [$monthStart, $monthEnd])->sum('hours');

    //     return response()->json([
    //         // 'weeks_html' => view('backend.pages.shift.partials.my_shift_table', compact('weeks'))->render(),
    //         'table_html' => view('backend.pages.shift.partials.my_shift_table', compact('shifts', 'startDate', 'endDate', 'weeklyTotal', 'monthlyTotal'))->render(),
    //     ]);
    // }

    public function allShifts(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate   = \Carbon\Carbon::parse($startDate)->addDays(6)->toDateString();

        $placeFilter = $request->get('place'); // filter by place

        $query = EmployeeAvailability::with(['employee', 'dayTask'])
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date');

        if ($placeFilter) {
            $query->where('place', $placeFilter);
        }

        $shifts = $query->get()->groupBy('date');

        $weeklyTotal = $shifts->flatten()->sum('hours');

        $monthStart = \Carbon\Carbon::parse(now()->startOfMonth());
        $weeks = [];
        for ($i = 0; $i < 6; $i++) {
            $weekStart = $monthStart->copy()->addDays($i * 7);
            $weekEnd   = $weekStart->copy()->addDays(6);
            $weeks[] = [
                'start' => $weekStart->toDateString(),
                'end'   => $weekEnd->toDateString(),
                'label' => $weekStart->format('d M') . ' - ' . $weekEnd->format('d M'),
            ];
        }

        $monthEnd = $monthStart->copy()->endOfMonth();
        $monthlyTotal = EmployeeAvailability::whereBetween('date', [$monthStart, $monthEnd])->sum('hours');

        if ($request->ajax()) {
            return view('backend.pages.shift.partials.all_shift_table', compact(
                'shifts',
                'startDate',
                'endDate',
                'weeklyTotal',
                'monthlyTotal'
            ))->render();
        }

        return view('backend.pages.shift.all_shift', compact(
            'shifts',
            'startDate',
            'endDate',
            'weeks',
            'weeklyTotal',
            'monthlyTotal'
        ));
    }
}

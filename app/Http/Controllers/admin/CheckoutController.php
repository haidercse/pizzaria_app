<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeCheckout;
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
            if(isset($days[$date])){
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

        if($checkout){
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
}

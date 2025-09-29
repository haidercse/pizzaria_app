<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('date')->get();
        return view('backend.pages.holiday.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:holidays,date',
            'name' => 'required|string|max:255',
        ]);

        $holiday = Holiday::create($request->only('date', 'name'));

        return response()->json([
            'status' => 'success',
            'message' => 'Holiday added successfully',
            'holiday' => $holiday
        ]);
    }

    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'date' => 'required|date|unique:holidays,date,' . $holiday->id,
            'name' => 'required|string|max:255',
        ]);

        $holiday->update($request->only('date', 'name'));

        return response()->json([
            'status' => 'success',
            'message' => 'Holiday updated successfully',
            'holiday' => $holiday
        ]);
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Holiday deleted successfully'
        ]);
    }
}

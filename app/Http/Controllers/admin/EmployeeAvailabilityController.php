<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeAvailabilityController extends Controller
{
    /// List my availabilities
    public function index()
    {
        $availabilities = EmployeeAvailability::where('employee_id', Auth::id())
            ->orderBy('date', 'asc')
            ->get();

        return view('backend.pages.availability.index', compact('availabilities'));
    }
    // Show form
    public function create()
    {
        return view('backend.pages.availability.create');
    }

    // Store data
   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'date.*' => 'required|date',
        'preferred_time.*' => 'required|in:morning,evening,full_day,custom',
        'start_time.*' => 'nullable|required_if:preferred_time.*,custom|date_format:H:i',
        'end_time.*' => 'nullable|required_if:preferred_time.*,custom|date_format:H:i|after:start_time.*',
        'note.*' => 'nullable|string|max:255',
    ], [
        'date.*.required' => 'Date is required for all rows',
        'preferred_time.*.required' => 'Preferred time is required',
        'start_time.*.required_if' => 'Start time required for custom shift',
        'end_time.*.required_if' => 'End time required for custom shift',
        'end_time.*.after' => 'End time must be after Start time',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()->all()], 422);
    }

    foreach ($request->date as $i => $date) {
        EmployeeAvailability::create([
            'employee_id'   => auth()->id(),
            'date'          => $date,
            'preferred_time'=> $request->preferred_time[$i],
            'start_time'    => $request->start_time[$i] ?? null,
            'end_time'      => $request->end_time[$i] ?? null,
            'note'          => $request->note[$i] ?? null,
        ]);
    }

    return response()->json(['success' => '✅ Availability saved successfully!']);
}


    // Edit availability
    public function edit($id)
    {
        $availability = EmployeeAvailability::where('id', $id)
            ->where('employee_id', Auth::id())
            ->firstOrFail();
        $start_time = $availability->start_time ?? '';
        $end_time = $availability->end_time ?? '';

        return view('backend.pages.availability.edit', compact('availability', 'start_time', 'end_time'));
    }
    // Update availability
    public function update(Request $request, $id)
    {
        $availability = EmployeeAvailability::where('id', $id)
            ->where('employee_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'date' => 'required|date',
            'preferred_time' => 'required|in:morning,evening,full_day,custom',
            'start_time' => 'nullable|required_if:preferred_time,custom|date_format:H:i',
            'end_time' => 'nullable|required_if:preferred_time,custom|date_format:H:i|after:start_time',
            'note' => 'nullable|string|max:255',
        ]);

        $availability->update($validated);

        return redirect()->route('availability.index')->with('success', 'Availability updated successfully!');
    }
    // Delete availability
    public function destroy($id)
    {
        $availability = EmployeeAvailability::where('id', $id)
            ->where('employee_id', Auth::id())
            ->firstOrFail();

        $availability->delete();

        return redirect()->route('availability.index')->with('success', 'Availability deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DoughList;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        // সপ্তাহের সব doughlist আনো
        $doughs = DoughList::whereBetween('date', [$startOfWeek, $endOfWeek])->get();

        // (Mon → Sun)
        $weekDays = collect();
        $current = $startOfWeek->copy();
        while ($current->lte($endOfWeek)) {

            $dayData = $doughs->firstWhere('day', $current->format('l'));

            $weekDays->push([
                'day'   => $current->format('l'),    // Monday, Tuesday, ...
                'dough_litter' => $dayData->dough_litter ?? 0,
                'dough_total_weight' => $dayData->dough_total_weight ?? 0,
                'dough_num_of_cajas' => $dayData->dough_num_of_cajas ?? 0,
            ]);

            $current->addDay();
        }

        $events = Event::orderBy('event_date', 'asc')->get(); // Collection thakbe

        return view('backend.pages.dashboard.index', compact('weekDays', 'events'));
    }
}

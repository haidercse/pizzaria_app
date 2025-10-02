<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('event_date', 'asc')->get();
        return view('backend.pages.event.index', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_date' => 'required|array|min:1', // name must match Blade input
            'event_date.*' => 'required|date|distinct',
            'note' => 'required|array|min:1',
            'note.*' => 'required|string|max:255',
        ]);

        $added = [];
        foreach ($request->event_date as $key => $date) {
            $note = $request->note[$key];
            if (!Event::where('event_date', $date)->exists()) {
                $added[] = Event::create([
                    'event_date' => $date,
                    'note' => $note,
                ]);
            }
        }

        if (count($added) == 0) {
            return response()->json(['success' => false, 'message' => 'You already submitted in this date!']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Events added successfully!',
            'events' => $added
        ]);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'event_date.0' => 'required|date|unique:events,event_date,' . $id,
            'note.0' => 'required|string|max:255',
        ]);

        $event->update([
            'event_date' => $request->event_date[0],
            'note' => $request->note[0],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully!',
            'event' => [
                'id' => $event->id,
                'event_date' => $event->event_date,
                'note' => $event->note,
                'day' => \Carbon\Carbon::parse($event->event_date)->format('l'),
            ]
        ]);
    }


    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully!'
        ]);
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskCompletion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('day_time', '!=', 'daily')
            ->orderBy('id', 'DESC')
            ->get();
        return view('backend.pages.task.task_list', compact('tasks'));
    }

    public function dailyTasks(Request $request)
    {
        $query = Task::where('day_time', 'daily');

        if ($request->has('month') && $request->month != '') {
            $yearMonth = explode('-', $request->month); // format: YYYY-MM
            $year = $yearMonth[0];
            $month = $yearMonth[1];

            $query->whereYear('date', $year)
                ->whereMonth('date', $month);
        }

        $tasks = $query->orderBy('date', 'ASC')->get()->groupBy('date'); // group by date

        return view('backend.pages.task.daily_task', compact('tasks'));
    }



    public function create()
    {
        return view('backend.pages.task.task_create');
    }

    public function store(Request $request)
    {
        if ($request->day_time !== 'daily') {
            $request->validate([
                'name.*' => 'required|string|max:255',
            ]);

            foreach ($request->name as $taskName) {
                Task::create([
                    'name'       => $taskName,
                    'place'      => $request->place,
                    'day_time'   => $request->day_time,
                    'work_side'  => $request->work_side,
                    'date'       => now()->toDateString(),
                    'assign_by'  => auth()->id(),
                    'created_by' => auth()->id(),
                ]);
            }

            return redirect()->route('tasks.index')->with('success', 'Tasks added successfully!');
        } else {

            $request->validate([
                'daily_task_name.*' => 'required|string|max:255',
                'daily_task_date.*' => 'required|date',
            ]);

            foreach ($request->daily_task_name as $index => $taskName) {

                Task::create([
                    'name'       => $taskName,
                    'place'      => $request->place,
                    'day_time'   => $request->day_time,
                    'work_side'  => null,
                    'date'       => $request->daily_task_date[$index],
                    'assign_by'  => auth()->id(),
                    'created_by' => auth()->id(),
                ]);
            }

            return redirect()->route('tasks.daily')->with('success', 'Daily tasks added successfully!');
        }
    }


    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('backend.pages.task.task_edit', compact('task'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $task = Task::findOrFail($id);
        $task->update([
            'name'       => $request->name,
            'day_time'  => $request->day_time ?? '',
            'work_side' => $request->work_side ?? '',
            'date'       => now()->toDateString(),
            'assign_by'  => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully!');
    }

    public function taskOpening()
    {
        $date = now()->toDateString();
        $tasks = Task::with(['completions' => function ($q) use ($date) {
            $q->where('date', $date)->where('user_id', auth()->id());
        }])->get();
        return view('backend.pages.task.task_opening', compact('tasks', 'date'));
    }
    public function toggleComplete(Request $request, Task $task)
    {
        // $completion = TaskCompletion::firstOrCreate(
        //     ['task_id' => $task->id, 'user_id' => auth()->id(), 'date' => now()->toDateString()],
        //     ['completed' => false]
        // );

        // $completion->completed = !$completion->completed;
        // $completion->save();

        // return response()->json(['success' => true]);

        $date = $request->input('date', now()->toDateString());
        $code = $request->input('code', null);

        // build attributes for lookup
        $attributes = [
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'date'    => $date,
        ];

        // if code column exists and code was provided, include it
        if (Schema::hasColumn('task_completions', 'code') && $code) {
            $attributes['code'] = $code;
        }

        $completion = TaskCompletion::firstOrCreate($attributes, ['completed' => false]);

        // toggle
        $completion->completed = !$completion->completed;
        $completion->save();

        return response()->json([
            'success' => true,
            'completed' => $completion->completed,
        ]);
    }



    public function taskClosingIndex()
    {
        $date = now()->toDateString();
        $tasks = Task::with(['completions' => function ($q) use ($date) {
            $q->where('date', $date)->where('user_id', auth()->id());
        }])->where('day_time', 'evening')->get(); // Closing tasks

        return view('backend.pages.task.task_closing', compact('tasks', 'date'));
    }

    public function toggleClosingComplete(Request $request, Task $task)
    {
        $completion = TaskCompletion::firstOrCreate(
            ['task_id' => $task->id, 'user_id' => auth()->id(), 'date' => now()->toDateString()],
            ['completed' => false]
        );

        $completion->completed = !$completion->completed;
        $completion->save();

        return response()->json(['success' => true]);
    }

    // public function monthlyMatrix(Request $request)
    // {
    //     $month = $request->input('month', date('Y-m'));
    //     $start = $month . '-01';
    //     $end = date('Y-m-t', strtotime($start));


    //     $tasks = Task::all();


    //     $completions = \DB::table('task_completions')
    //         ->join('users', 'task_completions.user_id', '=', 'users.id')
    //         ->select('task_id', 'date', 'completed', 'users.name')
    //         ->whereBetween('date', [$start, $end])
    //         ->where('completed', true) 
    //         ->get()
    //         ->groupBy(fn($row) => $row->task_id . '_' . $row->date);


    //     $period = new \DatePeriod(
    //         new \DateTime($start),
    //         new \DateInterval('P1D'),
    //         (new \DateTime($end))->modify('+1 day')
    //     );

    //     return view('backend.pages.task.monthly_matrix', compact('tasks', 'completions', 'period', 'month'));
    // }

    public function monthlyMatrix(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        $start = \Carbon\Carbon::parse($month)->startOfMonth();
        $end = \Carbon\Carbon::parse($month)->endOfMonth();

        // মাসের সব তারিখ বের করব
        $dates = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates[] = $date->toDateString();
        }

        // মাসের সব task লোড করব, সাথে এই মাসের completions
        $tasks = Task::with(['completions' => function ($q) use ($start, $end) {
            $q->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->where('user_id', auth()->id());
        }])->orderBy('work_side')->get();

        return view('backend.pages.task.monthly_matrix', compact('tasks', 'month', 'dates'));
    }




    // Add new task via AJAX
    public function taskDailyStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'date' => 'required|date',
                'day_time' => 'required|string',
            ]);

            $task = Task::create([
                'name' => $validated['name'],
                'place' => 'nusle',
                'day_time' => $validated['day_time'],
                'work_side' => null,
                'date' => $validated['date'],
                'assign_by' => auth()->id(),
                'created_by' => auth()->id(),
            ]);

            return response()->json(['id' => $task->id, 'success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Update existing task via AJAX
    public function taskDailyUpdate(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'day_time' => 'required|string',
            ]);

            $task = Task::findOrFail($id);
            $task->update([
                'name' => $validated['name'],
                'day_time' => $validated['day_time'],
            ]);

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Delete task via AJAX
    public function taskDailyDestroy($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();
            return response()->json(['success' => true, 'message' => 'Task deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function dailyTaskListForUser()
    {
        $tasks = Task::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->orderBy('date')
            ->get()
            ->groupBy(function ($task) {
                return $task->date->format('d') . '_' . $task->day_time;
            });

        return view('backend.pages.task.daily_task_checklist', compact('tasks'));
    }
    public function toggleCompleteForUser(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->completed_at = $task->completed_at ? null : now();
        $task->save();

        return response()->json(['success' => true, 'completed' => $task->completed_at]);
    }
}

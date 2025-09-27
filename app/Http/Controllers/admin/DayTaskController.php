<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DayTask;
use Illuminate\Http\Request;

class DayTaskController extends Controller
{
    public function index()
    {
        $tasks = DayTask::all();
        return view('backend.pages.day_tasks.index', compact('tasks'));
    }

    // DayTaskController.php
    public function store(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required',
            'task_name'   => 'required|string|max:255',
        ]);

        $task = DayTask::create($request->only('day_of_week', 'task_name'));

        $tasks = DayTask::all();

        $table = view('backend.pages.day_tasks.partial.table', compact('tasks'))->render();

        return response()->json([
            'message' => 'Task added successfully!',
            'table'   => $table,
        ]);
    }


    public function update(Request $request, DayTask $day_task)
    {
        $request->validate([
            'day_of_week' => 'required',
            'task_name'   => 'required|string|max:255',
        ]);

        $day_task->update($request->only('day_of_week', 'task_name'));

        $tasks = DayTask::all();
        $table = view('backend.pages.day_tasks.partial.table', compact('tasks'))->render();

        return response()->json([
            'message' => 'Task updated successfully!',
            'table'   => $table,
        ]);
    }


    public function destroy(DayTask $day_task)
    {
        $day_task->delete();

        $tasks = DayTask::all();
        $table = view('backend.pages.day_tasks.partial.table', compact('tasks'))->render();

        return response()->json([
            'message' => 'Task deleted successfully!',
            'table'   => $table,
        ]);
    }
}

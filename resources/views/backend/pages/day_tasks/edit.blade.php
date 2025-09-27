@extends('backend.layouts.master')

@section('title', 'Edit Day Task')

@section('admin-content')
<div class="container">
    <h3>Edit Task</h3>
    <form action="{{ route('day_tasks.update', $dayTask->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Day of Week</label>
            <input type="text" name="day_of_week" value="{{ $dayTask->day_of_week }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Task Name</label>
            <input type="text" name="task_name" value="{{ $dayTask->task_name }}" class="form-control">
        </div>
        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection

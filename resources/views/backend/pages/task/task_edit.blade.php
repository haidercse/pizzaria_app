@extends('backend.layouts.master')
@section('title', 'Task Edit Page')
@section('page-title', 'Task Edit')
@section('breadcrumb-home_route', route('tasks.index'))
@section('breadcrumb-home_title', 'Task List')
@section('breadcrumb-current', 'Task Edit')

@section('admin-content')
    <div class="main-content">
        @include('backend.layouts.partials.message')

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mt-5">
                    <div class="card-body">
                        <h4 class="header-title text-center">Edit Task</h4>
                        <hr>
                        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label for="name" class="form-label fw-bold">Task Name</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ $task->name }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="day_time" class="form-label fw-bold">Select Day Time</label>
                                <select class="form-control" id="day_time" name="day_time" required>
                                    <option value="morning" {{ $task->day_time == 'morning' ? 'selected' : '' }}> Opening Task</option>
                                    <option value="evening" {{ $task->day_time == 'evening' ? 'selected' : '' }}> Closing Task</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="work_side" class="form-label fw-bold">Select Work Side</label>
                                <select class="form-control" id="work_side" name="work_side" required>
                                    <option value="front" {{ $task->work_side == 'front' ? 'selected' : '' }}>👤 Front Side</option>
                                    <option value="back" {{ $task->work_side == 'back' ? 'selected' : '' }}> Back Side</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Update Task</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

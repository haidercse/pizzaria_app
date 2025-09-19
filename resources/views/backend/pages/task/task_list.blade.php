@extends('backend.layouts.master')
@section('title', 'Task Create Page')
@section('page-title', 'Task Create')
@section('breadcrumb-home_route', route('tasks.create'))
@section('breadcrumb-home_title', 'Task Create')
@section('breadcrumb-current', 'Task Create')

@section('admin-content')
    <div class="main-content">
        @include('backend.layouts.partials.message')

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card mt-5">
                    <div class="card-body">
                        <h4 class="header-title text-center">Task List</h4>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Task Name</th>
                                        <th>Day Time</th>
                                        <th>Work Side</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tasks as $task)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $task->name }}</td>
                                            <td>
                                                @if ($task->day_time === 'morning')
                                                    <span class="badge bg-success"> Opening Task</span>
                                                @else
                                                    <span class="badge bg-danger"> Closing Task</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($task->work_side === 'front')
                                                    <span class="badge bg-primary"> Front Side</span>
                                                @else
                                                    <span class="badge bg-secondary"> Back Side</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="ti-pencil"></i>
                                                </a>
                                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this item?');">
                                                        <i class="ti-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No tasks found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

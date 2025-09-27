@extends('backend.layouts.master')
@section('title', 'Day Tasks')
@section('admin-content')
<div class="container">
    <h3 class="mb-3 text-center">Day Tasks</h3>
    
    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Add Task Button --}}
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
            <i class="bi bi-plus-circle"></i> Add New Task
        </button>
    </div>

    {{-- Task Table --}}
    <table class="table table-bordered table-hover align-middle text-center shadow-sm">
        <thead class="table-primary">
            <tr>
                <th style="width: 30%;">Day of Week</th>
                <th style="width: 40%;">Task</th>
                <th style="width: 30%;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>{{ $task->day_of_week }}</td>
                <td>{{ $task->task_name }}</td>
                <td>
                    <button class="btn btn-sm btn-warning edit-btn" 
                            data-id="{{ $task->id }}" 
                            data-day="{{ $task->day_of_week }}" 
                            data-task="{{ $task->task_name }}" 
                            data-bs-toggle="modal" data-bs-target="#editTaskModal">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <form action="{{ route('day_tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


{{-- Add Modal --}}
<div class="modal fade" id="addTaskModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('day_tasks.store') }}" method="POST">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add New Task</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Day of Week</label>
                <select name="day_of_week" class="form-select" required>
                    <option value="">-- Select Day --</option>
                    <option>Monday</option>
                    <option>Tuesday</option>
                    <option>Wednesday</option>
                    <option>Thursday</option>
                    <option>Friday</option>
                    <option>Saturday</option>
                    <option>Sunday</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Task Name</label>
                <input type="text" name="task_name" class="form-control" placeholder="e.g. Meat Slicing" required>
            </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>


{{-- Edit Modal --}}
<div class="modal fade" id="editTaskModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editTaskForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header bg-warning">
          <h5 class="modal-title">Edit Task</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Day of Week</label>
                <select name="day_of_week" id="edit_day_of_week" class="form-select" required>
                    <option>Monday</option>
                    <option>Tuesday</option>
                    <option>Wednesday</option>
                    <option>Thursday</option>
                    <option>Friday</option>
                    <option>Saturday</option>
                    <option>Sunday</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Task Name</label>
                <input type="text" name="task_name" id="edit_task_name" class="form-control" required>
            </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection


@push('scripts')
<script>
    // Edit Button Click
    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        let day = $(this).data('day');
        let task = $(this).data('task');

        $('#edit_day_of_week').val(day);
        $('#edit_task_name').val(task);

        // Dynamic action url
        $('#editTaskForm').attr('action', '/day_tasks/' + id);
    });
</script>
@endpush

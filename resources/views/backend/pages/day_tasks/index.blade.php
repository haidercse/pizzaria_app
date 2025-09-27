@extends('backend.layouts.master')
@section('title', 'Day Tasks')
@section('admin-content')
<div class="container">
    <h3 class="mb-3 text-center">Day Tasks</h3>

    {{-- Success/Error Message --}}
    <div id="alertBox"></div>

    {{-- Add Task Button --}}
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addTaskModal">
            <i class="bi bi-plus-circle"></i> Add New Task
        </button>
    </div>

    {{-- Task Table --}}
    <div id="taskTable">
        @include('backend.pages.day_tasks.partial.table', ['tasks' => $tasks])
    </div>
</div>


{{-- Add Modal --}}
<div class="modal fade" id="addTaskModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addTaskForm">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add New Task</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Day of Week</label>
                <select name="day_of_week" class="form-control" required>
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
          <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
      <form id="editTaskForm">
        @csrf
        @method('PUT')
        <div class="modal-header bg-warning">
          <h5 class="modal-title">Edit Task</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="edit_id" name="id">
            <div class="mb-3">
                <label class="form-label">Day of Week</label>
                <select name="day_of_week" id="edit_day_of_week" class="form-control" required>
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
          <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


@push('scripts')
<script>
    // ===== Add Task (AJAX) =====
    $('#addTaskForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('day_tasks.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                $('#addTaskModal').modal('hide');
                $('#taskTable').html(response.table);
                showAlert('success', response.message);
                $('#addTaskForm')[0].reset();
            },
            error: function(xhr) {
                showAlert('danger', 'Something went wrong!');
            }
        });
    });

    // ===== Edit Button Fill Modal =====
    $(document).on('click', '.edit-btn', function() {
        $('#edit_id').val($(this).data('id'));
        $('#edit_day_of_week').val($(this).data('day'));
        $('#edit_task_name').val($(this).data('task'));
        $('#editTaskModal').modal('show');
    });

    // ===== Update Task (AJAX) =====
    $('#editTaskForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#edit_id').val();
        $.ajax({
            url: "/day_tasks/" + id,
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                $('#editTaskModal').modal('hide');
                $('#taskTable').html(response.table);
                showAlert('success', response.message);
            },
            error: function(xhr) {
                showAlert('danger', 'Something went wrong!');
            }
        });
    });

    // ===== Delete Task (AJAX) =====
    $(document).on('submit', '.deleteForm', function(e) {
        e.preventDefault();
        if(!confirm('Are you sure?')) return;

        let form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: form.serialize(),
            success: function(response) {
                $('#taskTable').html(response.table);
                showAlert('success', response.message);
            },
            error: function(xhr) {
                showAlert('danger', 'Something went wrong!');
            }
        });
    });

    // ===== Alert Show Function =====
    function showAlert(type, message) {
        $('#alertBox').html(`
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        `);
    }
</script>
@endpush

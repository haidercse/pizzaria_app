@extends('backend.layouts.master')
@section('title', 'Daily Task Checklist')
@section('page-title', 'Daily Task Checklist')
@section('breadcrumb-home_route', route('tasks.opening.index'))
@section('breadcrumb-home_title', 'Daily Task Checklist')
@section('breadcrumb-current', 'Daily Task Checklist')

@section('admin-content')
<div class="main-content">
    <h3 class="text-center mb-4">
        Opening Checklist - {{ \Carbon\Carbon::now()->format('d F Y') }}
    </h3>

    <div class="row">
        {{-- Front Side --}}
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white fw-bold">
                    Front Side
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:50px">#</th>
                                    <th>Task</th>
                                    <th style="width:80px">Done</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks->where('work_side', 'front') as $task)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $task->name }}</td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input task-check"
                                                data-id="{{ $task->id }}"
                                                {{ $task->completions->first()?->completed ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Back Side --}}
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    Back Side
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:50px">#</th>
                                    <th>Task</th>
                                    <th style="width:80px">Done</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks->where('work_side', 'back') as $task)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $task->name }}</td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input task-check"
                                                data-id="{{ $task->id }}"
                                                {{ $task->completions->first()?->completed ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.task-check').forEach(chk => {
    chk.addEventListener('change', function() {
        fetch(`/tasks/${this.dataset.id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
    });
});
</script>
@endsection

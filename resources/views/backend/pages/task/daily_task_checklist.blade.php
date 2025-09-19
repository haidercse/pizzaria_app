@extends('backend.layouts.master')
@section('title', 'Daily Task Checklist')
@section('page-title', 'Daily Task Checklist')

@section('admin-content')
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>✅ Daily Task Checklist</h3>
            <form method="GET" action="{{ route('tasks.checklist') }}" class="d-flex gap-2">
                <input type="month" class="form-control form-control-sm" name="month" value="{{ request('month') }}">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            </form>
        </div>

        @forelse($tasks as $date => $taskList)
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between">
                    <strong>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</strong>
                    <span class="badge bg-info text-dark">Daily</span>
                </div>
                <div class="card-body">
                    @foreach ($taskList as $task)
                        @php
                            $subTasks = $task->sub_tasks;
                            $doneStates = $task->is_done ?? [];
                        @endphp

                        @foreach ($subTasks as $i => $subTask)
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input checklist" data-id="{{ $task->id }}"
                                    data-index="{{ $i }}"
                                    {{ !empty($doneStates[$i]) && $doneStates[$i] ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    {{ $subTask }}
                                </label>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        @empty
            <div class="alert alert-warning">No tasks found for this month.</div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.checklist').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    let taskId = this.dataset.id;
                    let index = this.dataset.index;
                    let isDone = this.checked ? 1 : 0;

                    fetch("{{ route('tasks.checklist.update') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                id: taskId,
                                index: index,
                                is_done: isDone
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            console.log("Server response:", data);
                        });
                });
            });
        });
    </script>
@endpush


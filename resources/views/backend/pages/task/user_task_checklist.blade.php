@extends('backend.layouts.master')
@section('title', 'My Daily Checklist')

@section('admin-content')
    <div class="container mt-3">
        <h4 class="text-center mb-3">📅 My Tasks for {{ \Carbon\Carbon::parse($today)->format('d M Y') }}</h4>

        @if ($tasks->isEmpty())
            <div class="alert alert-info text-center">No tasks for today 🎉</div>
        @else
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @foreach ($tasks as $task)
                                @php
                                    $subTasks = $task->sub_tasks;
                                    $doneStates = $task->is_done ?? [];
                                @endphp

                                @foreach ($subTasks as $i => $subTask)
                                    <div
                                        class="form-check mb-2 p-2 border rounded d-flex align-items-center justify-content-between">
                                        <label class="form-check-label flex-grow-1 d-flex align-items-center">
                                            <input type="checkbox" class="form-check-input me-2 checklist"
                                                data-id="{{ $task->id }}" data-index="{{ $i }}"
                                                {{ !empty($doneStates[$i]) && $doneStates[$i] ? 'checked' : '' }}>
                                            <span
                                                class="{{ !empty($doneStates[$i]) && $doneStates[$i] ? 'text-decoration-line-through text-muted' : '' }}">
                                                {{ trim($subTask) }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.checklist').forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        let taskId = this.dataset.id;
                        let index = this.dataset.index;
                        let isDone = this.checked ? 1 : 0;
                        let label = this.closest('.form-check').querySelector('span');

                        fetch("{{ route('user.tasks.checklist.update') }}", {
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
                                if (data.success) {
                                    if (isDone) {
                                        label.classList.add("text-decoration-line-through",
                                            "text-muted");
                                    } else {
                                        label.classList.remove("text-decoration-line-through",
                                            "text-muted");
                                    }
                                }
                            });
                    });
                });
            });
        </script>
    @endpush
@endsection

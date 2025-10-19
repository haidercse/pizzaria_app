@if ($tasks->isEmpty())
    <div class="alert alert-info text-center">No tasks found 🎉</div>
@else
    @foreach ($tasks as $date => $taskList)
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
                            <input type="checkbox" class="form-check-input checklist"
                                   data-id="{{ $task->id }}" data-index="{{ $i }}"
                                   {{ !empty($doneStates[$i]) && $doneStates[$i] ? 'checked' : '' }}>
                            <label class="form-check-label">
                                {{ $subTask }}
                            </label>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    @endforeach
@endif

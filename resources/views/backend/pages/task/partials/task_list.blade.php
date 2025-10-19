@if ($tasks->isEmpty())
    <div class="alert alert-info text-center">No tasks found 🎉</div>
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


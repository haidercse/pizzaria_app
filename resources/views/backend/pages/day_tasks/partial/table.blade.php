<table class="table table-bordered table-hover align-middle text-center shadow-sm">
    <thead class="table-primary">
        <tr>
            <th style="width: 30%;">Day of Week</th>
            <th style="width: 40%;">Task</th>
            <th style="width: 30%;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tasks as $task)
        <tr>
            <td>{{ $task->day_of_week }}</td>
            <td>{{ $task->task_name }}</td>
            <td>
                <button class="btn btn-sm btn-warning edit-btn"
                        data-id="{{ $task->id }}"
                        data-day="{{ $task->day_of_week }}"
                        data-task="{{ $task->task_name }}">
                    <i class="bi bi-pencil"></i> Edit
                </button>
                <form action="{{ route('day_tasks.destroy', $task->id) }}" method="POST" class="deleteForm d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-muted text-center">No tasks available.</td>
        </tr>
        @endforelse
    </tbody>
</table>

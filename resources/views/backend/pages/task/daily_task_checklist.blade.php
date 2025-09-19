<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cleaning Schedule - {{ now()->format('F Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .calendar { width: 100%; border-collapse: collapse; }
        .calendar th, .calendar td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .calendar th { background-color: #f2f2f2; }
        .calendar .day-header { background-color: #e0e0e0; font-weight: bold; }
        .task-item { margin-bottom: 4px; }
        .task-item input[type="checkbox"] { margin-right: 5px; }
        .task-item.completed { text-decoration: line-through; color: #888; }
    </style>
</head>
<body>
    <h1>Cleaning Schedule - {{ now()->format('F Y') }}</h1>
    <table class="calendar">
        <thead>
            <tr>
                <th>Calendar Month</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th>Saturday</th>
                <th>Sunday</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @php
                    $days = range(1, now()->daysInMonth);
                    $dayOfWeek = now()->startOfMonth()->dayOfWeek;
                    $tasksByDay = $tasks->mapWithKeys(function ($tasks, $key) {
                        [$day, $time] = explode('_', $key);
                        return [$day => ['day' => $day, 'time' => $time, 'tasks' => $tasks]];
                    })->groupBy('day');
                @endphp
                @foreach(array_chunk($days, 7) as $week)
                    <tr>
                        @foreach($week as $day)
                            <td class="day-header">{{ $day }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($week as $day)
                            <td>
                                @if (isset($tasksByDay[$day]))
                                    @foreach($tasksByDay[$day] as $group)
                                        @foreach($group['tasks'] as $task)
                                            <div class="task-item {{ $task->completed_at ? 'completed' : '' }}">
                                                <input type="checkbox" data-id="{{ $task->id }}" {{ $task->completed_at ? 'checked' : '' }}>
                                                {{ $task->name }} ({{ $group['time'] }})
                                            </div>
                                        @endforeach
                                    @endforeach
                                @else
                                    &nbsp;
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tr>
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.task-item input[type="checkbox"]').on('change', function() {
                const $checkbox = $(this);
                const taskId = $checkbox.data('id');
                $.post(`/tasks/user/${taskId}/toggle-complete`, { _token: '{{ csrf_token() }}' })
                    .done(function(response) {
                        $checkbox.parent().toggleClass('completed', response.completed);
                    })
                    .fail(function() {
                        alert('Error updating task status.');
                        $checkbox.prop('checked', !$checkbox.prop('checked'));
                    });
            });
        });
    </script>
</body>
</html>
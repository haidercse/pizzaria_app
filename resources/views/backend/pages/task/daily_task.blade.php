@extends('backend.layouts.master')
@section('title', 'Daily Tasks')
@section('admin-content')

    <div class="container mt-4">
        <h3 class="mb-3">Daily Tasks</h3>

        <form method="GET" class="mb-3 d-flex gap-2 align-items-center flex-wrap">
            <div>
                <label>Select Month</label>
                <input type="month" name="month" class="form-control" value="{{ request('month') }}">
            </div>

            <div>
                <label>Select Place</label>
                <select name="place" class="form-select form-control">
                    <option value="">-- All Places --</option>
                    <option value="nusle" {{ request('place') == 'nusle' ? 'selected' : '' }}>Nusle</option>
                    <option value="andel" {{ request('place') == 'andel' ? 'selected' : '' }}>Andel</option>
                </select>
            </div>

            <div class="align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>

        @forelse($tasks as $date => $dailyTasks)
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <strong>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</strong>
                    <span class="badge bg-warning text-dark">Daily</span>
                </div>

                <ul class="list-group list-group-flush" id="task-list-{{ $date }}">
                    @foreach ($dailyTasks as $task)
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                            id="task-{{ $task->id }}">
                            <input type="text" class="form-control me-2 task-name" value="{{ $task->name }}">
                            <div>
                                <button class="btn btn-sm btn-success me-2 save-task"
                                    data-id="{{ $task->id }}">Save</button>
                                <button class="btn btn-sm btn-danger delete-task"
                                    data-id="{{ $task->id }}">Delete</button>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="card-footer">
                    <button type="button" class="btn btn-primary btn-sm" onclick="addTask('{{ $date }}')">Add
                        Task</button>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No tasks found for this month and place.</div>
        @endforelse
    </div>

    <script>
        function addTask(date) {
            const list = document.getElementById(`task-list-${date}`);
            const li = document.createElement('li');
            li.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');

            li.innerHTML = `
        <input type="text" class="form-control me-2 task-name" placeholder="Enter task name">
        <div>
            <button class="btn btn-sm btn-success me-2 save-task-new" data-date="${date}">Save</button>
            <button class="btn btn-sm btn-danger remove-new-task">Delete</button>
        </div>
    `;

            list.appendChild(li);

            li.querySelector('.remove-new-task').addEventListener('click', () => li.remove());

            li.querySelector('.save-task-new').addEventListener('click', function() {
                const name = li.querySelector('.task-name').value.trim();
                if (!name) {
                    alert('Task name required');
                    return;
                }

                const date = this.dataset.date;
                const place = document.querySelector('select[name="place"]').value;

                fetch("{{ route('tasks.daily.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name,
                            date,
                            day_time: 'daily',
                            place
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                        if (data.errors) {
                            alert(Object.values(data.errors).flat().join("\n"));
                            return;
                        }

                        li.id = `task-${data.id}`;
                        li.querySelector('.save-task-new').remove();
                        li.querySelector('.remove-new-task').classList.remove('remove-new-task');
                        li.querySelector('div').innerHTML = `
                <button class="btn btn-sm btn-success me-2 save-task" data-id="${data.id}">Save</button>
                <button class="btn btn-sm btn-danger delete-task" data-id="${data.id}">Delete</button>
            `;
                        attachTaskEvents(li);
                        alert('Task added successfully!');
                    })
                    .catch(err => alert('Unexpected error. Check console.'));
            });
        }

        function attachTaskEvents(li) {
            li.querySelector('.save-task')?.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = li.querySelector('.task-name').value.trim();
                fetch(`{{ url('tasks/daily-tasks') }}/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name,
                            day_time: 'daily'
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) alert('Task updated successfully!');
                        else if (data.errors) alert(Object.values(data.errors).flat().join("\n"));
                        else if (data.error) alert(data.error);
                    });
            });

            li.querySelector('.delete-task')?.addEventListener('click', function() {
                if (!confirm('Are you sure?')) return;
                const id = this.dataset.id;
                fetch(`{{ url('tasks/daily-tasks') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            li.remove();
                            alert(data.message);
                        } else if (data.error) {
                            alert(data.error);
                        }
                    });
            });
        }

        document.querySelectorAll('li[id^="task-"]').forEach(li => attachTaskEvents(li));
    </script>

@endsection

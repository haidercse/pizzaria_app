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

    <!-- 🔽 Place Filter -->
    <div class="text-center mb-4">
        <select id="placeFilter" class="form-select w-auto d-inline-block">
            <option value="">-- Select Place --</option>
            <option value="nusle">Nusle</option>
            <option value="andel">Andel</option>
        </select>
    </div>

    <!-- 🔽 Task Container -->
    <div id="taskContainer">
        @include('backend.pages.task.partials.daily_task_list', ['tasks' => $tasks])
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const placeFilter = document.getElementById('placeFilter');
    const taskContainer = document.getElementById('taskContainer');

    // 🔹 Filter by Place (AJAX)
    placeFilter.addEventListener('change', function() {
        let place = this.value;
        let month = "{{ request('month') ?? '' }}";

        taskContainer.innerHTML = `<div class="text-center py-3">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading tasks...</p>
        </div>`;

        fetch("{{ route('tasks.checklist.filter') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ place: place, month: month })
        })
        .then(res => res.text())
        .then(html => {
            taskContainer.innerHTML = html;
        })
        .catch(() => {
            taskContainer.innerHTML = `<p class="text-danger">Something went wrong 😢</p>`;
        });
    });

    // 🔹 Update Checklist (AJAX)
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('checklist')) {
            let checkbox = e.target;
            let taskId = checkbox.dataset.id;
            let index = checkbox.dataset.index;
            let isChecked = checkbox.checked ? 1 : 0;

            fetch("{{ route('tasks.checklist.update') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    id: taskId,
                    index: index,
                    is_done: isChecked
                })
            })
            .then(res => res.json())
            .then(data => {
                console.log('Checklist updated:', data);
            })
            .catch(err => console.error(err));
        }
    });
});
</script>
@endpush

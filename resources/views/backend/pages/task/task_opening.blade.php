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

    <!-- 🔽 Place Filter -->
    <div class="text-center mb-4">
        <select id="placeFilter" class="form-select w-auto d-inline-block">
            <option value="">-- Select Place --</option>
            <option value="nusle">Nusle</option>
            <option value="andel">Andel</option>
        </select>
    </div>

    <!-- 🔽 Task Content Area -->
    <div id="taskContainer">
        @if ($tasks->count() > 0)
            @include('backend.pages.task.partials.task_table', ['tasks' => $tasks])
        @else
            <div class="text-center text-muted py-4">
                Please select a place to view tasks.
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // 🔹 Place filter change
    const placeFilter = document.getElementById('placeFilter');
    placeFilter.addEventListener('change', function() {
        let place = this.value;

        fetch("{{ route('tasks.filter') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ place: place })
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('taskContainer').innerHTML = html;
        })
        .catch(err => console.error('Filter Error:', err));
    });

    // 🔹 Event delegation for dynamically loaded checkboxes
    document.getElementById('taskContainer').addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('task-check')) {
            const taskId = e.target.dataset.id;
            const isChecked = e.target.checked ? 1 : 0;

            fetch(`/tasks/${taskId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    date: '{{ \Carbon\Carbon::now()->toDateString() }}'
                })
            })
            .then(res => res.json())
            .then(data => {
                console.log('Task toggled:', data);
            })
            .catch(err => console.error('Toggle Error:', err));
        }
    });

});
</script>
@endpush

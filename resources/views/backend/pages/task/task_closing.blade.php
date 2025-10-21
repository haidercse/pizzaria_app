@extends('backend.layouts.master')
@section('title', 'Daily Task Closing Checklist')
@section('page-title', 'Daily Task Closing Checklist')
@section('breadcrumb-home_route', route('tasks.opening.index'))
@section('breadcrumb-home_title', 'Daily Task Checklist')
@section('breadcrumb-current', 'Closing Checklist')

@section('admin-content')
    <div class="main-content">
        <h3 class="text-center mb-4">
            Closing Checklist - {{ \Carbon\Carbon::now()->format('d F Y') }}
        </h3>
        <div class="text-center mb-4">
            <select id="placeFilter" class="form-select w-auto d-inline-block">
                <option value="">-- Select Place --</option>
                <option value="nusle">Nusle</option>
                <option value="andel">Andel</option>
            </select>
            <input type="hidden" id="day_time" value="evening">
        </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const placeFilter = document.getElementById('placeFilter');
            placeFilter.addEventListener('change', function() {
                let place = this.value;
                let day_time = document.getElementById('day_time').value;
                fetch("{{ route('tasks.filter') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            place: place,
                            day_time: day_time
                        })
                    })
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('taskContainer').innerHTML = html;
                    })
                    .catch(err => console.error('Filter Error:', err));
            });

            document.querySelectorAll('.task-check').forEach(chk => {
                chk.addEventListener('change', function() {
                    fetch(`/tasks/${this.dataset.id}/toggle-closing`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                });
            });
        });
    </script>
@endsection

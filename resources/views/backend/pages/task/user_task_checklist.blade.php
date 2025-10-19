@extends('backend.layouts.master')
@section('title', 'My Daily Checklist')

@section('admin-content')
    <div class="container mt-3">
        <h4 class="text-center mb-3">📅 My Tasks for {{ \Carbon\Carbon::parse($today)->format('d M Y') }}</h4>

        <!-- 🔽 Dropdown -->
        <div class="text-center mb-4">
            <select id="placeFilter" class="form-select w-auto d-inline-block">
                <option value="">-- Select Place --</option>
                <option value="nusle">Nusle</option>
                <option value="andel">Andel</option>
            </select>
        </div>

        <!-- 🔽 Task List Container -->
        <div id="taskContainer" class="text-center text-muted">
            <p>Please select a place to view tasks 🧭</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const placeFilter = document.getElementById('placeFilter');
            const taskContainer = document.getElementById('taskContainer');

            // 🔹 Place Filter Change
            placeFilter.addEventListener('change', function() {
                let place = this.value;

                if (place === '') {
                    taskContainer.innerHTML =
                        `<p class="text-muted">Please select a place to view tasks 🧭</p>`;
                    return;
                }

                taskContainer.innerHTML = `<div class="text-center py-3">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading tasks...</p>
        </div>`;

                fetch("{{ route('user.tasks.filter') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            place: place
                        })
                    })
                    .then(res => res.text())
                    .then(html => {
                        taskContainer.innerHTML = html;
                    })
                    .catch(() => {
                        taskContainer.innerHTML = `<p class="text-danger">Something went wrong 😢</p>`;
                    });
            });

            // 🔹 Event delegation for dynamically loaded checkboxes
            document.addEventListener('change', function(e) {
                if (e.target && e.target.classList.contains('checklist')) {
                    let checkbox = e.target;
                    let taskId = checkbox.dataset.id;
                    let index = checkbox.dataset.index;
                    let isChecked = checkbox.checked ? 1 : 0;

                    // ✅ AJAX request to updateUserChecklist()
                    fetch("{{ route('user.tasks.checklist.update') }}", {
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
                            if (data.success) {
                                console.log('Checklist updated successfully!');
                            } else {
                                console.error('Failed to update checklist.');
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                        });
                }
            });
        });
    </script>
@endpush

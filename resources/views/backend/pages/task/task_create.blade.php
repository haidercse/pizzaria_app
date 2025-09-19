@extends('backend.layouts.master')
@section('title', 'Task Create Page')
@section('page-title', 'Task Create')
@section('breadcrumb-home_route', route('tasks.create'))
@section('breadcrumb-home_title', 'Task Create')
@section('breadcrumb-current', 'Task Create')

@section('admin-content')
    <div class="main-content">
        @include('backend.layouts.partials.message')

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mt-5">
                    <div class="card-body">
                        <h4 class="header-title text-center">Assign Task</h4>
                        <hr>
                        <form action="{{ route('tasks.store') }}" method="POST">
                            @csrf
                            {{-- Where --}}
                            <div class="form-group mb-3">
                                <label for="where" class="form-label fw-bold">Select Where</label>
                                <select class="form-control" id="place" name="place" required>
                                    <option value="nusle">Nusle</option>
                                    <option value="andel">Andel</option>
                                </select>
                            </div>

                            {{-- Day Time --}}
                            <div class="form-group mb-3">
                                <label for="day_time" class="form-label fw-bold">Select Day Time</label>
                                <select class="form-control" id="day_time" name="day_time" required>
                                    <option value="morning">Opening Task</option>
                                    <option value="evening">Closing Task</option>
                                    <option value="daily">Daily Task</option>
                                </select>
                            </div>

                            {{-- Work Side --}}
                            <div class="form-group mb-3" id="work-side-wrapper">
                                <label for="work_side" class="form-label fw-bold">Select Work Side</label>
                                <select class="form-control" id="work_side" name="work_side">
                                    <option value="">Select Work Side</option>
                                    <option value="front">Front Side</option>
                                    <option value="back">Back Side</option>
                                </select>
                            </div>

                            <hr class="my-4"> {{-- divider --}}

                            {{-- Normal Task List --}}
                            <div class="form-group mb-3" id="normal-task-wrapper">
                                <label class="form-label fw-bold">Task List</label>
                                <div id="task-wrapper">
                                    <div class="task-field mb-2 d-flex">
                                        <input type="text" name="name[]" class="form-control"
                                            placeholder="Enter task name">
                                        <button type="button" class="btn btn-success ms-2 add-task">+</button>
                                        <button type="button" class="btn btn-danger ms-2 remove-task">-</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Daily Task (Month + auto list) --}}
                            <div class="form-group mb-3" id="month-wrapper" style="display:none;">
                                <label for="month_select" class="form-label fw-bold">Select Month</label>
                                <input type="month" class="form-control" id="month_select" name="month">
                            </div>

                            <div class="form-group mb-3" id="daily-task-wrapper" style="display:none;">
                                <label class="form-label fw-bold">Daily Task List</label>
                                <div id="daily-task-fields"></div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Save Tasks</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dayTimeSelect = document.getElementById('day_time');
            const workSideSelect = document.getElementById('work_side');

            const normalTaskWrapper = document.getElementById('normal-task-wrapper');
            const taskWrapper = document.getElementById('task-wrapper');

            const monthWrapper = document.getElementById('month-wrapper');
            const monthSelect = document.getElementById('month_select');
            const dailyTaskWrapper = document.getElementById('daily-task-wrapper');
            const dailyTaskFields = document.getElementById('daily-task-fields');

            // Function for normal task input
            function createNewTaskField() {
                return `
                <div class="task-field mb-2 d-flex">
                    <input type="text" name="name[]" class="form-control" placeholder="Enter task name">
                    <button type="button" class="btn btn-success ms-2 add-task">+</button>
                    <button type="button" class="btn btn-danger ms-2 remove-task">-</button>
                </div>
            `;
            }

            // Add/remove for normal tasks
            taskWrapper.addEventListener('click', function(event) {
                if (event.target.classList.contains('add-task')) {
                    const newTaskField = document.createElement('div');
                    newTaskField.innerHTML = createNewTaskField().trim();
                    taskWrapper.appendChild(newTaskField.firstChild);
                }
                if (event.target.classList.contains('remove-task')) {
                    if (taskWrapper.querySelectorAll('.task-field').length > 1) {
                        event.target.parentElement.remove();
                    }
                }
            });

            // Daily task row (date + textarea + add/remove)
            // Daily task row (date + day + textarea + add/remove)
            function createDailyTaskField(date) {
                const d = new Date(date);
                const dayName = d.toLocaleDateString('en-US', {
                    weekday: 'long'
                });

                return `
        <div class="task-field mb-2 d-flex align-items-start">
            <input type="text" name="daily_task_date[]" class="form-control me-2" value="${date}" readonly style="max-width:140px;">
            <input type="text" class="form-control me-2" value="${dayName}" readonly style="max-width:140px; background:#f8f9fa;">
            <textarea name="daily_task_name[]" class="form-control me-2" placeholder="Enter tasks for ${date} (${dayName})" required></textarea>
            <button type="button" class="btn btn-success me-2 add-task">+</button>
            <button type="button" class="btn btn-danger remove-task">-</button>
        </div>
    `;
            }



            function generateMonthTasks(year, month) {
                dailyTaskFields.innerHTML = "";
                const daysInMonth = new Date(year, month, 0).getDate();
                for (let day = 1; day <= daysInMonth; day++) {
                    const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const newTaskField = document.createElement('div');
                    newTaskField.innerHTML = createDailyTaskField(dateStr).trim();
                    dailyTaskFields.appendChild(newTaskField.firstChild);
                }
            }

            // Add/remove for daily tasks
            dailyTaskFields.addEventListener('click', function(event) {
                if (event.target.classList.contains('add-task')) {
                    const parent = event.target.closest('.task-field');
                    const clone = parent.cloneNode(true);
                    dailyTaskFields.insertBefore(clone, parent.nextSibling);
                }
                if (event.target.classList.contains('remove-task')) {
                    if (dailyTaskFields.querySelectorAll('.task-field').length > 1) {
                        event.target.closest('.task-field').remove();
                    }
                }
            });

            // Handle day_time change
            dayTimeSelect.addEventListener('change', function() {
                if (this.value === 'daily') {
                    workSideSelect.disabled = true;
                    workSideSelect.value = '';

                    // show daily task + month
                    monthWrapper.style.display = 'block';
                    dailyTaskWrapper.style.display = 'block';

                    // hide normal task
                    normalTaskWrapper.style.display = 'none';
                } else {
                    workSideSelect.disabled = false;

                    // show normal task
                    normalTaskWrapper.style.display = 'block';

                    // hide daily task
                    monthWrapper.style.display = 'none';
                    dailyTaskWrapper.style.display = 'none';
                    dailyTaskFields.innerHTML = "";
                }
            });

            // Month select → generate days
            monthSelect.addEventListener('change', function() {
                const [year, month] = this.value.split("-");
                generateMonthTasks(parseInt(year), parseInt(month));
            });
        });
    </script>
@endsection

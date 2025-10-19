@extends('backend.layouts.master')
@section('title', 'Monthly Task Matrix')
@section('page-title', 'Monthly Task Matrix')
@section('breadcrumb-home_route', route('tasks.monthly.matrix'))
@section('breadcrumb-home_title', 'Monthly Task Matrix')
@section('breadcrumb-current', 'Monthly Task Matrix')

@section('admin-content')
    <div class="main-content">
        <h3 class="text-center mb-4">Monthly Task Matrix - {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h3>

        <!-- 🔹 Place Filter -->
        <div class="text-center mb-3">
            <select id="placeFilter" class="form-select w-auto d-inline-block">
                <option value="">-- Select Place --</option>
                <option value="nusle" {{ request('place') == 'nusle' ? 'selected' : '' }}>Nusle</option>
                <option value="andel" {{ request('place') == 'andel' ? 'selected' : '' }}>Andel</option>
            </select>
        </div>

        <!-- 🔹 Month Filter Form -->
        <form method="GET" action="{{ route('tasks.monthly.matrix') }}" class="mb-3 d-inline-block">
            <label>Select Month: </label>
            <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()">
            <input type="hidden" name="place" id="monthPlaceInput" value="{{ request('place') }}">
        </form>

        <!-- 🔹 Task Table Container -->
        <div id="taskMatrixContainer">
            @include('backend.pages.task.partials.monthly_task_table', [
                'tasks' => $tasks,
                'dates' => $dates,
            ])
        </div>
    </div>

    {{-- Ajax Scripts --}}
    @push('scripts')
        <script>
            $(document).ready(function() {

                // 🔹 Place Filter AJAX
                $('#placeFilter').change(function() {
                    let place = $(this).val();
                    let month = $("input[name='month']").val(); // get selected month
                    $('#monthPlaceInput').val(place);

                    $.ajax({
                        url: "{{ route('tasks.monthly.matrix') }}",
                        type: "GET",
                        data: {
                            month: month,
                            place: place
                        },
                        success: function(res) {
                            $('#taskMatrixContainer').html(res);
                        },
                        error: function(err) {
                            console.error('Error loading tasks:', err);
                        }
                    });
                });

                // 🔹 Toggle Completion AJAX (delegated)
                $(document).on('click', '.toggle-complete', function() {
                    let btn = $(this);
                    let taskId = btn.data('task');
                    let date = btn.data('date');

                    $.ajax({
                        url: "/tasks/" + taskId + "/toggle-complete",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            date: date
                        },
                        success: function(res) {
                            if (res.success) {
                                btn.html(res.completed ? '✅' : '❌');
                            } else {
                                alert('Update failed.');
                            }
                        }
                    });
                });

            });
        </script>
    @endpush

    @push('styles')
        <style>
            /* Freeze first column */
            .sticky-column {
                position: sticky;
                left: 0;
            }
        </style>
    @endpush
@endsection

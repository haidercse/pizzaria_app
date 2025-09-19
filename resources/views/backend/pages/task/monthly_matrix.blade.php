@extends('backend.layouts.master')
@section('title', 'Monthly Task Matrix')
@section('page-title', 'Monthly Task Matrix')
@section('breadcrumb-home_route', route('tasks.monthly.matrix'))
@section('breadcrumb-home_title', 'Monthly Task Matrix')
@section('breadcrumb-current', 'Monthly Task Matrix')


@section('admin-content')
    <div class="main-content">
        <h3 class="text-center mb-4">Monthly Task Matrix - {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h3>

        <form method="GET" action="{{ route('tasks.monthly.matrix') }}" class="mb-3">
            <label>Select Month: </label>
            <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()">
        </form>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                Task Completion Matrix
            </div>
            <div class="card-body">
                <!-- Scrollable container -->
                <div class="table-responsive" style="max-height: 600px; overflow-x: auto; overflow-y: auto;">
                    <table class="table table-bordered text-center align-middle" style="min-width: 800px;">
                        <thead class="table-light">
                            <tr>
                                <th class="sticky-column sticky-top bg-light" style="left:0; z-index: 12;">Tasks</th>
                                @foreach ($dates as $d)
                                    <th class="sticky-top bg-light" style="top:0; z-index: 10;">
                                        {{ \Carbon\Carbon::parse($d)->format('d') }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                @php
                                    $rowClass =
                                        $task->work_side === 'front'
                                            ? 'table-primary'
                                            : ($task->work_side === 'back'
                                                ? 'table-success'
                                                : '');
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td class="sticky-column" style="left:0; background-color: inherit; z-index: 11;">
                                        <strong>{{ ucfirst($task->work_side) }}</strong> - {{ $task->name }}
                                    </td>
                                    @foreach ($dates as $d)
                                        @php
                                            $comp = $task->completions->firstWhere('date', $d);
                                            $isCompleted = $comp && $comp->completed;
                                        @endphp
                                        <td>
                                            <button class="btn btn-sm toggle-complete" data-task="{{ $task->id }}"
                                                data-date="{{ $d }}">
                                                {!! $isCompleted ? '✅' : '❌' !!}
                                            </button>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Ajax Toggle --}}
    <script>
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
    </script>

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

@extends('backend.layouts.master')
@section('title', 'My Shifts')
@section('admin-content')
    <div class="container">
        <h3 class="mb-3 text-center">Employee Shift Schedule</h3>

        {{-- Week Filter Badges --}}
        {{-- <div class="mb-3 d-flex gap-2">
            <input type="month" id="monthFilter" class="form-control" value="{{ request('month') }}">
            <button class="btn btn-primary" id="filterBtn">Filter</button>
        </div> --}}
        <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
            @foreach ($weeks as $week)
                <button class="btn btn-outline-primary week-btn" data-start="{{ $week['start'] }}">
                    {{ $week['label'] }}
                </button>
            @endforeach
        </div>

        {{-- Shifts List --}}

        <div id="shiftList">
            @include('backend.pages.shift.partials.my_shift_table', [
                'shifts' => $shifts,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ])
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.week-btn', function() {
            let startDate = $(this).data('start');
            $.ajax({
                url: "{{ route('shift.employee') }}",
                type: "GET",
                data: {
                    start_date: startDate
                },
                beforeSend: function() {
                    $('#shiftList').html(
                        '<div class="text-center p-3"><div class="spinner-border text-primary"></div></div>'
                    );
                },
                success: function(data) {
                    $('#shiftList').html(data);
                }
            });
        });
        // $('#filterBtn').click(function() {
        //     let month = $('#monthFilter').val();

        //     $.ajax({
        //         url: "{{ route('shift.employee.month') }}",
        //         type: "GET",
        //         data: {
        //             month: month
        //         },
        //         beforeSend: function() {
        //             $('#shiftList').html(
        //                 '<div class="text-center p-3"><div class="spinner-border text-primary"></div></div>'
        //                 );
        //         },
        //         success: function(response) {

        //              // 1) week buttons replace
        //             // $('.week-btn').parent().parent().html(response.weeks_html);

        //             // 2) table replace
        //             $('#shiftList').html(response.table_html);
        //         }
        //     });
        // });
    </script>
@endpush

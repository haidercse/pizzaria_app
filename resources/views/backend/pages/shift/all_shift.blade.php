@extends('backend.layouts.master')
@section('title', 'All Shifts')

@section('admin-content')
<div class="container">
    <h3 class="mb-3 text-center">All Employees Shift Schedule</h3>

    {{-- Place Filter Buttons --}}
    <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
        <button class="btn btn-sm btn-outline-primary place-btn" data-place="">All</button>
        <button class="btn btn-sm btn-outline-warning place-btn" data-place="andel">Andel</button>
        <button class="btn btn-sm btn-outline-success place-btn" data-place="nusle">Nusle</button>
        <button class="btn btn-sm btn-outline-info place-btn" data-place="event">Event</button>
    </div>

    {{-- Week Filter --}}
    <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
        @foreach($weeks as $week)
            <button class="btn btn-outline-primary week-btn" data-start="{{ $week['start'] }}">
                {{ $week['label'] }}
            </button>
        @endforeach
    </div>

    {{-- Shifts List --}}
    <div id="shiftList">
        @include('backend.pages.shift.partials.all_shift_table', [
            'shifts' => $shifts,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'weeklyTotal' => $weeklyTotal,
            'monthlyTotal' => $monthlyTotal
        ])
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentPlace = "";
    let currentStart = "{{ $startDate }}";

    function loadShifts() {
        $.ajax({
            url: "{{ route('all.shifts') }}",
            type: "GET",
            data: { start_date: currentStart, place: currentPlace },
            beforeSend: function () {
                $('#shiftList').html('<div class="text-center p-3"><div class="spinner-border text-primary"></div></div>');
            },
            success: function (data) {
                $('#shiftList').html(data);
            }
        });
    }

    $(document).on('click', '.place-btn', function() {
        currentPlace = $(this).data('place');
        loadShifts();
    });

    $(document).on('click', '.week-btn', function() {
        currentStart = $(this).data('start');
        loadShifts();
    });
</script>
@endpush

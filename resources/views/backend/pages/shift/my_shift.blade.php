@extends('backend.layouts.master')
@section('title', 'My Shifts')
@section('admin-content')
<div class="container">
    <h3 class="mb-3 text-center">Employee Shift Schedule</h3>

    {{-- Week Filter Badges --}}
    <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
        @foreach($weeks as $week)
            <button class="btn btn-outline-primary week-btn" data-start="{{ $week['start'] }}">
                {{ $week['label'] }}
            </button>
        @endforeach
    </div>

    {{-- Shifts List --}}
    <div id="shiftList">
        @include('backend.pages.shift.partials.my_shift_table', ['shifts' => $shifts, 'startDate' => $startDate, 'endDate' => $endDate])
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
            data: { start_date: startDate },
            beforeSend: function () {
                $('#shiftList').html('<div class="text-center p-3"><div class="spinner-border text-primary"></div></div>');
            },
            success: function (data) {
                $('#shiftList').html(data);
            }
        });
    });
</script>
@endpush

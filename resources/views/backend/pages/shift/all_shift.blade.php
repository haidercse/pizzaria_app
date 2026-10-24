@extends('backend.layouts.master')
@section('title', 'All Shifts')

@push('styles')
<style>
    .week-btn.btn-primary {
        background: linear-gradient(135deg, #2563eb, #0f766e);
        border-color: transparent;
        box-shadow: 0 3px 10px rgba(15, 118, 110, .22);
    }

    .today-shift-row > td {
        background-color: #ecfeff !important;
    }

    .today-shift-row > td:first-child {
        box-shadow: inset 4px 0 #0891b2;
        font-weight: 700;
    }

    .my-shift-entry {
        display: inline-block;
        margin: .15rem;
        padding: .3rem .55rem;
        border: 1px solid #0f766e;
        border-radius: 999px;
        background: #ccfbf1;
        color: #115e59;
        font-weight: 600;
    }

    .my-shift-entry .badge {
        background: #0f766e;
        color: #fff;
    }

    .schedule-modal-dialog {
        max-width: 1400px;
        width: 95vw;
    }

    .schedule-modal-content {
        height: 80vh;
    }

    .schedule-modal-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-x: hidden;
        overflow-y: auto;
        padding: .6rem;
    }

    @media (max-width: 767.98px) {
        #scheduleModal .schedule-modal-dialog {
            height: 100%;
            margin: 0;
            max-width: none;
            width: 100%;
        }

        #scheduleModal .schedule-modal-content {
            border: 0;
            border-radius: 0;
            height: 100vh;
            height: 100dvh;
        }

        #scheduleModal .schedule-modal-header {
            flex: 0 0 auto;
            padding: .5rem .75rem;
        }

        #scheduleModal .schedule-modal-body {
            padding: .35rem;
        }
    }
</style>
@endpush

@section('admin-content')
<div class="container-fluid">
    <h3 class="mb-3 text-center">All Employees Shift Schedule</h3>

    <div class="d-flex justify-content-center flex-wrap gap-2 mb-3" aria-label="Schedule locations">
        <button class="btn btn-sm btn-outline-primary place-btn active" data-place="">All locations</button>
        <button class="btn btn-sm btn-outline-warning place-btn" data-place="andel">Andel</button>
        <button class="btn btn-sm btn-outline-success place-btn" data-place="nusle">Nusle</button>
        <button class="btn btn-sm btn-outline-info place-btn" data-place="event">Event</button>
    </div>

    <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
        @foreach ($weeks as $week)
            <button class="btn btn-sm {{ $week['start'] === $startDate ? 'btn-primary' : 'btn-outline-primary' }} week-btn"
                data-start="{{ $week['start'] }}">
                {{ $week['label'] }}
                @if ($week['start'] <= $todayDate && \Carbon\Carbon::parse($week['start'])->endOfWeek(\Carbon\Carbon::SUNDAY)->toDateString() >= $todayDate)
                    <span class="badge bg-light text-primary ms-1">This week</span>
                @endif
            </button>
        @endforeach
    </div>

    <div class="d-flex justify-content-end mb-2">
        <button type="button" class="btn btn-sm btn-primary" id="openScheduleButton">
            <i class="fa fa-calendar"></i>
            <span id="scheduleButtonLabel">View All Locations Schedule</span>
        </button>
    </div>

    <div id="shiftList">
        @include('backend.pages.shift.partials.all_shift_table', [
            'shifts' => $shifts,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'weeklyTotal' => $weeklyTotal,
            'monthlyTotal' => $monthlyTotal,
        ])
    </div>
</div>

<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered schedule-modal-dialog">
        <div class="modal-content schedule-modal-content">
            <div class="modal-header schedule-modal-header">
                <h5 class="modal-title" id="scheduleModalTitle">Weekly Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body schedule-modal-body" id="scheduleModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <span class="visually-hidden">Loading schedule...</span>
                </div>
            </div>
        </div>
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
            },
            error: function () {
                $('#shiftList').html('<div class="alert alert-danger">Could not load shifts. Please try again.</div>');
            }
        });
    }

    function loadSchedule(place) {
        const placeLabel = place ? place.charAt(0).toUpperCase() + place.slice(1) : 'All Locations';
        const weekLabel = $('.week-btn.btn-primary').text().trim().replace(/\s+/g, ' ');
        $('#scheduleModalTitle').text(placeLabel + ' Schedule — ' + weekLabel);
        $('#scheduleModalBody').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');
        $('#scheduleModal').modal('show');

        $.ajax({
            url: "{{ route('shift.schedule') }}",
            type: "GET",
            data: { start_date: currentStart, place: place || '' },
            success: function (html) {
                $('#scheduleModalBody').html(html);
            },
            error: function () {
                $('#scheduleModalBody').html('<div class="alert alert-danger mb-0">Could not load the schedule. Please try again.</div>');
            }
        });
    }

    $(document).on('click', '.place-btn', function() {
        currentPlace = $(this).data('place');
        $('.place-btn').removeClass('active');
        $(this).addClass('active');
        const placeLabel = currentPlace ? currentPlace.charAt(0).toUpperCase() + currentPlace.slice(1) : 'All Locations';
        $('#scheduleButtonLabel').text('View ' + placeLabel + ' Schedule');
        loadShifts();
    });

    $(document).on('click', '.week-btn', function() {
        currentStart = $(this).data('start');
        $('.week-btn').removeClass('btn-primary').addClass('btn-outline-primary');
        $(this).removeClass('btn-outline-primary').addClass('btn-primary');
        loadShifts();
        if ($('#scheduleModal').hasClass('show')) {
            loadSchedule(currentPlace);
        }
    });

    $('#openScheduleButton').on('click', function() {
        loadSchedule(currentPlace);
    });
</script>
@endpush

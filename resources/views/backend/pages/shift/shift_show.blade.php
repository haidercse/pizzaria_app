@extends('backend.layouts.master')
@section('title', 'Shift Details')

@push('styles')
    <style>
        .shift-table-wrapper {
            overflow-x: auto;
            overflow-y: auto;
            max-height: 80vh;
            -webkit-overflow-scrolling: touch;
        }

        .table td,
        .table th {
            white-space: nowrap;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background: #0d6efd;
            color: #fff;
            z-index: 10;
        }

        .sticky-col {
            position: sticky;
            left: 0;
            background: #fff;
            z-index: 11;
        }

        .table thead .sticky-col {
            z-index: 12;
            background: #0d6efd;
            color: #fff;
        }
    </style>
@endpush

@section('admin-content')
    @php
        $selectedMonth = $selectedMonth ?? now()->format('Y-m');
        $startOfMonth = \Carbon\Carbon::parse($selectedMonth . '-01');
    @endphp

    <div class="container">
        <h3 class="mb-4">Monthly Shift Overview - {{ $startOfMonth->format('F Y') }}</h3>

        <div class="mb-3">
            <label for="monthFilter">Select Month:</label>
            <input type="month" id="monthFilter" class="form-control" value="{{ $selectedMonth }}">
        </div>
        <!-- Loader -->
        <div id="loader" style="display:none; text-align:center; margin:20px 0;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Loading shifts...</p>
        </div>


        {{-- এখানে partial load হবে --}}
        <div id="shiftTableWrapper">
            @include('backend.pages.shift.partials.shift_over_view_table', [
                'users' => $users,
                'selectedMonth' => $selectedMonth,
                'startOfMonth' => $startOfMonth,
                'daysInMonth' => $daysInMonth,
                'dailyTotals' => $dailyTotals,
                'totalHoursAllUsers' => $totalHoursAllUsers,
            ])

        </div>
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#monthFilter').on('change', function() {
                    let month = $(this).val();
                    $.ajax({
                        url: "{{ route('shift.show') }}",
                        type: "GET",
                        data: {
                            month: month
                        },
                        beforeSend: function() {
                            $('#loader').show();
                        },
                        success: function(response) {
                            $('#loader').hide();
                            $('#shiftTableWrapper').replaceWith(response);
                        },
                        error: function(xhr) {
                            $('#loader').hide();
                            alert("Something went wrong!");
                        }
                    });

                });
            });
        </script>
    @endpush

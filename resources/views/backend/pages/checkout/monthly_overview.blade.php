@extends('backend.layouts.master')
@section('title', 'Monthly Shift Overview')

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
        $startOfMonth = \Carbon\Carbon::parse($selectedMonth . '-01');
    @endphp

    <div class="container">
        <h3 class="mb-4">Monthly Shift Overview - {{ $startOfMonth->format('F Y') }}</h3>

        <div class="mb-3">
            <label for="monthFilter">Select Month:</label>
            <input type="month" id="monthFilter" class="form-control" value="{{ $selectedMonth }}">
        </div>
        <div class="card mb-3">

            <div class="card-header">
                <b>Monthly Tips</b>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">

                        <label>Nusle Total Tips</label>

                        <input type="number" class="form-control" id="nusle_total_tips" value="{{ $nusleTotalTips }}">

                    </div>

                    <div class="col-md-3">

                        <label>Andel Total Tips</label>

                        <input type="number" class="form-control" id="andel_total_tips" value="{{ $andelTotalTips }}">

                    </div>

                    <div class="col-md-2 mt-4">

                        <button class="btn btn-success" id="saveTips">

                            Save

                        </button>

                    </div>

                </div>

            </div>

        </div>
        <div id="monthlyTableWrapper">
            @include('backend.pages.checkout.partials.monthly_table')
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#monthFilter').on('change', function() {
                let month = $(this).val();

                $.ajax({
                    url: "{{ route('checkout.monthly_overview') }}",
                    type: "GET",
                    data: {
                        month: month
                    },
                    success: function(response) {
                        $('#monthlyTableWrapper').html(response.html);

                        $('#nusle_total_tips').val(response.nusleTotalTips);

                        $('#andel_total_tips').val(response.andelTotalTips);
                    }
                });
            });
            $('#saveTips').click(function() {

                $.ajax({

                    url: "{{ route('checkout.save.monthly.tips') }}",

                    type: "POST",

                    data: {

                        _token: "{{ csrf_token() }}",

                        month: $('#monthFilter').val(),

                        nusle_total_tips: $('#nusle_total_tips').val(),

                        andel_total_tips: $('#andel_total_tips').val()

                    },

                    success: function() {

                        $('#monthFilter').trigger('change');

                    }

                });

            });
        });
    </script>
@endpush

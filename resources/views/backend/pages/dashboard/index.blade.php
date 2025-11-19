@extends('backend.layouts.master')

@section('title')
    Dashboard-page
@endsection

@section('admin-content')
    <div class="main-content">

    @section('page-title', 'Dashboard')
    @section('breadcrumb-home_route', route('admin.dashboard'))
    @section('breadcrumb-home_title', 'Main Page')
    @section('breadcrumb-current', 'Home')

    <div class="main-content-inner">

        <!-- Catering Calendar Start -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Catering Calendar ({{ now()->format('F Y') }})</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 text-center">
                            @php
                                $start = now()->startOfMonth();
                                $end = now()->endOfMonth();
                            @endphp

                            @for ($date = $start->copy(); $date->lte($end); $date->addDay())
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2 border p-2"
                                    style="min-height: 120px; border-radius:10px;">
                                    <h6 class="fw-bold">{{ $date->format('d D') }}</h6>

                                    @php
                                        $eventItem =
                                            $events->firstWhere('event_date', $date->format('Y-m-d'))->note ?? null;
                                    @endphp

                                    @if ($eventItem)
                                        <div class="badge bg-info text-dark mt-2 small">
                                            {{ $eventItem }}
                                        </div>
                                    @else
                                        <span class="text-muted small">No Events</span>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Catering Calendar End -->

        {{-- Dough Stats Cards --}}
        <div class="sales-report-area mt-5 mb-5">
            <div class=" bg-success text-white">
                <h4 class="mb-0">Dough Stats</h4>
            </div>
            <div class="row g-3">
                @foreach ($weekDays as $dough)
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="single-report h-100 p-3 shadow-sm rounded"
                            style="border:2px solid rgb(204,229,245);">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h4 class="mb-0">{{ $dough['dough_litter'] ?: '❌' }} L</h4>
                                <p class="mb-0">{{ $dough['day'] }}</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{ $dough['dough_total_weight'] ?: '0' }} kg</h5>
                                <span>{{ $dough['dough_num_of_cajas'] ?: '0' }} cajas</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {{-- Dough Stats End --}}



    </div>
</div>
@endsection

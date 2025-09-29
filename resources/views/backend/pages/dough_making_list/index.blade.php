@extends('backend.layouts.master')
@section('title', 'Dough Making Tables')

@section('admin-content')
<div class="container my-4">
    <h3 class="mb-4 text-success fw-bold text-center">Dough Making Tables</h3>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3" id="doughTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="phase-tab" data-toggle="tab" href="#phase" role="tab" aria-controls="phase" aria-selected="true">Phase 1 & Phase 2</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="yeast-tab" data-toggle="tab" href="#yeast" role="tab" aria-controls="yeast" aria-selected="false">Yeast & Salt</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="flour-tab" data-toggle="tab" href="#flour" role="tab" aria-controls="flour" aria-selected="false">Flour Distribution</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="doughTabsContent">
        <!-- Phase 1 & 2 Table -->
        <div class="tab-pane fade show active" id="phase" role="tabpanel" aria-labelledby="phase-tab">
            <div class="card shadow-sm">
                <div class="card-body table-responsive">
                    <table class="table table-bordered text-center table-striped align-middle">
                        <thead>
                            <tr>
                                <th rowspan="2" class="align-middle">Water (L)</th>
                                <th colspan="1" class="bg-warning bg-opacity-50">Phase 1</th>
                                <th colspan="2" class="bg-success bg-opacity-25">Phase 2</th>
                                <th colspan="4" class="bg-info bg-opacity-25">Mixing Times</th>
                            </tr>
                            <tr>
                                <th class="bg-warning bg-opacity-25">Tipo 00</th>
                                <th class="bg-success bg-opacity-25">Tipo 00</th>
                                <th class="bg-success bg-opacity-25">Tipo 1</th>
                                <th class="bg-info bg-opacity-25">15 MINS</th>
                                <th class="bg-info bg-opacity-25">8 MINS (2nd)</th>
                                <th class="bg-info bg-opacity-25">8 MINS (3rd)</th>
                                <th class="bg-info bg-opacity-25">8 MINS (4th)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($phaseTables as $row)
                                <tr>
                                    <td class="fw-bold">{{ $row->water_l }}</td>
                                    <td contenteditable="true" data-id="{{ $row->id }}" data-field="phase1_tipo00">{{ $row->phase1_tipo00 }}</td>
                                    <td contenteditable="true" data-id="{{ $row->id }}" data-field="phase2_tipo00">{{ $row->phase2_tipo00 }}</td>
                                    <td contenteditable="true" data-id="{{ $row->id }}" data-field="phase2_tipo1">{{ $row->phase2_tipo1 }}</td>
                                    <td contenteditable="true" data-id="{{ $row->id }}" data-field="first_15min">{{ $row->first_15min }}</td>
                                    <td contenteditable="true" data-id="{{ $row->id }}" data-field="second_8min">{{ $row->second_8min }}</td>
                                    <td contenteditable="true" data-id="{{ $row->id }}" data-field="third_8min">{{ $row->third_8min }}</td>
                                    <td contenteditable="true" data-id="{{ $row->id }}" data-field="fourth_8min">{{ $row->fourth_8min }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Yeast & Salt Table -->
        <div class="tab-pane fade" id="yeast" role="tabpanel" aria-labelledby="yeast-tab">
            <div class="card shadow-sm">
                <div class="card-body table-responsive">
                    <table class="table table-bordered text-center table-striped align-middle">
                        <thead>
                            <tr>
                                <th rowspan="2" class="align-middle bg-light">Water (L)</th>
                                <th colspan="7" class="bg-warning text-dark">YEAST (g/L)</th>
                                <th colspan="2" class="bg-success bg-opacity-25 text-dark">SALT (g/L)</th>
                            </tr>
                            <tr class="table-success">
                                <th>0.7</th>
                                <th>0.8</th>
                                <th>0.9</th>
                                <th>1.0</th>
                                <th>1.1</th>
                                <th>1.2</th>
                                <th>1.3</th>
                                <th>38</th>
                                <th>39</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($yeastSalts as $row)
                                <tr>
                                    <td>{{ $row->water_l }}</td>
                                    <td>{{ $row->y07 }}</td>
                                    <td>{{ $row->y08 }}</td>
                                    <td>{{ $row->y09 }}</td>
                                    <td>{{ $row->y10 }}</td>
                                    <td>{{ $row->y11 }}</td>
                                    <td>{{ $row->y12 }}</td>
                                    <td>{{ $row->y13 }}</td>
                                    <td>{{ $row->salt38 }}</td>
                                    <td>{{ $row->salt39 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Flour Distribution Table -->
        <div class="tab-pane fade" id="flour" role="tabpanel" aria-labelledby="flour-tab">
            <div class="card shadow-sm">
                <div class="card-body table-responsive">
                    <table class="table table-bordered text-center table-striped align-middle">
                        <thead>
                            <tr>
                                <th class="bg-light">Water (L)</th>
                                <th class="bg-warning bg-opacity-50">Total Flour (Kg)</th>
                                <th class="bg-info bg-opacity-25">Tipo 00 (80%)</th>
                                <th class="bg-info bg-opacity-25">Tipo 1 (20%)</th>
                                <th class="bg-success bg-opacity-25">Dough (Kg)</th>
                                <th class="bg-primary bg-opacity-25">N° of cajas</th>
                                <th class="bg-secondary bg-opacity-25">Divide into boxes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($flourDistributions as $row)
                                <tr>
                                    <td class="fw-bold">{{ $row->water_l }}</td>
                                    <td>{{ $row->total_flour }}</td>
                                    <td>{{ $row->tipo_00 }}</td>
                                    <td>{{ $row->tipo_1 }}</td>
                                    <td>{{ $row->dough_kg }}</td>
                                    <td>{{ $row->cajas }}</td>
                                    <td>{{ $row->divide_boxes }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    let alertShown = false; // prevent repeated alerts

    $('td[contenteditable=true]').blur(function() {
        let id = $(this).data('id');
        let field = $(this).data('field');
        let value = $(this).text();
        let cell = $(this);

        // Remove previous highlight
        cell.removeClass('bg-success bg-danger text-white');

        // Validate numeric
        if(isNaN(value) || value.trim() === '') {
            cell.addClass('bg-danger text-white');
            if(!alertShown){
                // first time invalid input
                // use subtle inline message instead of alert
                $('<div class="invalid-feedback d-block">Please enter a valid number!</div>')
                    .insertAfter(cell)
                    .fadeOut(3000, function(){ $(this).remove(); });
                alertShown = true;
                setTimeout(() => { alertShown = false; }, 3100);
            }
            return;
        }

        // Ajax save
        $.ajax({
            url: "{{ route('phase.update.inline') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                field: field,
                value: value
            },
            success: function(res) {
                // Highlight success
                cell.addClass('bg-success text-white');
                setTimeout(function() { cell.removeClass('bg-success text-white'); }, 1200);
            },
            error: function(err) {
                cell.addClass('bg-danger text-white');
            }
        });
    });

});
</script>
@endpush


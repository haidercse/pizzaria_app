@extends('backend.layouts.master')
@section('title', 'Set Monthly Availability')

@section('admin-content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3 text-center">📅 Submit Your Monthly Availability</h4>

                <div id="alertBox"></div>

                <form id="availabilityForm">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center" id="availabilityTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Preferred Time</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="availability-row">
                                    <td><input type="date" name="date[]" class="form-control dateInput" required></td>
                                    <td><input type="text" class="form-control dayInput" readonly placeholder="Day"></td>
                                    <td>
                                        <select name="preferred_time[]" class="form-select preferred_time" required>
                                            <option value="">--Select--</option>
                                            <option value="morning">Morning</option>
                                            <option value="evening">Evening</option>
                                            <option value="full_day">Full Day</option>
                                            <option value="custom">Custom</option>
                                            @if (Auth::user()->contract && Auth::user()->contract->type == 'full_time')
                                                <option value="unavailable">Unavailable</option>
                                            @endif

                                        </select>
                                    </td>
                                    <td>
                                        <select name="start_time[]" class="form-select" disabled>
                                            @for ($h = 0; $h < 24; $h++)
                                                @for ($m = 0; $m < 60; $m += 30)
                                                    @php $time = sprintf('%02d:%02d', $h, $m); @endphp
                                                    <option value="{{ $time }}">{{ $time }}</option>
                                                @endfor
                                            @endfor
                                        </select>
                                    </td>
                                    <td>
                                        <select name="end_time[]" class="form-select" disabled>
                                            @for ($h = 0; $h < 24; $h++)
                                                @for ($m = 0; $m < 60; $m += 30)
                                                    @php $time = sprintf('%02d:%02d', $h, $m); @endphp
                                                    <option value="{{ $time }}">{{ $time }}</option>
                                                @endfor
                                            @endfor
                                        </select>
                                    </td>
                                    <td><input type="text" name="note[]" class="form-control" placeholder="Optional">
                                    </td>
                                    <td><button type="button" class="btn btn-danger btn-sm removeRow">✖</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-success" id="addRow">➕ Add Day</button>
                        <button type="submit" class="btn btn-primary">💾 Save Availability</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function attachEvents(row) {
                row.querySelector('.removeRow').addEventListener('click', function() {
                    if (document.querySelectorAll('.availability-row').length > 1) {
                        row.remove();
                    } else {
                        alert('At least one row is required');
                    }
                });

                row.querySelector('.dateInput').addEventListener('change', function() {
                    let date = new Date(this.value);
                    if (!isNaN(date)) {
                        let options = {
                            weekday: 'long'
                        };
                        row.querySelector('.dayInput').value = date.toLocaleDateString('en-GB', options);
                    } else {
                        row.querySelector('.dayInput').value = '';
                    }
                });

                row.querySelector('.preferred_time').addEventListener('change', function() {
                    let startSelect = row.querySelector('[name="start_time[]"]');
                    let endSelect = row.querySelector('[name="end_time[]"]');
                    if (this.value === 'custom') {
                        startSelect.disabled = false;
                        endSelect.disabled = false;
                    } else {
                        startSelect.disabled = true;
                        endSelect.disabled = true;
                    }
                });
            }

            attachEvents(document.querySelector('.availability-row'));

            document.getElementById('addRow').addEventListener('click', function() {
                let tableBody = document.querySelector('#availabilityTable tbody');
                let newRow = tableBody.querySelector('tr').cloneNode(true);

                newRow.querySelectorAll('input').forEach(input => input.value = '');
                newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
                tableBody.appendChild(newRow);

                attachEvents(newRow);
            });

            // Ajax Form Submit with Validation
            document.getElementById('availabilityForm').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = this;
                let formData = new FormData(form);

                fetch("{{ route('availability.store') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": form.querySelector('input[name=_token]').value
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        let alertBox = document.getElementById('alertBox');
                        alertBox.innerHTML = "";

                        if (data.errors) {
                            let errors = "<div class='alert alert-danger'><ul>";
                            Object.values(data.errors).forEach(msg => {
                                errors += `<li>${msg}</li>`;
                            });
                            errors += "</ul></div>";
                            alertBox.innerHTML = errors;
                        } else if (data.success) {
                            alertBox.innerHTML =
                                `<div class="alert alert-success">${data.success}</div>`;
                            form.reset();
                            document.querySelectorAll('.dayInput').forEach(input => input.value = '');
                        }
                    })
                    .catch(err => console.error(err));
            });
        });
        // ✅ This should be placed AFTER the DOMContentLoaded block
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('preferred_time')) {
                let row = e.target.closest('.availability-row');
                let startSelect = row.querySelector('[name="start_time[]"]');
                let endSelect = row.querySelector('[name="end_time[]"]');

                if (e.target.value === 'custom') {
                    startSelect.disabled = false;
                    endSelect.disabled = false;
                } else {
                    startSelect.disabled = true;
                    endSelect.disabled = true;
                }
            }
        });
    </script>
@endsection

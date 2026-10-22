@extends('backend.layouts.master')
@section('title', 'Set Monthly Availability')

@section('admin-content')
    @php
        $availabilityMinDate = $latestPublishedShiftDate
            ? \Carbon\Carbon::parse($latestPublishedShiftDate)->addDay()->toDateString()
            : null;
    @endphp
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3 text-center">📅 Submit Your Monthly Availability</h4>
                @if ($latestPublishedShiftDate)
                    <div class="alert alert-info">
                        Shifts are published through {{ \Carbon\Carbon::parse($latestPublishedShiftDate)->format('d M Y') }}.
                        You can submit availability from {{ \Carbon\Carbon::parse($availabilityMinDate)->format('d M Y') }} onward.
                    </div>
                @endif

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
                                    {{-- <th>Note</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="availability-row">
                                    <td><input type="date" name="date[]" class="form-control dateInput"
                                            @if ($availabilityMinDate) min="{{ $availabilityMinDate }}" @endif required></td>
                                    <td><input type="text" class="form-control dayInput" readonly placeholder="Day"></td>
                                    <td>
                                        <select name="preferred_time[]" class="form-select form-control preferred_time"
                                            required>
                                            <option value="">--Select--</option>
                                            <option value="morning">Morning</option>
                                            <option value="evening">Evening</option>
                                            <option value="full_day">Full Day</option>
                                            <option value="custom">Custom</option>
                                            <option value="unavailable">Unavailable</option>
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
                                    {{-- <td>
                                        <input type="text" name="note[]" class="form-control" placeholder="Optional">
                                    </td> --}}
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
            const publishedThrough = @json($latestPublishedShiftDate);
            const alertBox = document.getElementById('alertBox');

            function showErrors(messages) {
                alertBox.replaceChildren();
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger';
                const list = document.createElement('ul');
                messages.forEach(message => {
                    const item = document.createElement('li');
                    item.textContent = message;
                    list.appendChild(item);
                });
                alert.appendChild(list);
                alertBox.appendChild(alert);
            }

            function attachEvents(row) {
                row.querySelector('.removeRow').addEventListener('click', function() {
                    if (document.querySelectorAll('.availability-row').length > 1) {
                        row.remove();
                    } else {
                        alert('At least one row is required');
                    }
                });

                row.querySelector('.dateInput').addEventListener('change', function() {
                    if (publishedThrough && this.value && this.value <= publishedThrough) {
                        alert(`Shifts are published through ${publishedThrough}. Choose a date after that.`);
                        this.value = '';
                        row.querySelector('.dayInput').value = '';
                        return;
                    }

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
                        if (data.errors) {
                            showErrors(Object.values(data.errors).flat());
                        } else if (data.success) {
                            alertBox.replaceChildren();
                            const success = document.createElement('div');
                            success.className = 'alert alert-success';
                            success.textContent = data.success;
                            alertBox.appendChild(success);
                            form.reset();
                            document.querySelectorAll('.dayInput').forEach(input => input.value = '');
                        }
                    })
                    .catch(() => showErrors(['Availability could not be saved. Please try again.']));
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

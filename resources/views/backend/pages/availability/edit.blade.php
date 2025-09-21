@extends('backend.layouts.master')
@section('title', 'Edit Availability')

@section('admin-content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div id="alertContainer"></div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="mb-3 text-center">📅 Edit Your Availability</h4>
                        <form id="editAvailabilityForm" action="{{ route('availability.update', $availability->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label fw-bold">Select Date</label>
                                <input type="date" id="dateInput" name="date" class="form-control"
                                    value="{{ $availability->date }}" required>
                                <div class="invalid-feedback" id="dateError"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Day</label>
                                <input type="text" id="dayInput" class="form-control" readonly
                                    value="{{ \Carbon\Carbon::parse($availability->date)->format('l') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Preferred Time</label>
                                <select name="preferred_time" id="preferred_time" class="form-control" required>
                                    <option value="morning"
                                        {{ $availability->preferred_time == 'morning' ? 'selected' : '' }}>Morning</option>
                                    <option value="evening"
                                        {{ $availability->preferred_time == 'evening' ? 'selected' : '' }}>Evening</option>
                                    <option value="full_day"
                                        {{ $availability->preferred_time == 'full_day' ? 'selected' : '' }}>Full Day
                                    </option>
                                    <option value="custom"
                                        {{ $availability->preferred_time == 'custom' ? 'selected' : '' }}>Custom</option>
                                </select>
                                <div class="invalid-feedback" id="preferred_timeError"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Start Time</label>
                                    <select name="start_time" class="form-control">
                                        @for ($h = 0; $h < 24; $h++)
                                            @for ($m = 0; $m < 60; $m += 30)
                                                @php
                                                    $time = sprintf('%02d:%02d', $h, $m);
                                                    $dbStartTime = substr($availability->start_time, 0, 5);
                                                @endphp
                                                <option value="{{ $time }}"
                                                    {{ $dbStartTime == $time ? 'selected' : '' }}>
                                                    {{ $time }}
                                                </option>
                                            @endfor
                                        @endfor
                                    </select>
                                    <div class="invalid-feedback" id="start_timeError"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">End Time</label>
                                    <select name="end_time" class="form-control">
                                        @for ($h = 0; $h < 24; $h++)
                                            @for ($m = 0; $m < 60; $m += 30)
                                                @php
                                                    $time = sprintf('%02d:%02d', $h, $m);
                                                    $dbEndTime = substr($availability->end_time, 0, 5);
                                                @endphp
                                                <option value="{{ $time }}"
                                                    {{ $dbEndTime == $time ? 'selected' : '' }}>
                                                    {{ $time }}
                                                </option>
                                            @endfor
                                        @endfor
                                    </select>
                                    <div class="invalid-feedback" id="end_timeError"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Note (optional)</label>
                                <textarea name="note" class="form-control" rows="3">{{ $availability->note }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Save Availability</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('dateInput').addEventListener('change', function() {
            let date = new Date(this.value);
            if (!isNaN(date)) {
                let options = {
                    weekday: 'long'
                };
                document.getElementById('dayInput').value = date.toLocaleDateString('en-GB', options);
            } else {
                document.getElementById('dayInput').value = "";
            }
        });

        // Ajax form submit
        document.getElementById('editAvailabilityForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const url = form.action;
            const formData = new FormData(form);

            // Clear previous errors
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            document.querySelectorAll('.form-control, .form-select').forEach(el => el.classList.remove(
                'is-invalid'));
            document.getElementById('alertContainer').innerHTML = '';

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('alertContainer').innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                    } else if (data.errors) {
                        for (const [key, messages] of Object.entries(data.errors)) {
                            const el = document.querySelector(`[name="${key}"]`);
                            if (el) {
                                el.classList.add('is-invalid');
                                document.getElementById(key + 'Error').textContent = messages[0];
                            }
                        }
                    } else {
                        document.getElementById('alertContainer').innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${data.message || 'Something went wrong'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('alertContainer').innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Ajax request failed
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
                });
        });
    </script>
@endsection

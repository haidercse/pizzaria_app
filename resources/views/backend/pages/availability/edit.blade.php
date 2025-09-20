@extends('backend.layouts.master')
@section('title', 'Set Availability')

@section('admin-content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="mb-3 text-center">📅 Submit Your Availability</h4>
                        <form action="{{ route('availability.update', $availability->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label fw-bold">Select Date</label>
                                <input type="date" id="dateInput" name="date" class="form-control"
                                    value="{{ $availability->date }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Day</label>
                                <input type="text" id="dayInput" class="form-control" readonly
                                    placeholder="Day will show here"
                                    value="{{ \Carbon\Carbon::parse($availability->date)->format('l') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Preferred Time</label>
                                <select name="preferred_time" id="preferred_time" class="form-control" required>
                                    <option value="morning" {{ $availability->preferred_time == 'morning' ? 'selected' : '' }}>Morning</option>
                                    <option value="evening" {{ $availability->preferred_time == 'evening' ? 'selected' : '' }}>Evening</option>
                                    <option value="full_day" {{ $availability->preferred_time == 'full_day' ? 'selected' : '' }}>Full Day</option>
                                    <option value="custom" {{ $availability->preferred_time == 'custom' ? 'selected' : '' }}>Custom</option>
                                </select>
                            </div>

                            <!-- Custom Time -->
                            <div id="timeInputs">
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
                                                    <option value="{{ $time }}" {{ $dbStartTime == $time ? 'selected' : '' }}>
                                                        {{ $time }}
                                                    </option>
                                                @endfor
                                            @endfor
                                        </select>
                                        @error('start_time')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
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
                                                    <option value="{{ $time }}" {{ $dbEndTime == $time ? 'selected' : '' }}>
                                                        {{ $time }}
                                                    </option>
                                                @endfor
                                            @endfor
                                        </select>
                                        @error('end_time')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Note (optional)</label>
                                <textarea name="note" class="form-control" rows="3" placeholder="E.g. Please try to give me more shifts">{{ $availability->note }}</textarea>
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
                let options = { weekday: 'long' };
                let dayName = date.toLocaleDateString('en-GB', options);
                document.getElementById('dayInput').value = dayName;
            } else {
                document.getElementById('dayInput').value = "";
            }
        });
    </script>
@endsection
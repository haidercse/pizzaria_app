@extends('backend.layouts.master')
@section('title', 'My Checkouts')
@section('admin-content')
    <div class="container">
        <h5 class="mb-3 text-center">Submit Your Hours (This Month)</h5>
        <div id="checkoutMsg"></div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Place</th>
                        <th>Worked Hours</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($days as $day)
                        @php $index = $loop->iteration; @endphp
                        <tr data-date="{{ $day['date']->toDateString() }}">
                            <td>{{ $index }}</td>
                            <td>{{ $day['date']->format('d M Y') }}</td>
                            <td>{{ $day['date']->format('l') }}</td>
                            <td>
                                <select class="form-select form-select-sm place-input">
                                    <option value="">-- Select Place --</option>
                                    <option value="andel"
                                        {{ $day['checkout'] && $day['checkout']->place == 'andel' ? 'selected' : '' }}>Andel
                                    </option>
                                    <option value="nusle"
                                        {{ $day['checkout'] && $day['checkout']->place == 'nusle' ? 'selected' : '' }}>Nusle
                                    </option>
                                    <option value="event"
                                        {{ $day['checkout'] && $day['checkout']->place == 'event' ? 'selected' : '' }}>Event
                                    </option>
                                </select>
                            </td>
                            <td>
                                <input type="number" step="0.5" min="0" max="24"
                                    class="form-control form-control-sm hours-input"
                                    value="{{ $day['checkout'] ? $day['checkout']->worked_hours : '' }}">
                            </td>
                            <td>
                                <button class="btn btn-sm btn-success update-btn"
                                    data-id="{{ $day['checkout'] ? $day['checkout']->id : '' }}">
                                    Update
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $(document).on('click', '.update-btn', function() {
                    let row = $(this).closest('tr');
                    let id = $(this).data('id');
                    let place = row.find('.place-input').val();
                    let hours = parseFloat(row.find('.hours-input').val());
                    let date = row.data('date');
                    let day = row.find('td:nth-child(3)').text();
                    let token = "{{ csrf_token() }}";

                    if (isNaN(hours)) {
                        alert("Please enter a valid number for worked hours.");
                        return;
                    }
                    if (!place) {
                        alert("Please select a place.");
                        return;
                    }

                    let url = id ? '/checkout/update/' + id : '/checkout/store';

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: token,
                            worked_hours: hours,
                            place: place,
                            date: date,
                            day: day
                        },
                        success: function(res) {
                            if (res.success) {
                                $('#checkoutMsg').html('<div class="alert alert-success">' + res
                                    .message + '</div>');
                                if (!id && res.id) {
                                    // assign new id to button
                                    row.find('.update-btn').data('id', res.id);
                                }
                            }
                        },
                        error: function(xhr) {
                            let msg = 'Something went wrong!';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            $('#checkoutMsg').html('<div class="alert alert-danger">' + msg +
                                '</div>');
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection

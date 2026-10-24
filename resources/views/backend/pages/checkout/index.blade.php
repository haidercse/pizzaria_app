@extends('backend.layouts.master')
@section('title', 'My Checkouts')
@section('admin-content')
    <div class="container">
        <h5 class="mb-3 text-center">Submit Your Hours (This Month)</h5>
        <div class="text-center mb-3">
            <span class="badge bg-primary fs-6">
                {{ $monthStart->format('F') }} -
                <span id="monthlyTotalHours">{{ rtrim(rtrim(number_format($monthlyTotalHours, 2, '.', ''), '0'), '.') }}</span>
                hours
            </span>
        </div>
        <p class="text-muted text-center">Scheduled shift hours are prefilled. You can edit them before updating.</p>
        <div id="checkoutMsg"></div>
        <div class="text-center mb-3">
            <button type="button" class="btn btn-primary" id="saveAllCheckouts">
                Save All Hours
            </button>
        </div>

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
                                        {{ $day['place'] == 'andel' ? 'selected' : '' }}>Andel
                                    </option>
                                    <option value="nusle"
                                        {{ $day['place'] == 'nusle' ? 'selected' : '' }}>Nusle
                                    </option>
                                    <option value="event"
                                        {{ $day['place'] == 'event' ? 'selected' : '' }}>Event
                                    </option>
                                </select>
                            </td>
                            <td>
                                <input type="number" step="0.5" min="0" max="24"
                                    class="form-control form-control-sm hours-input"
                                    value="{{ $day['worked_hours'] }}">
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
                function updateMonthlyTotal() {
                    let total = 0;
                    $('.hours-input').each(function() {
                        let hours = parseFloat($(this).val());
                        if (!isNaN(hours)) {
                            total += hours;
                        }
                    });

                    let formattedTotal = Number(total.toFixed(2)).toString();
                    $('#monthlyTotalHours').text(formattedTotal || '0');
                }

                $(document).on('input change', '.hours-input', updateMonthlyTotal);

                $('#saveAllCheckouts').on('click', function() {
                    let checkouts = [];
                    let incompleteDate = null;

                    $('tbody tr[data-date]').each(function() {
                        let row = $(this);
                        let place = row.find('.place-input').val();
                        let hours = row.find('.hours-input').val().trim();

                        if (!place && !hours) {
                            return;
                        }

                        if (!place || hours === '') {
                            incompleteDate = row.data('date');
                            return false;
                        }

                        checkouts.push({
                            date: row.data('date'),
                            place: place,
                            worked_hours: hours
                        });
                    });

                    if (incompleteDate) {
                        $('#checkoutMsg').html(
                            '<div class="alert alert-danger">Please select a place and enter hours for ' +
                            incompleteDate + '.</div>'
                        );
                        return;
                    }

                    if (checkouts.length === 0) {
                        $('#checkoutMsg').html(
                            '<div class="alert alert-danger">There are no checkout hours to save.</div>'
                        );
                        return;
                    }

                    let button = $(this);
                    button.prop('disabled', true);

                    $.ajax({
                        url: "{{ route('checkout.save.all') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            checkouts: checkouts
                        },
                        success: function(res) {
                            $('#checkoutMsg').html(
                                '<div class="alert alert-success">' + res.message + '</div>'
                            );
                        },
                        error: function(xhr) {
                            let msg = xhr.responseJSON && xhr.responseJSON.message
                                ? xhr.responseJSON.message
                                : 'Could not save checkout hours. Please try again.';
                            $('#checkoutMsg').html(
                                '<div class="alert alert-danger">' + msg + '</div>'
                            );
                        },
                        complete: function() {
                            button.prop('disabled', false);
                        }
                    });
                });

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

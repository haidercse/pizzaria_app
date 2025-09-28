@extends('backend.layouts.master')
@section('title', 'Submit your hours')
@section('admin-content')

    <!-- Checkout Card -->
    <div class="card shadow-sm border-0 mx-auto mt-4" style="max-width: 420px;">
        <div class="card-header bg-primary text-white text-center py-2">
            <h5 class="mb-0">Checkout Today</h5>
        </div>
        <div class="card-body p-3">
            <form id="checkoutForm">
                @csrf

                <!-- Hidden fields for date & day -->
                <input type="hidden" name="date" value="{{ \Carbon\Carbon::now()->toDateString() }}">
                <input type="hidden" name="day" value="{{ \Carbon\Carbon::now()->format('l') }}">

                <!-- Place -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Place</label>
                    <select name="place" class="form-control" required>
                        <option value="">-- Select Place --</option>
                        <option value="andel">Andel</option>
                        <option value="nusle">Nusle</option>
                        <option value="event">Event</option>
                    </select>
                </div>

                <!-- Hours -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Worked Hours</label>
                    <input type="number" step="0.5" name="worked_hours" class="form-control" placeholder="e.g. 7.5"
                        required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success btn-block">
                    <i class="fas fa-save"></i> Save Checkout
                </button>
            </form>


            <!-- Message -->
            <div id="checkoutMsg" class="mt-3"></div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).on('submit', '#checkoutForm', function(e) {
            e.preventDefault();
            let form = $(this);

            $.ajax({
                url: "{{ route('checkout.store') }}",
                type: "POST",
                data: form.serialize(),
                success: function(res) {
                    if (res.success) {
                        $('#checkoutMsg').html('<div class="alert alert-success">' + res.message +
                            '</div>');
                        form.trigger("reset");
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        $('#checkoutMsg').html('<div class="alert alert-warning">' + xhr.responseJSON
                            .message + '</div>');
                    } else {
                        $('#checkoutMsg').html(
                            '<div class="alert alert-danger">Something went wrong!</div>');
                    }
                }
            });
        });
    </script>
@endpush

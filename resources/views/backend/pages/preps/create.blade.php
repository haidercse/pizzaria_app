@extends('backend.layouts.master')
@section('title', 'Add Prep')

@section('admin-content')
    <div class="container my-4">
        <h3 class="mb-4 text-success fw-bold text-center">Add New Prep</h3>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">Prep Form</div>
            <div class="card-body">
                <form id="prepForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter prep name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ingredients</label>
                        <textarea id="ingredients" name="ingredients" class="form-control summernote" rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Process</label>
                        <textarea id="process" name="process" class="form-control summernote" rows="4"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Save Prep</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 150,
            });

            // Submit form
            $('#prepForm').on('submit', function(e) {
                e.preventDefault();

                // Set Summernote content to textarea before submit
                $('#ingredients').val($('#ingredients').summernote('code'));
                $('#process').val($('#process').summernote('code'));

                $.ajax({
                    url: "{{ route('preps.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            alert('Prep added successfully!');
                            // Redirect to list page using JS (not PHP redirect)
                            window.location.href = "{{ route('preps.list') }}";
                        }
                    }
                });
            });
        });
    </script>
@endpush

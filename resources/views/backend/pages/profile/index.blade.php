@extends('backend.layouts.master')

@section('title', 'Profile Page')
@section('page-title', 'Profile')
@section('breadcrumb-home_route', route('admin.dashboard'))
@section('breadcrumb-home_title', 'Dashboard')
@section('breadcrumb-current', 'Profile')

@section('admin-content')
    <div class="main-content">
        @include('backend.layouts.partials.message')

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mt-5">
                    <div class="card-body">
                        <h4 class="header-title text-center">Profile Information</h4>
                        <hr>
                        <form enctype="multipart/form-data" id="profileForm" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Your Name" value="{{ auth()->user()->name ?? '' }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Your Email" value="{{ auth()->user()->email ?? '' }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Leave blank to keep current password">
                                    <small class="form-text text-muted">Leave blank if you don't want to change it.</small>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Your Phone Number" value="{{ auth()->user()->phone ?? '' }}" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Your Address">{{ auth()->user()->address ?? '' }}</textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6 mb-3 text-center">
                                    <label for="profile_image" class="d-block mb-2">Your Profile Image</label>
                                    @if (auth()->user()->image)
                                        <img id="profilePreview" src="{{ asset(auth()->user()->image) }}"
                                            class="img-thumbnail mb-2" style="max-width: 150px;">
                                    @else
                                        <img id="profilePreview" src="{{ asset('images/default-profile.png') }}"
                                            class="img-thumbnail mb-2" style="max-width: 150px;">
                                    @endif
                                    <input type="file" class="form-control-file d-inline-block" id="profile_image"
                                        name="image" accept="image/*">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6 mb-3 text-center">
                                    <label for="passport_image" class="d-block mb-2">Your Passport</label>
                                    @if (auth()->user()->passport_image)
                                        <img id="passportPreview" src="{{ asset(auth()->user()->passport_image) }}"
                                            class="img-thumbnail mb-2" style="max-width: 150px;">
                                    @else
                                        <img id="passportPreview" src="{{ asset('images/default-passport.png') }}"
                                            class="img-thumbnail mb-2" style="max-width: 150px;">
                                    @endif
                                    <input type="file" class="form-control-file d-inline-block" id="passport_image"
                                        name="passport_image" accept="image/*">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6 mb-3 text-center">
                                    <label for="trc_image" class="d-block mb-2">Your TRC</label>
                                    @if (auth()->user()->trc_image)
                                        <img id="trcPreview" src="{{ asset(auth()->user()->trc_image) }}"
                                            class="img-thumbnail mb-2" style="max-width: 150px;">
                                    @else
                                        <img id="trcPreview" src="{{ asset('images/default-trc.png') }}"
                                            class="img-thumbnail mb-2" style="max-width: 150px;">
                                    @endif
                                    <input type="file" class="form-control-file d-inline-block" id="trc_image"
                                        name="trc_image" accept="image/*">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6 mb-3 text-center">
                                    <label for="study_confirmation_image" class="d-block mb-2">Study Confirmation</label>
                                    @if (auth()->user()->study_confirmation_image)
                                        <img id="studyPreview" src="{{ asset(auth()->user()->study_confirmation_image) }}"
                                            class="img-thumbnail mb-2" style="max-width: 150px;">
                                    @else
                                        <img id="studyPreview" src="{{ asset('images/default-study.png') }}"
                                            class="img-thumbnail mb-2" style="max-width: 150px;">
                                    @endif
                                    <input type="file" class="form-control-file d-inline-block"
                                        id="study_confirmation_image" name="study_confirmation_image" accept="image/*">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" id="profileSubmit" class="btn btn-primary btn-lg">
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Ensure jQuery is loaded
            if (typeof jQuery === 'undefined') {
                console.error('jQuery is not loaded');
                return;
            }

            // Clear previous validation errors
            function clearErrors() {
                $('.form-control, .form-control-file').removeClass('is-invalid');
                $('.invalid-feedback').empty();
            }

            // Show validation errors
            function showValidationErrors(errors) {
                clearErrors();
                $.each(errors, function(field, messages) {
                    let fieldElement = $(`[name="${field}"]`);
                    fieldElement.addClass('is-invalid');
                    fieldElement.siblings('.invalid-feedback').html(messages[0]);
                });
            }

            // Show alert message
            function showAlert(type, message) {
                let alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                let alertBox = `
                    <div class="alert ${alertClass} alert-dismissible fade show mt-3" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
                $('.main-content').prepend(alertBox);

                setTimeout(() => {
                    $('.alert').alert('close');
                }, 4000);
            }

            // Preview image before upload
            function previewImage(input, previewId) {
                if (input.files && input.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $(`#${previewId}`).attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Attach preview handlers
            $('#profile_image').on('change', function() { previewImage(this, 'profilePreview'); });
            $('#passport_image').on('change', function() { previewImage(this, 'passportPreview'); });
            $('#trc_image').on('change', function() { previewImage(this, 'trcPreview'); });
            $('#study_confirmation_image').on('change', function() { previewImage(this, 'studyPreview'); });

            // Form submission
            $('#profileForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                let form = this;
                let formData = new FormData(form);
                let submitBtn = $('#profileSubmit');

                // Disable button while submitting
                submitBtn.prop('disabled', true).text('Updating...');

                $.ajax({
                    url: "{{ route('profile.update') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            // Show success message
                            showAlert('success', res.message);

                            // Update form fields
                            $('#name').val(res.user.name);
                            $('#email').val(res.user.email);
                            $('#phone').val(res.user.phone);
                            $('#address').val(res.user.address);
                            $('#password').val('');

                            // Update image previews
                            if (res.user.image) {
                                $('#profilePreview').attr('src', res.user.image);
                            }
                            if (res.user.passport_image) {
                                $('#passportPreview').attr('src', res.user.passport_image);
                            }
                            if (res.user.trc_image) {
                                $('#trcPreview').attr('src', res.user.trc_image);
                            }
                            if (res.user.study_confirmation_image) {
                                $('#studyPreview').attr('src', res.user.study_confirmation_image);
                            }

                            // Clear any previous errors
                            clearErrors();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            showValidationErrors(errors);
                        } else {
                            showAlert('error', 'Something went wrong! Please try again.');
                        }
                    },
                    complete: function() {
                        // Enable button after request
                        submitBtn.prop('disabled', false).text('Update Profile');
                    }
                });
            });
        });
    </script>
@endpush
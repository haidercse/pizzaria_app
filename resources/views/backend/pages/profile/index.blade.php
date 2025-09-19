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
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" value="{{ auth()->user()->name ?? '' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" value="{{ auth()->user()->email ?? '' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                                    <small class="form-text text-muted">Leave blank if you don't want to change it.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Your Phone Number" value="{{ auth()->user()->phone ?? '' }}">
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Your Address">{{ auth()->user()->address ?? '' }}</textarea>
                            </div>
                            
                            <hr>

                            <div class="row">
                                <div class="col-md-6 mb-3 text-center">
                                    <label for="profile_image" class="d-block mb-2">Your Profile Image</label>
                                    @if(auth()->user()->image)
                                        <img src="{{ asset(auth()->user()->image) }}" alt="Profile Image" class="img-thumbnail mb-2" style="max-width: 150px;">
                                    @endif
                                    <input type="file" class="form-control-file d-inline-block" id="profile_image" name="image">
                                </div>
                                <div class="col-md-6 mb-3 text-center">
                                    <label for="passport_image" class="d-block mb-2">Your Passport</label>
                                    @if(auth()->user()->passport_image)
                                        <img src="{{ asset(auth()->user()->passport_image) }}" alt="Passport" class="img-thumbnail mb-2" style="max-width: 150px;">
                                    @endif
                                    <input type="file" class="form-control-file d-inline-block" id="passport_image" name="passport_image">
                                </div>
                                <div class="col-md-6 mb-3 text-center">
                                    <label for="trc_image" class="d-block mb-2">Your TRC</label>
                                    @if(auth()->user()->trc_image)
                                        <img src="{{ asset(auth()->user()->trc_image) }}" alt="TRC" class="img-thumbnail mb-2" style="max-width: 150px;">
                                    @endif
                                    <input type="file" class="form-control-file d-inline-block" id="trc_image" name="trc_image">
                                </div>
                                <div class="col-md-6 mb-3 text-center">
                                    <label for="study_confirmation_image" class="d-block mb-2">Study Confirmation</label>
                                    @if(auth()->user()->study_confirmation_image)
                                        <img src="{{ asset(auth()->user()->study_confirmation_image) }}" alt="Study Confirmation" class="img-thumbnail mb-2" style="max-width: 150px;">
                                    @endif
                                    <input type="file" class="form-control-file d-inline-block" id="study_confirmation_image" name="study_confirmation_image">
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
@endsection
@extends('backend.layouts.master')
@section('title')
    User Page
@endsection

@section('page-title', 'User List')
@section('breadcrumb-home_route', route('users.create'))
@section('breadcrumb-home_title', 'User Add')
@section('breadcrumb-current', 'User List')


@section('admin-content')
    <div class="main-content">
        @include('backend.layouts.partials.message')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- Wrap the table in a responsive div for mobile support --}}
                        <div class="table-responsive">
                            {{-- Add Bootstrap styling classes for a better look --}}
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Image</th>
                                        <th>Passport Image</th>
                                        <th>TRC Image</th>
                                        <th>Study Confirmation Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Use @forelse to handle the case where no data is found --}}
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->address }}</td>
                                            <td>
                                                <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}" class="img-fluid" style="max-width: 100px;">
                                            </td>
                                            <td>
                                                <img src="{{ asset('storage/' . $user->passport_image) }}" alt="{{ $user->name }}" class="img-fluid" style="max-width: 100px;">
                                            </td>
                                            <td>
                                                <img src="{{ asset('storage/' . $user->trc_image) }}" alt="{{ $user->name }}" class="img-fluid" style="max-width: 100px;">
                                            </td>
                                            <td>
                                                <img src="{{ asset('storage/' . $user->study_confirmation_image) }}" alt="{{ $user->name }}" class="img-fluid" style="max-width: 100px;">
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column align-items-start">
                                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm mb-1"><i class="fa fa-edit"></i></a>

                                                    <a href="{{ route('users.restore.password', $user->id) }}" class="btn btn-warning btn-sm mb-1"><i class="fa fa-refresh"></i></a>
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No user data found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
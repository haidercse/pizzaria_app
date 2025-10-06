@extends('backend.layouts.master')

@section('title', 'User Management')
@section('page-title', 'User List')
@section('breadcrumb-home_route', route('users.index'))
@section('breadcrumb-home_title', 'Users')
@section('breadcrumb-current', 'List')
@push('styles')
    <style>
        .btn-group .btn i {
            transition: transform 0.2s ease;
        }

        .btn-group .btn:hover i {
            transform: scale(1.2);
        }
    </style>
@endpush
@php
    $authUser = Auth::user();
@endphp

@section('admin-content')
    <div class="main-content">
        <button class="btn btn-success ml-3" id="addUserBtn">+ Add User</button>
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle" id="userTable">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Contract Type</th>
                            <th>Salary</th>
                            <th>Profile</th>
                            <th>Passport</th>
                            <th>TRC</th>
                            <th>Study Confirmation</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr data-id="{{ $user->id }}" data-role-id="{{ $user->roles->first()->id ?? '' }}">
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->contract->type ?? '-' }}</td>
                                <td>{{ $user->contract->hourly_rate ?? '-' }}</td>
                                <td>
                                    @if ($user->image)
                                        <img src="{{ asset($user->image) }}" class="img-thumbnail preview-img"
                                            data-img="{{ asset($user->image) }}"
                                            style="width:50px;height:50px;cursor:pointer;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($user->passport_image)
                                        <img src="{{ asset($user->passport_image) }}" class="img-thumbnail preview-img"
                                            data-img="{{ asset($user->passport_image) }}"
                                            style="width:50px;height:50px;cursor:pointer;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($user->trc_image)
                                        <img src="{{ asset($user->trc_image) }}" class="img-thumbnail preview-img"
                                            data-img="{{ asset($user->trc_image) }}"
                                            style="width:50px;height:50px;cursor:pointer;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($user->study_confirmation_image)
                                        <img src="{{ asset($user->study_confirmation_image) }}"
                                            class="img-thumbnail preview-img"
                                            data-img="{{ asset($user->study_confirmation_image) }}"
                                            style="width:50px;height:50px;cursor:pointer;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-primary editBtn" data-toggle="tooltip" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger deleteBtn" data-toggle="tooltip" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <button class="btn btn-warning resetBtn" data-toggle="tooltip"
                                            title="Reset Password">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- User Form Modal --}}
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form id="userForm">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">User Form</h5>
                    </div>

                    <div class="modal-body bg-light">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" class="form-control border-success" name="name" id="name"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control border-success" name="email" id="email"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phone</label>
                                <input type="text" class="form-control border-success" name="phone" id="phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Contract Type</label>
                                <select name="contract_type" id="contract_type" class="form-control border-success">
                                    <option value="full_time">Full Time</option>
                                    <option value="part_time">Part Time</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Role Assign</label>
                                <select name="role" id="role" class="form-control border-success">
                                    <option value="">-- Select Role --</option>
                                    @php
                                        $roles = \Spatie\Permission\Models\Role::where(
                                            'name',
                                            '!=',
                                            'super admin',
                                        )->get();
                                    @endphp
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Salary</label>
                                <input type="number" step="0.01" class="form-control border-success" name="salary"
                                    id="salary">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" id="saveBtn" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Image Preview Modal --}}
    <div class="modal fade" id="imagePreviewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <img id="previewImage" src="" class="img-fluid rounded">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let modal = new bootstrap.Modal(document.getElementById('userModal'));
            let imageModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));

            // ✅ Image click => show preview
            $(document).on('click', '.preview-img', function() {
                let imgSrc = $(this).data('img');
                $('#previewImage').attr('src', imgSrc);
                imageModal.show();
            });

            // ✅ Success Message
            function showMessage(type, message) {
                let alertClass = (type === 'success') ? 'alert-success' : 'alert-danger';
                let alertBox = `
                <div class="alert ${alertClass} alert-dismissible fade show mt-3" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                $('.main-content').prepend(alertBox);
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 4000);
            }

            // ✅ Add Button Click
            $('#addUserBtn').click(function() {
                $('#userForm')[0].reset();
                $('#user_id').val('');
                $('#role').val('');
                modal.show();
            });

            // ✅ Save or Update
            $('#userForm').submit(function(e) {
                e.preventDefault();
                let id = $('#user_id').val();
                let url = id ? `/users/${id}` : "{{ route('users.store') }}";
                let formData = new FormData(this);
                if (id) formData.append('_method', 'PUT');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            modal.hide();
                            showMessage('success', res.message);
                            location.reload(); // simple reload for full sync
                        }
                    },
                    error: function() {
                        showMessage('error', 'Something went wrong!');
                    }
                });
            });

            // ✅ Reset Password
            $(document).on('click', '.resetBtn', function() {
                let id = $(this).closest('tr').data('id');
                if (confirm('Are you sure to reset password?')) {
                    $.ajax({
                        url: `/users/${id}/reset-password`,
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            if (res.success) showMessage('success', res.message);
                        },
                        error: function() {
                            showMessage('error', 'Failed to reset password!');
                        }
                    });
                }
            });

            // ✅ Edit User
            $(document).on('click', '.editBtn', function() {
                let tr = $(this).closest('tr');
                let id = tr.data('id');
                $('#user_id').val(id);
                $('#name').val(tr.find('td:eq(0)').text());
                $('#email').val(tr.find('td:eq(1)').text());
                $('#phone').val(tr.find('td:eq(2)').text());
                $('#contract_type').val(tr.find('td:eq(3)').text().toLowerCase());
                $('#salary').val(tr.find('td:eq(4)').text());

                // ✅ Role selected set properly
                let roleId = tr.data('role-id');
                $('#role').val(roleId).change();

                modal.show();
            });

            // ✅ Delete User
            $(document).on('click', '.deleteBtn', function() {
                if (confirm('Are you sure?')) {
                    let id = $(this).closest('tr').data('id');
                    $.ajax({
                        url: `/users/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            if (res.success) {
                                $(`#userTable tr[data-id='${id}']`).remove();
                                showMessage('success', res.message);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush

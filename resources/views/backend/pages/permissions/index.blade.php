@extends('backend.layouts.master')

@section('title', 'Permission Management')

@section('admin-content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Permission List</h3>
                <button class="btn btn-primary" id="addPermissionBtn">Add Permission</button>
            </div>
            <div class="card-body" id="permissionTable">
                <table class="table table-bordered" id="dataTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Group</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr id="row_{{ $permission->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td class="permission_group">{{ $permission->group_name }}</td>
                                <td class="permission_name">{{ $permission->name }}</td>
                                <td>
                                    <button class="btn btn-success editPermission"
                                        data-id="{{ $permission->id }}"
                                        data-name="{{ $permission->name }}"
                                        data-group="{{ $permission->group_name }}">Edit</button>
                                    <button class="btn btn-danger deletePermission"
                                        data-id="{{ $permission->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="permissionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="permissionForm">
                    @csrf
                    <input type="hidden" id="permission_id" name="id">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Permission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="permissionFields">
                            <div class="form-group mb-2">
                                <label for="group_name">Group Name</label>
                                <input type="text" name="group_name" id="group_name" class="form-control"
                                    placeholder="Enter Group Name" required>
                            </div>

                            <div class="form-group d-flex mb-2 permission-row">
                                <input type="text" name="name[]" class="form-control me-2" placeholder="Permission Name"
                                    required>
                                <button type="button" class="btn btn-success addMoreBtn">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            function refreshTableSerials() {
                $('#permissionTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }

            // Open modal - Add
            $('#addPermissionBtn').click(function() {
                $('#permissionForm')[0].reset();
                $('#permission_id').val('');
                $('#permissionFields').html(`
                    <div class="form-group mb-2">
                        <label for="group_name">Group Name</label>
                        <input type="text" name="group_name" id="group_name" class="form-control"
                            placeholder="Enter Group Name" required>
                    </div>
                    <div class="form-group d-flex mb-2 permission-row">
                        <input type="text" name="name[]" class="form-control me-2" placeholder="Permission Name" required>
                        <button type="button" class="btn btn-success addMoreBtn">+</button>
                    </div>
                `);
                $('#permissionModal .modal-title').text('Add Permission');
                $('#permissionModal').modal('show');
            });

            // Dynamic add/remove
            $(document).on('click', '.addMoreBtn', function() {
                var newField = `
                    <div class="form-group d-flex mb-2 permission-row">
                        <input type="text" name="name[]" class="form-control me-2" placeholder="Permission Name" required>
                        <button type="button" class="btn btn-danger removeBtn">-</button>
                    </div>`;
                $('#permissionFields').append(newField);
            });
            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.permission-row').remove();
            });

            // Edit
            $(document).on('click', '.editPermission', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var group = $(this).data('group');

                $('#permission_id').val(id);

                $('#permissionFields').html(`
                    <div class="form-group mb-2">
                        <label for="group_name">Group Name</label>
                        <input type="text" name="group_name" id="group_name" class="form-control"
                            value="${group}" required>
                    </div>
                    <div class="form-group d-flex mb-2 permission-row">
                        <input type="text" name="name[]" class="form-control me-2" value="${name}" required>
                    </div>
                `);

                $('#permissionModal .modal-title').text('Edit Permission');
                $('#permissionModal').modal('show');
            });

            // Submit form
            $('#permissionForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('permissions.storeOrUpdate') }}",
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        if (res.status === 'success') {
                            if ($('#permission_id').val() === '') {
                                // New added permissions dynamically append
                                res.permissions.forEach(function(permission) {
                                    $('#permissionTable tbody').append(`
                                        <tr id="row_${permission.id}">
                                            <td></td>
                                            <td class="permission_group">${permission.group_name}</td>
                                            <td class="permission_name">${permission.name}</td>
                                            <td>
                                                <button class="btn btn-success editPermission"
                                                    data-id="${permission.id}"
                                                    data-name="${permission.name}"
                                                    data-group="${permission.group_name}">Edit</button>
                                                <button class="btn btn-danger deletePermission"
                                                    data-id="${permission.id}">Delete</button>
                                            </td>
                                        </tr>
                                    `);
                                });
                                refreshTableSerials();
                            } else {
                                // Update existing row
                                var row = $('#row_' + res.permissions[0].id);
                                row.find('.permission_group').text(res.permissions[0].group_name);
                                row.find('.permission_name').text(res.permissions[0].name);
                            }
                            $('#permissionModal').modal('hide');
                            alert(res.message);
                        }
                    },
                    error: function(err) {
                        console.log(err.responseJSON);
                        alert('Something went wrong!');
                    }
                });
            });

            // Delete
            $(document).on('click', '.deletePermission', function() {
                if (!confirm('Are you sure?')) return;
                var id = $(this).data('id');

                $.ajax({
                    url: `/admin/permissions/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.status === 'success') {
                            $('#row_' + id).remove();
                            refreshTableSerials();
                            alert(res.message);
                        }
                    }
                });
            });

        });
    </script>
@endpush

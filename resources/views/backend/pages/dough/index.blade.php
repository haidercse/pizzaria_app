@extends('backend.layouts.master')

@section('title', 'Manage Dough')

@section('admin-content')
    <div class="container my-4">

        <h3 class="mb-4 text-success fw-bold text-center">Dough List</h3>

        {{-- Success & Error Alerts --}}
        <div id="alertBox" class="mt-2"></div>

        {{-- Add Button --}}
        <div class="mb-3 text-end">
            <button class="btn btn-success" id="addDoughBtn">Add New Dough</button>
        </div>

        {{-- Dough List --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">Dough Records</div>
            <div class="card-body" id="doughTableWrapper">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center" id="dataTable">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Dough Litter</th>
                                <th>Total Weight</th>
                                <th>Num Of Cajas</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($doughs as $dough)
                                <tr id="dough-{{ $dough->id }}">
                                    <td>{{ $dough->day }}</td>
                                    <td>{{ $dough->dough_litter }}</td>
                                    <td>{{ $dough->dough_total_weight }}</td>
                                    <td>{{ $dough->dough_num_of_cajas }}</td>
                                    <td>{{ $dough->date }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-dough" data-id="{{ $dough->id }}"><i
                                                class="fa fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger delete-dough" data-id="{{ $dough->id }}"><i
                                                class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-muted">No records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="doughModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalTitle">Add Dough</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="doughForm">
                        @csrf
                        <input type="hidden" id="dough_id" name="id">
                        <div class="mb-3 form-group">
                            <label class="form-label fw-semibold">Dough Litter</label>
                            <select name="dough_litter" id="dough_litter" class="form-control">
                                <option value="3">3</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                            </select>
                            {{-- <input type="number" class="form-control" id="dough_litter" name="dough_litter" min="10"
                                max="16" required> --}}

                        </div>

                        <button type="submit" class="btn btn-success w-100" id="saveBtn">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // Show Alert Function
            function showAlert(type, message) {
                let alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                let alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
                $('#alertBox').html(alertHtml);

                // Auto hide after 4s
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 4000);
            }

            // Add Button Click
            $('#addDoughBtn').click(function() {
                $('#modalTitle').text("Add Dough");
                $('#doughForm')[0].reset();
                $('#dough_id').val('');
                $('#saveBtn').removeClass('btn-warning').addClass('btn-success').text("Save");
                $('#doughModal').modal('show');
            });

            // Save / Update
            $('#doughForm').submit(function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let id = $('#dough_id').val();
                let url = id ? `/dough/${id}` : "{{ route('dough.store') }}";
                let method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function(res) {
                        $('#doughModal').modal('hide');
                        reloadTable();
                        showAlert('success', res.message || "Dough saved successfully!");
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Validation error
                            let errors = xhr.responseJSON.errors;
                            let firstError = Object.values(errors)[0][0];
                            showAlert('error', firstError);
                        } else if (xhr.status === 400) {
                            // Already inserted error
                            let msg = xhr.responseJSON.message || "Already inserted for today!";
                            showAlert('error', msg);
                        } else {
                            showAlert('error', "Something went wrong!");
                        }
                    }
                });
            });

            // Edit
            $(document).on('click', '.edit-dough', function() {
                let id = $(this).data('id');
                $.get(`/dough/${id}/get`, function(data) {
                    $('#modalTitle').text("Edit Dough");
                    $('#dough_id').val(data.id);
                    $('#dough_litter').val(data.dough_litter);
                    $('#saveBtn').removeClass('btn-success').addClass('btn-warning').text(
                        "Update");
                    $('#doughModal').modal('show');
                });
            });

            // Delete
            $(document).on('click', '.delete-dough', function() {
                if (!confirm("Are you sure to delete?")) return;
                let id = $(this).data('id');
                $.ajax({
                    url: `/dough/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        $(`#dough-${id}`).remove();
                        showAlert('success', res.message || "Dough deleted successfully!");
                    },
                    error: function() {
                        showAlert('error', "Failed to delete record!");
                    }
                });
            });

            // Reload Table
            function reloadTable() {
                $.get("{{ route('dough.index') }}", function(data) {
                    $('#doughTableWrapper').html($(data).find('#doughTableWrapper').html());
                });
            }

        });
    </script>
@endpush

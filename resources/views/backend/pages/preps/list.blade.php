@extends('backend.layouts.master')
@section('title', 'Manage Preps')

@section('admin-content')
    <div class="container my-4">

        <h3 class="mb-4 text-success fw-bold text-center">Manage Preps</h3>

        {{-- Add Prep Button --}}
        <div class="mb-3 text-end">
            <a href="{{ route('preps.create') }}" class="btn btn-success">Add New Prep</a>
        </div>

        {{-- List --}}
        <div class="card-body" id="prepsTable">
            <table class="table table-hover align-middle text-center shadow-sm" id="dataTable">
                <thead class="table-success">
                    <tr>
                        <th style="width:60%">Name</th>
                        <th style="width:40%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($preps as $prep)
                        <tr id="prep-{{ $prep->id }}">
                            <td class="fw-semibold text-dark">{{ $prep->name }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary rounded-pill edit-prep me-2"
                                    data-id="{{ $prep->id }}">
                                    ✏️ Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill delete-prep"
                                    data-id="{{ $prep->id }}">
                                    🗑 Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-muted fst-italic">No preps found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit Prep</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="editFormContainer">
                    {{-- Ajax form will load here --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            //data table
            

            // Load Preps list (for after add/edit/delete)
            function loadPreps() {
                $.get("{{ route('preps.index') }}", function(data) {
                    $('#prepsTable').html($(data).find('#prepsTable').html());
                });
            }

            // Delete Prep
            $(document).on('click', '.delete-prep', function() {
                let id = $(this).data('id');
                if (!confirm("Are you sure to delete this prep?")) return;

                $.ajax({
                    url: `/preps/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.success) {
                            alert('Prep deleted successfully!');
                            $(`#prep-${id}`).remove();
                            loadPreps();
                        }
                    }
                });
            });

            // Edit Prep
            $(document).on('click', '.edit-prep', function() {
                let id = $(this).data('id');

                $.get(`/preps/${id}/edit`, function(data) {
                    $('#editFormContainer').html(`
                <form id="updatePrepForm">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" value="${data.name}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ingredients</label>
                        
                        <textarea id="edit-ingredients" name="ingredients" class="form-control summernote">${data.ingredients}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Process</label>
                        
                        <textarea id="edit-process" name="process" class="form-control summernote">${data.process}</textarea>
                    </div>
                    <button type="submit" class="btn btn-warning">Update</button>
                </form>
            `);

                    $('.summernote').summernote({
                        height: 150,
                    });

                    $('#editModal').modal('show');

                    // Update Prep
                    $('#updatePrepForm').on('submit', function(e) {
                        e.preventDefault();
                        $.ajax({
                            url: `/preps/${id}`,
                            method: 'POST',
                            data: $(this).serialize(),
                            success: function(res) {
                                if (res.success) {
                                    alert('Prep updated successfully!');
                                    loadPreps();
                                    $('#editModal').modal('hide');
                                }
                            }
                        });
                    });
                });
            });

        });

        // Formatting buttons for Summernote
        function format(command, id) {
            let el = document.getElementById(id);
            el.focus();
            document.execCommand(command, false, null);
        }
    </script>
@endpush

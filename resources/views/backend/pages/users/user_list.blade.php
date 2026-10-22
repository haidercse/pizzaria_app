
@extends('backend.layouts.master')

@section('title', 'User Management')
@section('page-title', 'User List')
@section('breadcrumb-home_route', route('users.index'))
@section('breadcrumb-home_title', 'Users')
@section('breadcrumb-current', 'List')

@php
    $imageUrl = function ($path) {
        if (!$path) {
            return null;
        }

        $path = trim(str_replace('\\', '/', $path));

        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        // Normalize paths saved by both profile uploads and storage-based uploads.
        $path = preg_replace('#^(\./)+#', '', $path);
        $path = preg_replace('#^public/#', '', $path);
        if (preg_match('#(?:^|/)(uploads/.*|images/.*|storage/.*)$#i', $path, $matches)) {
            $path = $matches[1];
        }

        if (str_starts_with($path, 'storage/app/public/')) {
            $path = substr($path, strlen('storage/app/public/'));
        }

        if (is_file(public_path($path))) {
            return asset($path);
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }

        // Keep a public URL as a fallback for deployments using a remote/static file host.
        return asset($path);
    };
@endphp

@push('styles')
<style>
    .user-management {
        --user-border: #e6eaf0;
        --user-muted: #748094;
    }

    .user-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }

    .toolbar-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin: 0;
    }

    .toolbar-subtitle {
        font-size: .84rem;
        color: var(--user-muted);
        margin-top: 5px;
    }

    .user-table-card,
    .user-mobile-card {
        background: #fff;
        border: 1px solid var(--user-border);
        border-radius: 12px;
    }

    .user-table-card {
        overflow: hidden;
    }

    .user-table-scroll {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    #userTable {
        width: 100%;
        min-width: 900px;
        margin-bottom: 0;
        font-size: .85rem;
    }

    #userTable th {
        background: #f6f8fb;
        color: #566176;
        text-transform: uppercase;
        font-size: .73rem;
        letter-spacing: .025em;
        white-space: nowrap;
        padding: 13px 10px;
    }

    #userTable td {
        padding: 10px;
        vertical-align: middle;
    }

    .user-identity {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 185px;
    }

    .user-profile-thumb,
    .user-avatar-fallback {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 50%;
        object-fit: cover;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eaf5ee;
        color: #198754;
        font-weight: 700;
    }

    .user-identity-name {
        font-weight: 700;
        color: #263347;
        overflow-wrap: anywhere;
    }

    .user-identity-email {
        display: block;
        color: var(--user-muted);
        font-size: .77rem;
        margin-top: 3px;
        overflow-wrap: anywhere;
    }

    .contract-badge {
        display: inline-block;
        background: #edf7f0;
        color: #187443;
        padding: 5px 8px;
        border-radius: 6px;
        font-size: .76rem;
        white-space: nowrap;
    }

    .user-document-list {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .document-thumb {
        width: 38px;
        height: 38px;
        flex-shrink: 0;
        object-fit: cover;
        border: 1px solid #dfe5ed;
        border-radius: 7px;
        cursor: zoom-in;
        background: #f3f5f8;
    }

    .document-thumb:hover {
        border-color: #198754;
        transform: scale(1.06);
    }

    .document-placeholder {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 7px;
        background: #f1f3f6;
        color: #8b95a5;
    }

    .schedule-color-cell {
        min-width: 100px;
        text-align: center;
    }

    .schedule-color-swatch {
        display: inline-block;
        width: 18px;
        height: 18px;
        vertical-align: middle;
        border: 1px solid #d5dae1;
        border-radius: 5px;
    }

    .schedule-color-value {
        font-size: .73rem;
    }

    .user-schedule-color {
        display: block;
        width: 38px !important;
        height: 28px !important;
        padding: 2px !important;
        margin: 5px auto 0;
        cursor: pointer;
    }

    .user-actions {
        display: flex;
        justify-content: center;
        gap: 5px;
    }

    .user-actions .btn {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .user-mobile-list {
        display: none;
    }

    .user-mobile-card {
        padding: 14px;
        margin-bottom: 12px;
        box-shadow: 0 3px 12px rgba(25, 35, 50, .04);
    }

    .mobile-user-header {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .mobile-user-header .user-identity {
        min-width: 0;
        flex: 1;
    }

    .mobile-user-details {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        padding-top: 14px;
        margin-top: 14px;
        border-top: 1px solid var(--user-border);
    }

    .mobile-detail-label {
        display: block;
        color: var(--user-muted);
        font-size: .75rem;
        margin-bottom: 4px;
    }

    .mobile-detail-value {
        font-size: .86rem;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .mobile-document-row,
    .mobile-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid var(--user-border);
    }

    .mobile-document-row .user-document-list {
        flex-wrap: wrap;
    }

    .mobile-card-footer .user-actions {
        flex-shrink: 0;
    }

    .mobile-schedule-color {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 5px;
    }

    .mobile-schedule-color .user-schedule-color {
        margin: 0;
    }

    #imagePreviewModal {
        z-index: 1060;
    }

    #imagePreviewModal .modal-dialog {
        width: calc(100% - 24px);
        max-width: 900px;
        margin: 1.75rem auto;
    }

    #imagePreviewModal .modal-content {
        position: relative;
        background: #171b22;
        border: 0;
        border-radius: 12px;
        padding: 12px;
    }

    #previewImage {
        display: block;
        width: auto;
        max-width: 100%;
        max-height: 82vh;
        margin: auto;
        object-fit: contain;
    }

    .image-preview-close {
        position: absolute;
        z-index: 2;
        top: 8px;
        right: 12px;
        border: 0;
        background: rgba(0, 0, 0, .7);
        color: #fff;
        border-radius: 50%;
        width: 34px;
        height: 34px;
        font-size: 23px;
        line-height: 1;
    }

    .image-preview-error {
        color: #fff;
        text-align: center;
        padding: 35px 10px;
    }

    @media (max-width: 767.98px) {
        .user-desktop-table {
            display: none !important;
        }

        .user-mobile-list {
            display: block;
        }

        .user-management .card-body {
            padding: 12px;
        }

        .user-toolbar {
            align-items: flex-start;
        }

        .user-toolbar .btn {
            padding: 9px 12px;
        }
    }

    @media (min-width: 768px) {
        .user-mobile-list {
            display: none !important;
        }
    }

    @media (max-width: 380px) {
        .user-mobile-card {
            padding: 11px;
        }

        .mobile-user-details {
            gap: 10px;
        }

        .user-profile-thumb,
        .user-avatar-fallback {
            width: 36px;
            height: 36px;
            min-width: 36px;
        }

        .user-actions {
            gap: 3px;
        }

        .user-actions .btn {
            width: 29px;
            height: 29px;
        }
    }
</style>
@endpush

@section('admin-content')
<div class="main-content user-management">

    <div class="user-toolbar">
        <div>
            <h5 class="toolbar-title">User Management</h5>
            <div class="toolbar-subtitle">
                Manage employees, documents and schedule colors
            </div>
        </div>

        <button type="button" class="btn btn-success" id="addUserBtn">
            <i class="fa fa-plus"></i> Add User
        </button>
    </div>

    <div id="userMessages"></div>

    <div class="card shadow-sm">
        <div class="card-body">

            {{-- DESKTOP TABLE --}}
            <div class="user-desktop-table user-table-card">
                <div class="user-table-scroll">
                    <table class="table table-hover" id="userTable">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Phone</th>
                                <th>Contract</th>
                                <th>Salary / Rate</th>
                                <th>Documents</th>
                                <th>Schedule</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($users as $user)
                            @php
                                $color = preg_match('/^#[0-9a-fA-F]{6}$/', $user->color ?? '')
                                    ? $user->color : '#4a90d9';

                                $profile = $imageUrl($user->image);
                                $documents = [
                                    ['path' => $imageUrl($user->passport_image), 'label' => 'Passport'],
                                    ['path' => $imageUrl($user->trc_image), 'label' => 'TRC'],
                                    ['path' => $imageUrl($user->study_confirmation_image), 'label' => 'Study Confirmation'],
                                ];

                                $roleId = $user->roles->first()->id ?? '';
                                $contractType = $user->contract->type ?? '';
                                $salary = $user->contract->hourly_rate ?? '';
                            @endphp

                            <tr
                                data-id="{{ $user->id }}"
                                data-role-id="{{ $roleId }}"
                                data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}"
                                data-phone="{{ $user->phone }}"
                                data-contract="{{ $contractType }}"
                                data-salary="{{ $salary }}">

                                <td>
                                    <div class="user-identity">
                                        @if ($profile)
                                            <img src="{{ $profile }}"
                                                class="user-profile-thumb preview-img"
                                                data-img="{{ $profile }}"
                                                alt="Profile"
                                                loading="lazy"
                                                onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex'">
                                            <span class="user-avatar-fallback" style="display:none">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        @else
                                            <span class="user-avatar-fallback">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        @endif

                                        <div>
                                            <div class="user-identity-name">{{ $user->name }}</div>
                                            <span class="user-identity-email">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ $user->phone ?: '-' }}</td>

                                <td>
                                    <span class="contract-badge">
                                        {{ $contractType ? ucfirst(str_replace('_', ' ', $contractType)) : '-' }}
                                    </span>
                                </td>

                                <td>{{ $salary !== '' ? $salary : '-' }}</td>

                                <td>
                                    <div class="user-document-list">
                                        @foreach ($documents as $document)
                                            @if ($document['path'])
                                                <img src="{{ $document['path'] }}"
                                                    class="document-thumb preview-img"
                                                    data-img="{{ $document['path'] }}"
                                                    alt="{{ $document['label'] }}"
                                                    title="{{ $document['label'] }}"
                                                    loading="lazy"
                                                    onerror="this.onerror=null;this.style.display='none';this.insertAdjacentHTML('afterend','<span class=&quot;document-placeholder&quot; title=&quot;Image unavailable&quot;><i class=&quot;fa fa-exclamation-triangle&quot;></i></span>')">
                                            @else
                                                <span class="document-placeholder" title="{{ $document['label'] }} missing">
                                                    <i class="fa fa-file-o"></i>
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>

                                <td class="schedule-color-cell">
                                    <span class="schedule-color-swatch" style="background:{{ $color }}"></span>
                                    <code class="schedule-color-value">{{ $color }}</code>

                                    <input type="color"
                                        class="form-control user-schedule-color"
                                        value="{{ $color }}"
                                        data-saved-color="{{ $color }}"
                                        data-url="{{ route('users.color', $user) }}"
                                        aria-label="Schedule color for {{ $user->name }}">
                                </td>

                                <td>
                                    <div class="user-actions">
                                        <button type="button" class="btn btn-primary editBtn" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger deleteBtn" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning resetBtn" title="Reset password">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No users found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- MOBILE CARDS --}}
            <div class="user-mobile-list">
                @forelse ($users as $user)
                    @php
                        $color = preg_match('/^#[0-9a-fA-F]{6}$/', $user->color ?? '')
                            ? $user->color : '#4a90d9';

                        $profile = $imageUrl($user->image);
                        $documents = [
                            ['path' => $imageUrl($user->passport_image), 'label' => 'Passport'],
                            ['path' => $imageUrl($user->trc_image), 'label' => 'TRC'],
                            ['path' => $imageUrl($user->study_confirmation_image), 'label' => 'Study Confirmation'],
                        ];

                        $roleId = $user->roles->first()->id ?? '';
                        $contractType = $user->contract->type ?? '';
                        $salary = $user->contract->hourly_rate ?? '';
                    @endphp

                    <div class="user-mobile-card"
                        data-id="{{ $user->id }}"
                        data-role-id="{{ $roleId }}"
                        data-name="{{ $user->name }}"
                        data-email="{{ $user->email }}"
                        data-phone="{{ $user->phone }}"
                        data-contract="{{ $contractType }}"
                        data-salary="{{ $salary }}">

                        <div class="mobile-user-header">
                            <div class="user-identity">
                                @if ($profile)
                                    <img src="{{ $profile }}"
                                        class="user-profile-thumb preview-img"
                                        data-img="{{ $profile }}"
                                        alt="Profile"
                                        loading="lazy"
                                        onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex'">
                                    <span class="user-avatar-fallback" style="display:none">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                @else
                                    <span class="user-avatar-fallback">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                @endif

                                <div>
                                    <div class="user-identity-name">{{ $user->name }}</div>
                                    <span class="user-identity-email">{{ $user->email }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mobile-user-details">
                            <div>
                                <span class="mobile-detail-label">Phone</span>
                                <div class="mobile-detail-value">{{ $user->phone ?: '-' }}</div>
                            </div>

                            <div>
                                <span class="mobile-detail-label">Contract</span>
                                <div class="mobile-detail-value">
                                    {{ $contractType ? ucfirst(str_replace('_', ' ', $contractType)) : '-' }}
                                </div>
                            </div>

                            <div>
                                <span class="mobile-detail-label">Salary / Rate</span>
                                <div class="mobile-detail-value">{{ $salary !== '' ? $salary : '-' }}</div>
                            </div>

                            <div>
                                <span class="mobile-detail-label">Schedule color</span>
                                <div class="mobile-schedule-color">
                                    <span class="schedule-color-swatch" style="background:{{ $color }}"></span>
                                    <code class="schedule-color-value">{{ $color }}</code>
                                    <input type="color"
                                        class="form-control user-schedule-color"
                                        value="{{ $color }}"
                                        data-saved-color="{{ $color }}"
                                        data-url="{{ route('users.color', $user) }}"
                                        aria-label="Schedule color for {{ $user->name }}">
                                </div>
                            </div>
                        </div>

                        <div class="mobile-document-row">
                            <span class="mobile-detail-label mb-0">Documents</span>
                            <div class="user-document-list">
                                @foreach ($documents as $document)
                                    @if ($document['path'])
                                        <img src="{{ $document['path'] }}"
                                            class="document-thumb preview-img"
                                            data-img="{{ $document['path'] }}"
                                            alt="{{ $document['label'] }}"
                                            title="{{ $document['label'] }}"
                                            loading="lazy"
                                            onerror="this.onerror=null;this.style.display='none';this.insertAdjacentHTML('afterend','<span class=&quot;document-placeholder&quot; title=&quot;Image unavailable&quot;><i class=&quot;fa fa-exclamation-triangle&quot;></i></span>')">
                                    @else
                                        <span class="document-placeholder" title="{{ $document['label'] }} missing">
                                            <i class="fa fa-file-o"></i>
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="mobile-card-footer">
                            <span class="mobile-detail-label mb-0">Actions</span>
                            <div class="user-actions">
                                <button type="button" class="btn btn-primary editBtn" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger deleteBtn" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button type="button" class="btn btn-warning resetBtn" title="Reset password">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">No users found.</div>
                @endforelse
            </div>

        </div>
    </div>
</div>

{{-- USER FORM MODAL --}}
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <form id="userForm">
                @csrf
                <input type="hidden" id="user_id" name="user_id">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">User Form</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body bg-light">
                    <div id="userFormErrors" class="alert alert-danger d-none"></div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" name="phone" id="phone">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contract_type">Contract Type</label>
                            <select name="contract_type" id="contract_type" class="form-control">
                                <option value="full_time">Full Time</option>
                                <option value="part_time">Part Time</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="role">Role Assign</label>
                            <select name="role" id="role" class="form-control">
                                <option value="">-- Select Role --</option>
                                @foreach (\Spatie\Permission\Models\Role::where('name', '!=', 'super')->get() as $role)
                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="salary">Salary / Rate</label>
                            <input type="number" step="0.01" class="form-control" name="salary" id="salary">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveBtn" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- IMAGE PREVIEW MODAL --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog"
    aria-hidden="true" aria-label="Document preview">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="image-preview-close" data-dismiss="modal"
                aria-label="Close">&times;</button>

            <img id="previewImage" src="" alt="Document preview" style="display:none">

            <div id="imagePreviewError" class="image-preview-error" style="display:none">
                <i class="fa fa-exclamation-triangle fa-2x"></i>
                <p class="mt-2 mb-0">Image could not be loaded. Please check the saved file path.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const csrfToken = @json(csrf_token());
    const storeUrl = @json(route('users.store'));
    const userBaseUrl = @json(url('/users'));

    function showMessage(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const box = $('<div>', {
            class: 'alert ' + alertClass + ' alert-dismissible fade show mt-3',
            role: 'alert'
        });

        box.text(message || 'Request completed.');
        box.append(
            $('<button>', {
                type: 'button',
                class: 'close',
                'data-dismiss': 'alert',
                'aria-label': 'Close'
            }).html('<span aria-hidden="true">&times;</span>')
        );

        $('#userMessages').empty().append(box);
        setTimeout(function () {
            if (box.length && $.contains(document, box[0])) {
                box.alert('close');
            }
        }, 4000);
    }

    function showImagePreview(src) {
        $('#previewImage').hide().attr('src', '');
        $('#imagePreviewError').hide();

        if (!src) {
            $('#imagePreviewError').show();
            $('#imagePreviewModal').modal('show');
            return;
        }

        $('#imagePreviewModal').modal('show');
        $('#previewImage').attr('src', src);
    }

    // One delegated click handler for desktop and mobile thumbnails.
    $(document).on('click', '.preview-img', function () {
        showImagePreview($(this).attr('data-img') || $(this).attr('src'));
    });

    $('#previewImage').on('load', function () {
        $('#imagePreviewError').hide();
        $(this).show();
    });

    $('#previewImage').on('error', function () {
        $(this).hide();
        $('#imagePreviewError').show();
    });

    $('#imagePreviewModal').on('hidden.bs.modal', function () {
        $('#previewImage').off('load.preview error.preview');
        $('#previewImage').attr('src', '').hide();
        $('#imagePreviewError').hide();
    });

    $('#addUserBtn').on('click', function () {
        $('#userForm')[0].reset();
        $('#user_id').val('');
        $('#role').val('');
        $('#userFormErrors').addClass('d-none').empty();
        $('#userModal .modal-title').text('Add User');
        $('#userModal').modal('show');
    });

    // Create / update
    $('#userForm').on('submit', function (e) {
        e.preventDefault();

        const id = $('#user_id').val();
        const url = id ? userBaseUrl + '/' + encodeURIComponent(id) : storeUrl;
        const formData = new FormData(this);

        if (id) {
            formData.append('_method', 'PUT');
        }

        $('#saveBtn').prop('disabled', true).text('Saving...');

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': csrfToken },

            success: function (res) {
                if (res.success) {
                    $('#userModal').modal('hide');
                    showMessage('success', res.message || 'User saved successfully.');
                    // Reload ensures both desktop and mobile lists are synchronized.
                    location.reload();
                } else {
                    showMessage('error', res.message || 'Could not save user.');
                }
            },

            error: function (xhr) {
                const errors = xhr.responseJSON && xhr.responseJSON.errors;
                let message = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Something went wrong while saving the user.';

                if (errors) {
                    message = Object.values(errors).flat().join(' ');
                }

                $('#userFormErrors').removeClass('d-none').text(message);
            },

            complete: function () {
                $('#saveBtn').prop('disabled', false).text('Save');
            }
        });
    });

    // Edit: works for desktop rows and mobile cards.
    $(document).on('click', '.editBtn', function () {
        const row = $(this).closest('[data-id]');

        $('#userForm')[0].reset();
        $('#userFormErrors').addClass('d-none').empty();

        $('#user_id').val(row.attr('data-id'));
        $('#name').val(row.attr('data-name'));
        $('#email').val(row.attr('data-email'));
        $('#phone').val(row.attr('data-phone'));
        $('#contract_type').val(row.attr('data-contract'));
        $('#salary').val(row.attr('data-salary'));
        $('#role').val(row.attr('data-role-id'));

        $('#userModal .modal-title').text('Edit User');
        $('#userModal').modal('show');
    });

    // Reset password: supports desktop and mobile.
    $(document).on('click', '.resetBtn', function () {
        const row = $(this).closest('[data-id]');
        const id = row.attr('data-id');

        if (!id || !confirm('Are you sure you want to reset this password?')) {
            return;
        }

        $.ajax({
            url: userBaseUrl + '/' + encodeURIComponent(id) + '/reset-password',
            method: 'POST',
            data: { _token: csrfToken },

            success: function (res) {
                showMessage(
                    res.success ? 'success' : 'error',
                    res.message || (res.success ? 'Password reset successfully.' : 'Password reset failed.')
                );
            },

            error: function () {
                showMessage('error', 'Failed to reset password.');
            }
        });
    });

    // Schedule color update: works on desktop and mobile.
    $(document).on('change', '.user-schedule-color', function () {
        const picker = $(this);
        const selectedColor = picker.val();
        const previousColor = picker.attr('data-saved-color') || selectedColor;
        const url = picker.attr('data-url');
        const container = picker.closest('.schedule-color-cell, .mobile-schedule-color');

        container.find('.schedule-color-swatch').css('background-color', selectedColor);
        container.find('.schedule-color-value').text(selectedColor);
        picker.prop('disabled', true);

        $.ajax({
            url: url,
            method: 'PATCH',
            data: {
                _token: csrfToken,
                color: selectedColor
            },

            success: function (res) {
                if (res.success) {
                    picker.attr('data-saved-color', selectedColor);
                    showMessage('success', res.message || 'Schedule color updated.');
                } else {
                    picker.val(previousColor);
                    container.find('.schedule-color-swatch').css('background-color', previousColor);
                    container.find('.schedule-color-value').text(previousColor);
                    showMessage('error', res.message || 'Could not update schedule color.');
                }
            },

            error: function (xhr) {
                picker.val(previousColor);
                container.find('.schedule-color-swatch').css('background-color', previousColor);
                container.find('.schedule-color-value').text(previousColor);

                const errors = xhr.responseJSON && xhr.responseJSON.errors
                    ? Object.values(xhr.responseJSON.errors).flat().join(' ')
                    : '';

                showMessage('error', errors || 'Could not update schedule color.');
            },

            complete: function () {
                picker.prop('disabled', false);
            }
        });
    });

    // Delete: remove both the desktop row and mobile card.
    $(document).on('click', '.deleteBtn', function () {
        const row = $(this).closest('[data-id]');
        const id = row.attr('data-id');

        if (!id || !confirm('Are you sure you want to delete this user?')) {
            return;
        }

        $.ajax({
            url: userBaseUrl + '/' + encodeURIComponent(id),
            method: 'DELETE',
            data: { _token: csrfToken },

            success: function (res) {
                if (res.success) {
                    $('[data-id]').filter(function () {
                        return String($(this).attr('data-id')) === String(id) &&
                            ($(this).is('tr') || $(this).hasClass('user-mobile-card'));
                    }).remove();

                    showMessage('success', res.message || 'User deleted successfully.');
                } else {
                    showMessage('error', res.message || 'Could not delete user.');
                }
            },

            error: function () {
                showMessage('error', 'Failed to delete user.');
            }
        });
    });
});
</script>
@endpush

@extends('backend.layouts.master')

@section('title')
    Role | Role Management
@endsection
@push('styles')
    <style>
        /* Card */
        .card {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        /* Card Header */
        /* .card-header {
            background: linear-gradient(90deg, #4e73df, #1cc88a);
            color: #fff;
            font-weight: 600;
            font-size: 1.25rem;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            padding: 1rem 1.5rem;
        } */

        /* Buttons */
        .btn-info,
        .btn-success,
        .btn-danger {
            border-radius: 0.6rem;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .btn-info:hover {
            background: #36b9cc;
        }

        .btn-success:hover {
            background: #1cc88a;
        }

        .btn-danger:hover {
            background: #e74a3b;
        }

        /* Table */
        #data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.75rem;
        }

        #data-table th,
        #data-table td {
            border: none !important;
            border-radius: 0.6rem;
            background: #ffffff;
            padding: 0.8rem 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            vertical-align: middle;
        }

        #data-table th {
            background: #4e73df;
            color: #fff;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        #data-table tr:hover td {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        /* Badges */
        .badge-info {
            background: linear-gradient(45deg, #36b9cc, #1cc88a);
            color: #fff;
            font-size: 0.8rem;
            padding: 0.45em 0.8em;
            border-radius: 0.5rem;
            margin-right: 0.3rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .badge-info:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Modal */
        .modal-content {
            border-radius: 1rem;
        }

        .modal-header {
            background: #4e73df;
            color: #fff;
            border-bottom: none;
        }

        .modal-body {
            font-size: 0.95rem;
        }
    </style>
@endpush



@section('admin-content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    @include('backend.layouts.partials.message')
                    <div class="card-header">
                        <h3>Role List</h3>
                    </div>
                    <div class="mt-3 justify-content-between">
                        <a href="{{ route('roles.create') }}" class="float-right btn btn-info"><i class="fa fa-user">
                                Add Role Name</i></a>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="data-table">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 10%;">#</th>
                                    <th scope="col" style="width: 20%;">Name</th>
                                    <th scope="col" style="width: 50%;">Permission</th>
                                    @if (Auth::user()->can('roles.create') || Auth::user()->can('roles.delete'))
                                        <th scope="col" style="width: 20%;">Action</th>
                                    @endif
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <th scope="row">{{ $loop->index + 1 }}</th>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            @foreach ($role->permissions as $perm)
                                                <span class="badge badge-info"> {{ $perm->name }}</span>
                                            @endforeach
                                        </td>

                                        <td>
                                            @if (Auth::user()->can('roles.create') || Auth::user()->can('roles.delete'))
                                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-success"><i
                                                        class="fa fa-edit"></i></a>

                                                <a href="#delteModal{{ $role->id }}" data-toggle="modal"
                                                    class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                            @endif


                                            <!--Delete  Modal -->
                                            <div class="modal fade" id="delteModal{{ $role->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Are you sure
                                                                to delete this?</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('roles.destroy', $role->id) }}"
                                                                method="POST">
                                                                @method('DELETE')
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger">Permanent
                                                                    Delete</button>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

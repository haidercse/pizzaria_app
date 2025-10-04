@extends('backend.layouts.master')

@section('title')
    Role | Role Management
@endsection

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

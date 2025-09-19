@extends('backend.layouts.master')

@section('title')
    Dough Page
@endsection

{{-- All @section directives should be at the top level of your file --}}
@section('page-title', 'Dough List')
@section('breadcrumb-home_route', route('dough.create'))
@section('breadcrumb-home_title', "Dough Add")
@section('breadcrumb-current', 'Dough List')


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
                                        <th>Day</th>
                                        <th>Dough Litter</th>
                                        <th>Dough Weight</th>
                                        <th>Num Of Cajas</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Use @forelse to handle the case where no data is found --}}
                                    @forelse ($doughs as $dough)
                                        <tr>
                                            <td>{{ $dough->day }}</td>
                                            <td>{{ $dough->dough_litter }}</td>
                                            <td>{{ $dough->dough_total_weight }}</td>
                                            <td>{{ $dough->dough_num_of_cajas }}</td>
                                            <td>{{ $dough->date }}</td>
                                            <td>
                                                <a href="{{ route('dough.edit', $dough->id) }}" class="btn btn-primary"><i class="ti-pencil"></i></a>
                                                <form action="{{ route('dough.destroy', $dough->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');"><i class="ti-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No dough data found.</td>
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
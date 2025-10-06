@extends('backend.layouts.master')
@section('title', 'My Availability')

@section('admin-content')
<div class="container mt-4">
    <h4 class="text-center mb-3">📋 My Availability List</h4>

    @if($availabilities->isEmpty())
        <div class="alert alert-info text-center">You haven't set any availability yet.</div>
    @else
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-striped" id="dataTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Preferred Time</th>
                            <th>Custom Time</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availabilities as $availability)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($availability->date)->format('d M Y') }}</td>
                                <td>{{ ucfirst($availability->preferred_time) }}</td>
                                <td>
                                    @if(isset($availability->start_time) && isset($availability->end_time))
                                        {{ $availability->start_time }} - {{ $availability->end_time }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $availability->note ?? '-' }}</td>
                           
                                <td class="form-inline">
                                    <form action="{{ route('availability.edit', $availability->id) }}" method="GET">
                                        <button type="submit" class="btn btn-primary btn-sm mr-1 "><i class="fa fa-edit"></i></button>
                                    </form>
                                    <form action="{{ route('availability.destroy', $availability->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this availability?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                    </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection

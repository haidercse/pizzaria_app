@extends('backend.layouts.master')

@section('title')
   Edit Dough Page
@endsection

@section('admin-content')
<!-- main content area start -->
<div class="main-content">
    
       @section('page-title', 'Edit Dough')
       @section('breadcrumb-home_route', route('dough.index'))
       @section('breadcrumb-home_title', "Dough List")
       @section('breadcrumb-current', 'Edit Dough')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('dough.update', $dough->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="dough_litter">Dough Litter</label>
                            <input type="number" class="form-control" id="dough_litter"
                             name="dough_litter" min="0" value="{{ $dough->dough_litter }}" required>
                        </div>


                        <button type="submit" class="btn btn-primary">Update Dough</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection                    
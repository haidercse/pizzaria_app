@extends('backend.layouts.master')

@section('title')
    Dough Page
@endsection

@section('admin-content')
<!-- main content area start -->
<div class="main-content">
    
       @section('page-title', 'Add Dough')
       @section('breadcrumb-home_route', route('dough.index'))
       @section('breadcrumb-home_title', "Dough List")
       @section('breadcrumb-current', 'Add Dough')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('dough.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="dough_litter">Dough Litter</label>
                            <input type="number" class="form-control" id="dough_litter"
                             name="dough_litter" min="0" required>
                        </div>
                    
                        
                        <button type="submit" class="btn btn-primary">Add Dough</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection                    
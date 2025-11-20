@extends('backend.layouts.master')

@section('title', 'Pizza Dough Table View')

@section('admin-content')
    <div class="container my-4">

        <h4 class="text-success fw-bold mb-3 text-center">Pizza dough Table Viewer</h4>

        {{-- Message --}}
       @include('backend.layouts.partials.message')

        {{-- Upload Form --}}
        @if (!Auth::user()->hasRole('employee'))
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-primary text-white">
                    Upload New PDF (Old File Deletes Automatically)
                </div>
                <div class="card-body">
                    <form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control mb-2" required>
                        <button class="btn btn-success w-100">Upload</button>
                    </form>
                </div>
            </div>
        @endif


        {{-- PDF Viewer --}}
        @if ($file)
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    View PDF
                </div>
                <div class="card-body p-0">

                    <iframe src="{{ asset('storage/' . $file->file_path) }}#toolbar=0"
                        style="width:100%; height:100vh; border:none;">
                    </iframe>

                </div>
            </div>
        @endif

    </div>
@endsection

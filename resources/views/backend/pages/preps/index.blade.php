@extends('backend.layouts.master')
@section('title', 'Preps Menu')

@section('admin-content')
<div class="container my-4">
    <h3 class="mb-4 text-center text-success">Preps Menu</h3>

    {{-- Buttons Row --}}
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-center flex-wrap gap-2">
            @foreach($preps as $prep)
                <button class="btn btn-light border shadow-sm prep-btn px-3 py-2 rounded-pill" data-id="{{ $prep->id }}">
                    {{ $prep->name }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Details Row --}}
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div id="prepDetails">
                <div class="alert alert-secondary text-center">
                    Select a prep to see details
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).on('click', '.prep-btn', function() {
        let id = $(this).data('id');
        $('.prep-btn').removeClass('btn-success text-white').addClass('btn-light border');
        $(this).removeClass('btn-light border').addClass('btn-success text-white');

        $.ajax({
            url: "/preps/" + id,
            type: "GET",
            beforeSend: function () {
                $('#prepDetails').html('<div class="text-center p-5"><div class="spinner-border text-success"></div></div>');
            },
            success: function (data) {
                $('#prepDetails').html(`
                    <div class="card mb-3 bg-light shadow-sm">
                        <div class="card-body">
                            <h5 class="m-0 fw-bold text-dark">${data.name}</h5>
                        </div>
                    </div>

                    <div class="card mb-3 border-success shadow-sm">
                        <div class="card-header bg-success text-white fw-semibold">Ingredients</div>
                        <div class="card-body">
                            <p class="m-0 text-dark">${data.ingredients}</p>
                        </div>
                    </div>

                    <div class="card border-primary shadow-sm">
                        <div class="card-header bg-primary text-white fw-semibold">Process</div>
                        <div class="card-body">
                            <p class="m-0 text-dark">${data.process}</p>
                        </div>
                    </div>
                `);
            }
        });
    });
</script>
@endpush

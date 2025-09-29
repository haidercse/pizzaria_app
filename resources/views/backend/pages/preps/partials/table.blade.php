<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($preps as $key => $prep)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $prep->name }}</td>
                <td>
                    <button class="btn btn-sm btn-warning edit-prep" data-id="{{ $prep->id }}">
                        Edit
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

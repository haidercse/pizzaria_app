@if($shifts->count())
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Start</th>
                <th>End</th>
                <th>Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shifts as $shift)
                <tr>
                    <td>{{ $shift->start_time }}</td>
                    <td>{{ $shift->end_time }}</td>
                    <td>{{ \Carbon\Carbon::parse($shift->end_time)->diffInHours(\Carbon\Carbon::parse($shift->start_time)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No shifts assigned for this date.</p>
@endif

<div class="mt-3">
    <h6>Employee Status:</h6>
    <p><strong>Type:</strong> {{ $isFullTime }}</p>
    <p><strong>Last Month Hours:</strong> {{ $lastMonthHours }} hours</p>
    <p><strong>Note:</strong> {{ $note }}</p>
</div>
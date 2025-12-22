@extends('user.layouts.master')

@section('css')
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('content')
@include('includes.flash')

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if($buttons['punchIn'])
    <button id="punchInButton" class="btn btn-primary btn-sm btn-flat btn-success">Punch In</button>
@endif

@if($buttons['punchOut'])
    <button id="punchOutButton" class="btn btn-primary btn-sm btn-flat btn-danger">Punch Out</button>
@endif

@if($buttons['breakIn'])
    <button id="breakInButton" class="btn btn-primary btn-sm btn-flat btn-success">Break In</button>
@endif

@if($buttons['breakOut'])
    <button id="breakOutButton" class="btn btn-primary btn-sm btn-flat btn-danger">Break Out</button>
@endif

<!-- Your existing content -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Punch-In</th>
                                    <th>Punch-Out</th>
                                    <th>Total Work Hours</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceTableBody">
                                @foreach($attendances as $attendance)
                                <tr data-punch-in="{{ $attendance->punch_in }}"
                                    data-punch-out="{{ $attendance->punch_out }}"
                                    data-break-hours="{{ $attendance->total_break_hours }}">
                                    <td>{{ $attendance->punch_in }}</td>
                                    <td>{{ $attendance->punch_out }}</td>
                                    <td class="totalWorkHours">-</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Break-in</th>
                                    <th>Break-out</th>
                                    <th>Total Break Hours</th>
                                </tr>
                            </thead>
                            <tbody id="breakTableBody">
                                @foreach($breaks as $break)
                                <tr>
                                    <td>{{ $break->break_in }}</td>
                                    <td>{{ $break->break_out }}</td>
                                    <td class="totalBreakHours">---</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        @if(isset($onLeave) && $onLeave)
            alert('You are currently on leave.');
        @endif
        // Function to calculate time difference in hours and minutes
        function calculateTimeDifference(startTime, endTime) {
            const start = new Date(startTime);
            const end = new Date(endTime);
            let diff = (end - start) / (1000 * 60 * 60); // Time difference in hours
            const hours = Math.floor(diff);
            const minutes = Math.floor((diff - hours) * 60);
            return `${hours}h ${minutes}m`;
        }
    
        // Calculate total work hours for each row
        $('#attendanceTableBody tr').each(function() {
            const punchIn = $(this).data('punch-in');
            const punchOut = $(this).data('punch-out');
    
            if (punchIn && punchOut) {
                const totalWorkHours = calculateTimeDifference(punchIn, punchOut);
                $(this).find('.totalWorkHours').text(totalWorkHours);
            }
        });
    
        // Calculate total break hours for each row
        $('#breakTableBody tr').each(function() {
            const breakIn = $(this).find('td:first').text();
            const breakOut = $(this).find('td:nth-child(2)').text();
    
            if (breakIn && breakOut) {
                const totalBreakHours = calculateTimeDifference(breakIn, breakOut);
                $(this).find('.totalBreakHours').text(totalBreakHours);
            }
        });
    
        // Ajax setup for CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        // Punch In/Out and Break In/Out Ajax requests
        $('#punchInButton').click(function() {
            $.ajax({
                url: '{{ route('user.check') }}',
                type: 'POST',
                data: { type: 'punch_in' },
                success: function(response) {
                    alert(response.success);
                    window.location.reload();
                },
                error: function(xhr) {
                    alert(xhr.responseText);
                }
            });
        });
    
        $('#punchOutButton').click(function() {
            $.ajax({
                url: '{{ route('user.check') }}',
                type: 'POST',
                data: { type: 'punch_out' },
                success: function(response) {
                    alert(response.success);
                    window.location.reload();
                },
                error: function(xhr) {
                    alert(xhr.responseText);
                }
            });
        });
    
        $('#breakInButton').click(function() {
            $.ajax({
                url: '{{ route('break.create') }}',
                type: 'POST',
                data: { type: 'break_in' },
                success: function(response) {
                    console.log(response);
                    alert(response.success);
                    window.location.reload();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert(xhr.responseText);
                }
            });
        });
    
        $('#breakOutButton').click(function() {
            $.ajax({
                url: '{{ route('break.create') }}',
                type: 'POST',
                data: { type: 'break_out' },
                success: function(response) {
                    console.log(response);
                    alert(response.success);
                    window.location.reload();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert(xhr.responseText);
                }
            });
        });
    });
    </script>
    
@endsection

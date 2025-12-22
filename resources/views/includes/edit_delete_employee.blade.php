<!-- Edit Modal -->
<!-- Edit Modal -->
<div class="modal fade" id="edit{{ $employee->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('admin.employee.update', $employee->id) }}">
                    @csrf
                    @method('PUT')
                    
                    @php
                        $profileDetails = is_array($employee->profile_details) ? $employee->profile_details : json_decode($employee->profile_details, true);
                    @endphp

                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" class="form-control" id="position" name="position" value="{{ $employee->position }}" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ $profileDetails['address'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" class="form-control" id="state" name="state" value="{{ $profileDetails['state'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="pincode">Pincode</label>
                        <input type="text" class="form-control" id="pincode" name="pincode" value="{{ $profileDetails['pincode'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ $profileDetails['city'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="schedule">Schedule</label>
                        <select class="form-control" id="schedule" name="schedule" required>
                            <option value="" selected>- Select -</option>
                            @foreach ($schedules as $schedule)
                                <option value="{{ $schedule->slug }}" {{ $employee->schedules->contains($schedule->id) ? 'selected' : '' }}>
                                    {{ $schedule->slug }} -> from {{ $schedule->time_in }} to {{ $schedule->time_out }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="delete{{ $employee->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.employee.destroy', $employee->id) }}">
                    @csrf
                    @method('DELETE')
                    <p class="text-center">Are you sure you want to delete the employee <strong>{{ $employee->id }}</strong>?</p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
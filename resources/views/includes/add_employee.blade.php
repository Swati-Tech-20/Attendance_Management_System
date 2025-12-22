<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Add Profile</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>

            <div class="modal-body">

                <div class="card-body text-left">

                    <form method="POST" action="{{ route('admin.employee.store') }}">
                        @csrf
                        <div class="row">
                        <div class="form-group col-md-6">
                            <label for="user_id">User ID</label>
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value="" selected>- Select User -</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="position">Position</label>
                            <input type="text" class="form-control" placeholder="Enter Employee Position" id="position" name="position">
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-6">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="state">State</label>
                            <input type="text" class="form-control" id="state" name="state" placeholder="Enter State" >
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pincode">Pincode</label>
                            <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter Pincode">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="Enter City">
                        </div>
                        </div>
                        {{-- <div class="form-group">
                            <label for="image">Profile Image</label>
                            <input type="file" class="form-control-file" id="image" name="image">
                        </div> --}}
                        
                        {{-- <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Email</label>


                            <input type="email" class="form-control" id="email" name="email">

                        </div> --}}
                        <div class="form-group col-md-6">
                            <label for="schedule" class="col-sm-3 control-label">Schedule</label>


                            <select class="form-control" id="schedule" name="schedule" required>
                                <option value="" selected>- Select -</option>
                                @foreach($schedules as $schedule)
                                <option value="{{$schedule->slug}}">{{$schedule->slug}} -> from {{$schedule->time_in}}
                                    to {{$schedule->time_out}} </option>
                                @endforeach

                            </select>

                        </div>

                        <div class="form-group d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mx-2">
                                Submit
                            </button>
                            <button type="reset" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </form>

                </div>
            </div>


        </div>

    </div>
</div>
</div>
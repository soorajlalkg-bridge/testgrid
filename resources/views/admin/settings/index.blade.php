@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Settings</div>

                <div class="panel-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success">
                          {{Session::get('message')}}
                        </div>
                    @endif
                    <form method="post" action="{{url('/admin/saveProfile')}}">
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />
                      <!-- <div class="form-group">
                        <label for="old_pwd">Old Password:</label>
                        <input type="password" class="form-control" id="old_pwd" name="old_password" required="">
                      </div> -->
                      <div class="form-group">
                        <label for="new_pwd">New Password:</label>
                        <input type="password" class="form-control" id="new_pwd" name="new_password" required="">
                      </div>
                      <div class="form-group">
                        <label for="cfm_pwd">Confirm Password:</label>
                        <input type="password" class="form-control" id="cfm_pwd" name="confirm_password" required="">
                      </div>
                      <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

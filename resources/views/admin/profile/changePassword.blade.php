@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
          <div class="animated fadeIn">
            @if(Session::has('success'))
                <div class="alert alert-success">
                  {{Session::get('success')}}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
              <div class="col-sm-12">
                <div class="card">
                    <form method="post" action="{{url('/admin/savePassword')}}">
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />
                  <div class="card-header">
                    <strong>Change Password</strong>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="old_pwd">Old Password</label>
                          <input type="password" class="form-control" id="old_pwd" name="old_password" required="" placeholder="Old Password">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="new_pwd">New Password</label>
                          <input type="password" class="form-control" id="new_pwd" name="new_password" required="" placeholder="New Password">
                        </div>
                      </div>
                    </div>
                    <!-- /.row-->
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="cfm_pwd">Confirm Password</label>
                          <input type="password" class="form-control" id="cfm_pwd" name="new_password_confirmation" required="" placeholder="Confirm Password">
                        </div>
                      </div>
                    </div>
                    <!-- /.row-->
                  </div>
                  <div class="card-footer">
                        <button class="btn btn-sm btn-primary button" type="submit">
                          <i class="fa fa-dot-circle-o"></i> Submit</button>
                    </div>
                    </form>
                </div>
              </div>
          </div>
      </div>
  </div>

<!-- <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Change Password</div>

                <div class="panel-body">
                    @if(Session::has('success'))
                        <div class="alert alert-success">
                          {{Session::get('success')}}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="post" action="{{url('/admin/savePassword')}}">
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />
                      <div class="form-group">
                        <label for="new_pwd">New Password:</label>
                        <input type="password" class="form-control" id="new_pwd" name="new_password" required="">
                      </div>
                      <div class="form-group">
                        <label for="cfm_pwd">Confirm Password:</label>
                        <input type="password" class="form-control" id="cfm_pwd" name="new_password_confirmation" required="">
                      </div>
                      <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection

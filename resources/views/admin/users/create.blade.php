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
              <div class="col-sm-8">
                <div class="card">
                    <form method="post" action="{{url('/admin/users/store')}}">
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />
                  <div class="card-header">
                    <strong>Add User</strong>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="firstname">First Name</label>
                          <input type="text" class="form-control" id="firstname" name="firstname" value="{{ old('firstname') }}" required="">
                        </div>
                      </div>
                    </div>
                    <!-- /.row-->
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="lastname">Last Name</label>
                          <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname') }}" required="">
                        </div>
                      </div>
                    </div>
                    <!-- /.row-->
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="username">Username</label>
                          <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="email">Email</label>
                          <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="pwd">Password</label>
                          <input type="password" class="form-control" id="pwd" name="password" required="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="cfm_pwd">Confirm Password</label>
                          <input type="password" class="form-control" id="cfm_pwd" name="password_confirmation" required="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="loginlimit">Login Limit</label>
                          <input type="number" class="form-control" id="loginlimit" name="loginlimit" min="1" value="{{ old('loginlimit') }}" required="">
                        </div>
                      </div>
                    </div>
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

<!-- 
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Add User</div>

                <div class="panel-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success">
                          {{Session::get('message')}}
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

                    <form method="post" action="{{url('/admin/users/store')}}">
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />
                      <div class="form-group">
                        <label for="firstname">First Name:</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" value="{{ old('firstname') }}" required="">
                      </div>
                      <div class="form-group">
                        <label for="lastname">Last Name:</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname') }}" required="">
                      </div>
                      <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required="">
                      </div>
                      <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required="">
                      </div>
                      <div class="form-group">
                        <label for="pwd">Password:</label>
                        <input type="password" class="form-control" id="pwd" name="password" required="">
                      </div>
                      <div class="form-group">
                        <label for="cfm_pwd">Confirm Password:</label>
                        <input type="password" class="form-control" id="cfm_pwd" name="password_confirmation" required="">
                      </div>
                      <div class="form-group">
                        <label for="loginlimit">Login Limit:</label>
                        <input type="number" class="form-control" id="loginlimit" name="loginlimit" min="1" value="{{ old('loginlimit') }}" required="">
                      </div>
                      <button type="submit" class="btn btn-primary">Add New User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection

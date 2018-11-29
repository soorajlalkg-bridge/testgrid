@extends('admin.layout.login')

@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card-group">
        <div class="card p-4">
          <div class="card-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/login') }}">
                {{ csrf_field() }}
            <h1>Login</h1>
            <p class="text-muted">Sign In to your account</p>
            <div class="input-group mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="icon-user"></i>
                </span>
              </div>
              <!-- <input class="form-control" type="text" placeholder="E-Mail Address"> -->
              <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" autofocus placeholder="E-Mail Address">
            </div>
            @if ($errors->has('email'))
                <span class="help-block error-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
            <div class="input-group mb-4{{ $errors->has('password') ? ' has-error' : '' }}">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="icon-lock"></i>
                </span>
              </div>
              <!-- <input class="form-control" type="password" placeholder="Password"> -->
              <input id="password" type="password" class="form-control" name="password" placeholder="Password">
            </div>
            @if ($errors->has('password'))
                <span class="help-block error-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
            <div class="row">
              <div class="col-6">
                <button class="btn btn-primary px-4 button" type="submit">Login</button>
              </div>
              <div class="col-6 text-right">
                <a class="btn btn-link px-0" href="{{ url('/admin/password/reset') }}">Forgot password?</a>
              </div>
            </div>
            </form>
          </div>
        </div>
        <!-- <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
          <div class="card-body text-center">
            <div>
              <h2>Sign up</h2>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
              <button class="btn btn-primary active mt-3" type="button">Register Now!</button>
            </div>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</div>

<?php /*
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="{{ url('/admin/password/reset') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>*/?>
@endsection

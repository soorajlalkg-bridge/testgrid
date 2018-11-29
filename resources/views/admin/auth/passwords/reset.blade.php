@extends('admin.layout.login')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    </div>
    </div>
    <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card-group">
        <div class="card p-4">
          <div class="card-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/password/reset') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">
            <h1>Reset Password</h1>
            <div class="input-group mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="icon-user"></i>
                </span>
              </div>
              <!-- <input class="form-control" type="text" placeholder="E-Mail Address"> -->
              <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email-Address">
            </div>
            @if ($errors->has('email'))
                <span class="help-block error-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
            <div class="input-group mb-3{{ $errors->has('password') ? ' has-error' : '' }}">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="icon-lock"></i>
                </span>
              </div>
              <!-- <input class="form-control" type="text" placeholder="E-Mail Address"> -->
              <input id="password" type="password" class="form-control" name="password" placeholder="Password">
                
            </div>
            @if ($errors->has('password'))
                <span class="help-block error-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
            <div class="input-group mb-3{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="icon-lock"></i>
                </span>
              </div>
              <!-- <input class="form-control" type="text" placeholder="E-Mail Address"> -->
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
            </div>
            @if ($errors->has('password_confirmation'))
                <span class="help-block error-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
            <div class="row">
              <div class="col-6">
                <button class="btn btn-primary px-4 button" type="submit">Reset Password</button>
              </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/password/reset') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" autofocus>

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

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection

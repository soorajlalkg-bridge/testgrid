@extends('igadmin.layout.login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card-group">
          <div class="card p-4">
            <div class="card-body">
              <form class="form-horizontal" role="form" method="POST" action="{{ url('/igadmin/login') }}">
                  {{ csrf_field() }}
              <h1>Igolgi Login</h1>
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
                    {{-- <a class="btn btn-link" href="{{ url('/igadmin/password/reset') }}">
                        Forgot Your Password?
                    </a> --}}
                </div>
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@extends('igadmin.layout.app')

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
                    <form method="post" action="{{url('/igadmin/saveSettings')}}">
                        <input type="hidden" value="{{csrf_token()}}" name="_token" />
                  <div class="card-header">
                    <strong>{!! trans('extendedlaravelinstaller.licenses.title') !!}</strong>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="user_licenses">{{ trans('extendedlaravelinstaller.licenses.form.user_licenses_label') }}</label>
                          <input type="number" class="form-control"  name="user_licenses" id="user_licenses" value="{{$userLicenseLimit}}" placeholder="{{ trans('extendedlaravelinstaller.licenses.form.user_licenses_placeholder') }}" min="1" max="1000000" required="" />
                        </div>
                        <div class="form-group">
                          <label for="encryption_key">{{ trans('extendedlaravelinstaller.licenses.form.encryption_key_label') }}</label>
                          <input type="password" class="form-control"  name="encryption_key" id="encryption_key" value="" placeholder="{{ trans('extendedlaravelinstaller.licenses.form.encryption_key_placeholder') }}" required="" />
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

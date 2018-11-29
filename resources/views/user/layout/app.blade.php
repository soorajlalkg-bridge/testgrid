<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Multi-purpose Live Video System Monitor') }}</title>

  <link rel="icon" href="{{ url('/') }}/images/fav-icon-1-48x50.png" sizes="32x32" />
  <link rel="icon" href="{{ url('/') }}/images/fav-icon-1.png" sizes="192x192" />
  <link rel="apple-touch-icon-precomposed" href="{{ url('/') }}/images/fav-icon-1.png" />
  <meta name="msapplication-TileImage" content="{{ url('/') }}/images/fav-icon-1.png" />

  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">
  <!-- Bootstrap core CSS -->
  <link href="{{ url('/') }}/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
  <link href="{{ url('/') }}/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  @if(isset($gridView))
  <link href="http://vjs.zencdn.net/5.19/video-js.css" rel="stylesheet">
  <link href="{{ url('/') }}/full-screen-helper/full-screen-helper.css"  rel="stylesheet" type="text/css">
  @endif
  <link href="{{ url('/') }}/css/igogi.css" rel="stylesheet">
  <link href="{{ url('/') }}/css/custom.css?v=<?php echo time();?>" rel="stylesheet">
  <!-- Scripts -->
  <script>
      window.Laravel = <?php echo json_encode([
          'csrfToken' => csrf_token(),
      ]); ?>
  </script>
</head>
<body>
  <div class="container-fluid">
    <div class="row padding-t-b header align-items-center">
      <div class="col-md-4 col-sm-12">
        <a href="{{ url('/') }}"><img src="{{ URL::asset('/images/Igogio-logo.png') }}" alt="Igogi Logo"></a>
        <p class="margin-0 caption">{{ config('app.name', 'Multi-purpose Live Video System Monitor') }}</p>
      </div>
      <div class="col-md-8 col-sm-12 text-right">
      @if (\Auth::guard('user')->user())     
        <!-- <label class="checkbox-inline">
          Autoplay <input type="checkbox" checked data-toggle="toggle"  data-onstyle="success" >
        </label> -->
        <input type="checkbox" id="toggle-autoplay" @if(\Auth::guard('user')->user()->autoplay) checked @endif data-toggle="toggle" data-on="Autoplay On" data-off="Autoplay Off" data-onstyle="success" data-offstyle="primary">
        <button type="button" class="button " id="account-link">
          <i class="material-icons">perm_identity</i>
          <span>Account</span>
        </button>
        <a class="button" href="{{ url('/user/logoutAccount') }}">Logout</a>
      @else
        <button type="button" class="button " data-toggle="modal" data-target="#loginModal">
          <i class="material-icons">perm_identity</i>
          <span>Account</span>
        </button>
      @endif
        <button class="button">
          <i class="material-icons">help_outline</i>
          <span>Help</span>
        </button>
      </div>
    </div>

    @if (\Auth::guard('user')->user())
    <ul class="link-wrapp row text-center">
      <li class="col">
        <a href="{{ url('user/configure') }}" class="col {{ request()->is('user/configure') ? 'active' : '' }}">Configure</a>
      </li>
      <li class="col">
        <a href="{{ url('user/grid') }}" class="col {{ request()->is('user/grid') ? 'active' : '' }}">Grid</a>
      </li>
      <li class="col">
        <a href="{{ url('user/monitor') }}" class="col {{ request()->is('user/monitor') ? 'active' : '' }}">Monitor</a>
      </li>
    </ul>
    @endif

    @yield('content')

    <footer class="row">
      <div class="col-sm-12">
        <div class="row padding-t-b">
          <div class="col-sm-7">
            @copyright {{ date('Y') }} | {{ config('app.name', 'Multi-purpose Live Video System Monitor') }}
          </div>
          <div class="col-sm-5 text-right">
            <a href="#">Privacy Policy</a>
          </div>
        </div>

      </div>

    </footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

            <h3 class="modal-title text-center">Login</h3>
            <form method="post" action="{{url('user/loginAccount')}}" id="loginAccountForm" autocomplete="off">
              <div class="row">
                  <div class="form-errors col-sm-12  alert alert-danger d-none"></div>
                  <div class="col-sm-12">
                      <!-- <input type="text" placeholder="Username" > -->
                      <input type="text" id="login-username" name="username" placeholder="Username or Email" required="">
                      <span class="invalid-error-msg"></span>
                  </div>
                  <div class="col-sm-12">
                      <input type="password" id="login-password" name="password" placeholder="Password" >
                      <span class="invalid-error-msg"></span>
                  </div>
                  <div class="col-sm-12 text-right">
                      <!-- <a class="btn btn-link" href="{{ url('/user/password/reset') }}">Forgot Your Password?</a> -->
                      <button type="button" class="btn primary-btn ripple" id="ajax-login-submit" >Login</button>
                  </div>
              </div>
            </form>

        </div>
      </div>
    </div>

    @if (\Auth::guard('user')->user())
    <div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="accountModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

            <h3 class="modal-title text-center">Account</h3>
            <form method="post" action="{{url('user/saveProfile')}}" id="editAccountForm" autocomplete="off">
              <div class="row">
                  <div class="form-errors col-sm-12 alert alert-danger d-none"></div>
                  <div class="col-sm-12 alert alert-success d-none"></div>
                  <div class="col-sm-12">
                    <strong>Edit Profile</strong>
                  </div>
                  <div class="col-sm-12">
                      <input type="text" id="account-username" name="username" placeholder="Username" required="">
                      <span class="invalid-error-msg"></span>
                  </div>
                  <div class="col-sm-12">
                      <input type="text" id="account-firstname" name="firstname" placeholder="First Name" required="">
                      <span class="invalid-error-msg"></span>
                  </div>
                  <div class="col-sm-12">
                      <input type="text" id="account-lastname" name="lastname" placeholder="Last Name" required="">
                      <span class="invalid-error-msg"></span>
                  </div>
                  <div class="col-sm-12">
                      <input type="email" id="account-email" name="email" placeholder="Email" required="">
                      <span class="invalid-error-msg"></span>
                  </div>
                  <div class="col-sm-12">
                    <strong>Change Password</strong>
                  </div>
                  <div class="col-sm-12">
                      <input type="password" id="account-password" name="password" placeholder="New Password" >
                      <span class="invalid-error-msg"></span>
                  </div>
                  <div class="col-sm-12">
                      <input type="password" id="account-confirmpassword" name="password_confirmation" placeholder="Confirm Password" >
                      <span class="invalid-error-msg"></span>
                  </div>
                  <div class="col-sm-12 text-right">
                      <button type="button" class="btn primary-btn ripple" id="ajax-account-submit" >Update Profile</button>
                  </div>
              </div>
            </form>

        </div>
      </div>
    </div>

    @endif

  </div>  

    <!-- Scripts -->
    <script type="text/javascript">
        var SITE_URL = '<?php echo url('/');?>/';
        var maxGroups = '<?php echo config('constants.options.max_groups');?>';
        var maxRows = '<?php echo config('constants.options.max_rows');?>';
        var maxCols = '<?php echo config('constants.options.max_cols');?>';
        var defaultConfig = '<?php echo config('constants.config.default');?>';
    </script>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
      crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js
"></script>
    <script src="{{ url('/') }}/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
    @if (\Auth::guard('user')->user())
    <script src="{{ url('/') }}/jquery-idleTimeout/store.legacy.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/jquery-idleTimeout/jquery-idleTimeout.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/js/custom-idleTimeout.js?v=<?php echo time();?>"></script>
    @if(isset($gridView))
    <script src="http://vjs.zencdn.net/ie8/1.1/videojs-ie8.min.js"></script>
    <script src="http://vjs.zencdn.net/5.19/video.js"></script>
    <script src="https://unpkg.com/videojs-contrib-hls/dist/videojs-contrib-hls.js"></script>
    <script src="{{ url('/') }}/js/videojs.customvolumebutton.js"></script>
    <script src="{{ url('/') }}/js/videojs.customfullscreenbutton.js"></script>
    <!-- <script src="{{ url('/') }}/js/web-audio-peak-meter.js"></script> -->
    <script src="{{ url('/') }}/js/web-audio-vu-meter.js?v=<?php echo time();?>"></script>
    <script src="{{ url('/') }}/full-screen-helper/full-screen-helper.min.js" type="text/javascript" ></script>
    @endif
    @endif

    <script src="{{ url('/') }}/js/custom-front.js?v=<?php echo time();?>"></script>

</body>
</html>

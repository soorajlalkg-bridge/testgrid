<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Multi Auth Guard') }}</title>

    <!-- Styles -->
    <link href="{{ url('/') }}/css/app.css" rel="stylesheet">
    <link href="{{ url('/') }}/css/custom.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel Multi Auth Guard') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @if (Auth::user())
                        <li><a href="{{ url('/user/configure') }}">Configure</a></li>
                        <li><a href="{{ url('/user/grid') }}">Grid</a></li>
                        <li><a href="{{ url('/user/monitor') }}">Monitor</a></li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <!-- <li><a href="{{ url('/user/login') }}">Login</a></li>
                        <li><a href="{{ url('/user/register') }}">Register</a></li> -->
                        <li><a href="#" class="login-link" id="login-link" data-toggle="modal" data-target="#loginModal">Account</a></li>
                        <!-- <li><a href="{{ url('/user/logoutAccount') }}">logoutAccount</a></li> -->
                    @else
                        <li><a href="#" class="account-link" id="account-link">Account</a></li>
                        <li><a href="#">Help</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/user/logoutAccount') }}">Logout</a></li>
                                <?php /*<li>
                                    <a href="{{ url('/user/logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/user/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>*/?>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- The Modal -->
    <div class="modal" id="loginModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Login</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
          <div class="form-errors alert alert-danger hidden"></div>
            <form method="post" action="{{url('user/loginAccount')}}" id="loginAccountForm">
              <div class="form-group">
                <label for="login-email">Email address:</label>
                <input type="email" class="form-control" id="login-email" name="email" required="">
              </div>
              <div class="form-group">
                <label for="login-password">Password:</label>
                <input type="password" class="form-control" id="login-password" name="password" required="">
              </div>
              <!-- <div class="checkbox">
                <label><input type="checkbox"> Remember me</label>
              </div> -->
              <input type="submit" class="btn btn-primary" id="ajax-login-submit" value="Login">
              <a class="btn btn-link" href="{{ url('/user/password/reset') }}">Forgot Your Password?</a>
            </form>
          </div>
          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    @if (Auth::user())
    <div class="modal" id="account-modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Profile</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
          <div class="form-errors alert alert-danger hidden"></div>
          <div class="alert alert-success hidden"></div>
            <form method="post" action="{{url('user/saveProfile')}}" id="editAccountForm">
            <div class="form-group">
                Edit Profile
              </div>
                <div class="form-group">
                <label for="account-firstname">First Name:</label>
                <input type="text" class="form-control" id="account-firstname" name="firstname" required="">
              </div>
              <div class="form-group">
                <label for="account-lastname">Last Name:</label>
                <input type="text" class="form-control" id="account-lastname" name="lastname" required="">
              </div>
              <div class="form-group">
                <label for="account-email">Email address:</label>
                <input type="email" class="form-control" id="account-email" name="email" required="">
              </div>
              <div class="form-group">
                Change Password
              </div>
              <div class="form-group">
                <label for="account-password">New Password:</label>
                <input type="password" class="form-control" id="account-password" name="password" required="">
              </div>
              <div class="form-group">
                <label for="account-confirmpassword">Confirm Password:</label>
                <input type="password" class="form-control" id="account-confirmpassword" name="password_confirmation" required="">
              </div>
              <input type="submit" class="btn btn-primary" id="ajax-account-submit" value="Update Profile">
            </form>
          </div>
          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    @endif

    <!-- Scripts -->
    <script type="text/javascript">
        var SITE_URL = '<?php echo url('/');?>/';
    </script>
    <script src="{{ url('/') }}/js/app.js"></script>
    <script src="{{ url('/') }}/js/custom-front.js"></script>
</body>
</html>

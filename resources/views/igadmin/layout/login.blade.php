<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Multi-purpose Live Video System Monitor') }}</title>

      <link rel="icon" href="{{ url('/') }}/images/fav-icon-1-48x50.png" sizes="32x32" />
      <link rel="icon" href="{{ url('/') }}/images/fav-icon-1.png" sizes="192x192" />
      <link rel="apple-touch-icon-precomposed" href="{{ url('/') }}/images/fav-icon-1.png" />
      <meta name="msapplication-TileImage" content="{{ url('/') }}/images/fav-icon-1.png" />

    <!-- Icons-->
    <link href="{{ url('/') }}/backend/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/backend/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/backend/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/backend/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="{{ url('/') }}/backend/css/style.css" rel="stylesheet">
    <link href="{{ url('/') }}/backend/vendors/pace-progress/css/pace.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/css/custom.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body class="app flex-row header-fixed align-items-center app-login">
    <header class="app-header navbar">
      <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="{{ url('/igadmin/login') }}">
        <img class="navbar-brand-full" src="{{ URL::asset('/images/Igogio-logo.png') }}" width="89" height="25" alt="{{ config('app.name', 'Multi-purpose Live Video System Monitor') }} Logo">
        <img class="navbar-brand-minimized" src="{{ URL::asset('/images/Igogio-logo.png') }}" width="30" height="30" alt="{{ config('app.name', 'Multi-purpose Live Video System Monitor') }} Logo">
      </a>
    </header>

    @yield('content')
    
    <!-- CoreUI and necessary plugins-->
    <script src="{{ url('/') }}/backend/jquery/dist/jquery.min.js"></script>
    <script src="{{ url('/') }}/backend/popper.js/dist/umd/popper.min.js"></script>
    <script src="{{ url('/') }}/backend/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ url('/') }}/backend/pace-progress/pace.min.js"></script>
    <script src="{{ url('/') }}/backend/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
    <script src="{{ url('/') }}/backend/@coreui/coreui/dist/js/coreui.min.js"></script>

</body>
</html>

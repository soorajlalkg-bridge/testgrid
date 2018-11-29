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

    <?php /*<!-- Styles -->
    <link href="{{ url('/') }}/css/app.css" rel="stylesheet">
    <link href="{{ url('/') }}/css/custom.css" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/> -->
    <link href="{{ url('/') }}/datatable/css/jquery.dataTables.min.css" rel="stylesheet">
    @if(isset($datatable_buttons))
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
    @endif*/?>

    <!-- Icons-->
    <link href="{{ url('/') }}/backend/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/backend/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/backend/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/backend/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="{{ url('/') }}/backend/css/style.css" rel="stylesheet">
    <link href="{{ url('/') }}/backend/vendors/pace-progress/css/pace.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/> -->
    <link href="{{ url('/') }}/datatable/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/css/custom.css" rel="stylesheet">
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body class="app header-fixed ig-header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">

        <header class="app-header navbar">
          <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
            <span class="navbar-toggler-icon"></span>
          </button>
          <a class="navbar-brand" href="#">
            <img class="navbar-brand-full" src="{{ URL::asset('/images/Igogio-logo.png') }}" width="89" height="25" alt="{{ config('app.name', 'Multi-purpose Live Video System Monitor') }} Logo">
            <img class="navbar-brand-minimized" src="{{ URL::asset('/images/Igogio-logo.png') }}" width="30" height="30" alt="{{ config('app.name', 'Multi-purpose Live Video System Monitor') }} Logo">
          </a>
        </header>
        <div class="app-body">
      <div class="sidebar">
        <nav class="sidebar-nav">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/igadmin/dashboard') }}">
                <i class="nav-icon icon-user"></i> Dashboard</a>
            </li>
            <li>
                <a class="nav-link" href="{{ url('/igadmin/logout') }}"
                    onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                              <i class="nav-icon icon-power"></i> Logout
                </a>

                <form id="logout-form" action="{{ url('/igadmin/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
          </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
      </div>
      <main class="main">
        <!-- Breadcrumb-->
        <ol class="breadcrumb">
        </ol>

    @yield('content')

    </main>
    </div>

    <footer class="app-footer">
      <div>
        <span>@copyright {{ date('Y') }} | Igolgi grid view system</span>
      </div>
      <div class="ml-auto">
      </div>
    </footer>
    
    <!-- CoreUI and necessary plugins-->
    <script src="{{ url('/') }}/backend/jquery/dist/jquery.min.js"></script>
    <script src="{{ url('/') }}/backend/popper.js/dist/umd/popper.min.js"></script>
    <script src="{{ url('/') }}/backend/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ url('/') }}/backend/pace-progress/pace.min.js"></script>
    <script src="{{ url('/') }}/backend/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
    <script src="{{ url('/') }}/backend/@coreui/coreui/dist/js/coreui.min.js"></script>
</body>
</html>

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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/> 
    <link href="{{ url('/') }}/datatable/css/jquery.dataTables.min.css" rel="stylesheet">
    @if(isset($datatable_buttons))
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
    @endif
    <link href="{{ url('/') }}/css/custom.css" rel="stylesheet">
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show" >
    
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
              <a class="nav-link" href="{{ url('/admin/users') }}">
                <i class="nav-icon icon-user"></i> Users</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/admin/online') }}">
                <i class="nav-icon icon-login"></i> Active User Sessions</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/admin/logs') }}">
                <i class="nav-icon icon-flag"></i> Logs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/admin/nodes') }}">
                <i class="nav-icon icon-globe"></i> Nodes</a>
            </li>
            <li class="nav-item nav-dropdown">
              <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon icon-settings"></i> Settings</a>
              <ul class="nav-dropdown-items">
                <li class="nav-item">
                  <a class="nav-link" href="{{ url('/admin/profile') }}">
                    <i class="nav-icon icon-cursor"></i> Profile</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ url('/admin/changePassword') }}">
                    <i class="nav-icon icon-lock"></i> Change Password</a>
                </li>
              </ul>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/admin/logoutAccount') }}">
                <i class="nav-icon icon-power"></i> Logout</a>
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
    <script type="text/javascript" src="{{ url('/') }}/datatable/js/jquery.dataTables.min.js"></script>
    @if(isset($datatable_buttons))
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    @endif
    <script src="{{ url('/') }}/js/custom.js"></script>
</body>
</html>

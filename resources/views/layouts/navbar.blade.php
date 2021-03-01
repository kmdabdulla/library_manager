 <!--fixed layout to be used for the views across the application -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Library Manager</title>
  <link rel="icon" type="image/svg" href="{{asset('dist/img/server.svg')}}" />
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<!-- JQVMap -->
<link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<!-- summernote -->
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        @if (isset(Auth::user()->name))
        <div class="info">
          <a href="#" class="d-block">{{Auth::user()->name}}</a>
        </div>
        @endif
      </div>
 <!--side panel items -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="addBook" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Add Book</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="getAvailableBooks" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Available Books</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="getUserCheckedOutBooks" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Checked Out Books</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="getUserActions" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Activity Log</p>
                </a>
            </li>
              <br>
          <li class="nav-item">
            <form method="post" action="logout">
                @csrf
              <button type="submit" class="btn btn-danger btn-block">Log Out</button>
            </form>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
 <!--display the main content depending upon the view -->
  <div class="content-wrapper">
    @yield('content')
  </div>

  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>


<!-- jQuery -->

<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<!--<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>-->
<!-- Sparkline -->

<!-- JQVMap -->
<script src="{{asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<script src="{{asset('plugins/sparklines/sparkline.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('dist/js/pages/dashboard.js')}}"></script>
<script src="{{ asset('dist/js/bookmanager.js')}}"></script>
<script src="{{ asset ('dist/js/addbook.js')}}"></script>
</body>
</html>

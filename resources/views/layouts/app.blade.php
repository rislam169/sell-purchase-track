<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon/'.env('FAV_ICON')) }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title> {{ env('APP_NAME') }} </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/paper-dashboard.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.css') }}">
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{ asset('assets/demo/demo.css') }}" rel="stylesheet" />
{{--    <link rel="stylesheet" href="{{ asset('assets/css/dataTables-1.10.20/jquery.dataTables.min.css') }}">--}}
    @yield('css')
</head>

<body class="">
<div class="wrapper ">
    <div class="szn-preloader"></div>
    @include('layouts.sidebar')

    <div class="main-panel">
        @include('layouts.navbar')
        <div class="content">
            @yield('content')
        </div>
        @include('layouts.footer')
    </div>
</div>
<!--   Core JS Files   -->
<script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<!--  Notifications Plugin    -->
<script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="{{ asset('assets/js/paper-dashboard.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2@9.8.2/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
<script src="{{ asset('assets/demo/demo.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.szn-preloader').fadeOut('slow');

        $('[data-toggle="tooltip"]').tooltip();
    });

</script>

@yield('script')

</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AIC Training</title>
    <link rel="icon" href="{{url('./img/logo.png')}}">
    <link rel="stylesheet" href="\css\app.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
            </ul>
            <div id="datetime" style="font-weight:bold;"><strong>current day</strong></div>
        </nav>
        
        <!-- /.navbar -->

        <!-- Side bar -->
        @include('layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    {{-- vue-router --}}
                    <router-view></router-view>
                    {{-- progress-bar --}}
                    <vue-progress-bar></vue-progress-bar>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <!-- footet -->
        @include('layouts.footer')
    </div>
    <!-- ./wrapper -->
    <script src="\js\app.js"></script>
    <script>
    function updateDateTime() {
        const now = new Date();
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const day = days[now.getDay()];
        const date = now.toLocaleDateString('en-US');
        const time = now.toLocaleTimeString('en-US');
        const dateTimeFormat = `${day} [ ${date} ${time} ]`;
        document.getElementById('datetime').innerHTML = dateTimeFormat;
    }

    // Call the function to update date and time on page load
    updateDateTime();

    // Optional: Update the date and time every minute if you want it to stay current
    setInterval(updateDateTime, 1000);
    </script>

</body>

</html>

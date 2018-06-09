<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>{{ env('APP_NAME', 'CPMUMS') }} | @yield('title')</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="/bower_components/Ionicons/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="/css/dist/AdminLTE.min.css">
<!-- AdminLTE Skins. -->
<link rel="stylesheet" href="/css/dist/skins/skin-red.min.css">

<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

<!-- Select2 -->
<link rel="stylesheet" href="/bower_components/select2/dist/css/select2.min.css">
<!-- bootstrap-datepicker -->
<link rel="stylesheet" href="/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
{{-- sweet alert --}}
<link rel="stylesheet" href="/css/plugins/sweet_alert/sweetalert2.min.css">
{{-- custom css --}}
<link rel="stylesheet" href="/css/main.css">
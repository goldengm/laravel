<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  
  <title>Admin Panel | {{ $pageTitle }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="Vectorcoder" content="http://ionicecommerce.com">
  <!-- Bootstrap 3.3.6 -->
  <link href="{!! asset('resources/views/admin/bootstrap/css/bootstrap.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
  <link href="{!! asset('resources/views/admin/bootstrap/css/styles.css') !!}" media="all" rel="stylesheet" type="text/css" />
  <!-- Font Awesome -->
  <link href="{!! asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
  
  <!-- Select2 -->
  <link rel="stylesheet" href="{!! asset('resources/views/admin/plugins/select2/select2.min.css') !!}">
  
    <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="{!! asset('resources/views/admin/plugins/colorpicker/bootstrap-colorpicker.min.css') !!}">
  <!-- Ionicons -->
  <link href="{!! asset('https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
  <!-- daterange picker -->
  <link rel="stylesheet" href="{!! asset('resources/views/admin/plugins/daterangepicker/daterangepicker-bs3.css') !!}">
   <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{!! asset('resources/views/admin/plugins/datepicker/datepicker3.css') !!}">
  <!-- jvectormap -->
  <link href="{!! asset('resources/views/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css') !!}" media="all" rel="stylesheet" type="text/css" />
  <!-- Theme style -->
  <link href="{!! asset('resources/views/admin/dist/css/AdminLTE.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link href="{!! asset('resources/views/admin/dist/css/skins/_all-skins.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
    <!-- iCheck for checkboxes and radio inputs -->
    <link href="{!! asset('resources/views/admin/plugins/iCheck/all.css') !!}" media="all" rel="stylesheet" type="text/css" />
    
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="{!! asset('resources/views/admin/plugins/timepicker/bootstrap-timepicker.min.css') !!}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

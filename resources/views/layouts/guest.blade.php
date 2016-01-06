<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	

    <title>Laravel</title>
	<!-- Bootstrap 3.3.5 -->
	{{ HTML::style('AdminLTE-2.3.0/bootstrap/css/bootstrap.min.css') }}
	<!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	    <!-- Theme style -->
	{{ HTML::style('AdminLTE-2.3.0/dist/css/AdminLTE.min.css') }}
	<!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
	{{ HTML::style('AdminLTE-2.3.0/dist/css/skins/_all-skins.min.css') }}
	<!-- iCheck -->
	{{ HTML::style('AdminLTE-2.3.0/plugins/iCheck/flat/blue.css') }}
    <!-- Morris chart -->
	{{ HTML::style('AdminLTE-2.3.0/plugins/morris/morris.css') }}
    <!-- jvectormap -->
	{{ HTML::style('AdminLTE-2.3.0/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}
    <!-- Date Picker -->
	{{ HTML::style('AdminLTE-2.3.0/plugins/datepicker/datepicker3.css') }}
    <!-- Daterange picker -->
	{{ HTML::style('AdminLTE-2.3.0/plugins/daterangepicker/daterangepicker-bs3.css') }}
    <!-- bootstrap wysihtml5 - text editor -->
	{{ HTML::style('AdminLTE-2.3.0/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>
<body class="hold-transition skin-blue sidebar-mini">
    
		
		@yield('content')
		
    
	
	<!-- jQuery 2.1.4 -->
	{{ HTML::script('AdminLTE-2.3.0/plugins/jQuery/jQuery-2.1.4.min.js') }}

    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.5 -->
	{{ HTML::script('AdminLTE-2.3.0/bootstrap/js/bootstrap.min.js') }}
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	{{ HTML::script('AdminLTE-2.3.0/plugins/morris/morris.min.js') }}
    <!-- Sparkline -->
	{{ HTML::script('AdminLTE-2.3.0/plugins/sparkline/jquery.sparkline.min.js') }}
    <!-- jvectormap -->
	{{ HTML::script('AdminLTE-2.3.0/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}
	{{ HTML::script('AdminLTE-2.3.0/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}
    <!-- jQuery Knob Chart -->
	{{ HTML::script('AdminLTE-2.3.0/plugins/knob/jquery.knob.js') }}
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
	{{ HTML::script('AdminLTE-2.3.0/plugins/daterangepicker/daterangepicker.js') }}
    <!-- datepicker -->
	{{ HTML::script('AdminLTE-2.3.0/plugins/datepicker/bootstrap-datepicker.js') }}
    <!-- Bootstrap WYSIHTML5 -->
	{{ HTML::script('AdminLTE-2.3.0/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}
    <!-- Slimscroll -->
	{{ HTML::script('AdminLTE-2.3.0/plugins/slimScroll/jquery.slimscroll.min.js') }}
    <!-- FastClick -->
	{{ HTML::script('AdminLTE-2.3.0/plugins/fastclick/fastclick.min.js') }}
    <!-- AdminLTE App -->
	{{ HTML::script('AdminLTE-2.3.0/dist/js/app.min.js') }}
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	{{ HTML::script('AdminLTE-2.3.0/dist/js/pages/dashboard.js') }}
    <!-- AdminLTE for demo purposes -->
	{{ HTML::script('AdminLTE-2.3.0/dist/js/demo.js') }}
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="">
	<meta name="author" content="">
	<meta name="robots" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Lezato : Restaurant Admin Template">
	<meta property="og:title" content="Lezato : Restaurant Admin Template">
	<meta property="og:description" content="Lezato : Restaurant Admin Template">
	<meta property="og:image" content="https://lezato.dexignzone.com/xhtml/social-image.png">
	<meta name="format-detection" content="telephone=no">

	<!-- PAGE TITLE HERE -->
	<title>@yield('judul')</title>

	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="{{ asset('logo_web.png') }}">
    <!-- Datatable -->
    <link href="{{ asset('vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('vendor/chartist/css/chartist.min.css') }}">
    <link href="{{ asset('vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
	<link href="{{ asset('vendor/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet">
	<!-- Style css -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>
<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="gooey">
		  <span class="dot"></span>
		  <div class="dots">
			<span></span>
			<span></span>
			<span></span>
		  </div>
		</div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        @include('layout.nav_header')
        <!--**********************************
            Nav header end
        ***********************************-->

		<!--**********************************
            Header start
        ***********************************-->
        @include('layout.header')
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="deznav">
            <div class="deznav-scroll">
				<ul class="metismenu" id="menu">
                    <li class="mm-@yield('dashboard')}">
                        <a href="{{ route('dashboard') }}" aria-expanded="false">
							<i class="flaticon-025-dashboard"></i>
							<span class="nav-text">Dashboard</span>
						</a>

                    </li>
                    <li class="mm-@yield('profile')">
                        <a href="{{ '/profile' }}" aria-expanded="false">
						    <i class="flaticon-050-info"></i>
							<span class="nav-text">Profile</span>
						</a>
                    </li>
                    <li class="mm-@yield('data')">
                        <a href="{{ route('data') }}" aria-expanded="false">
						    <i class="flaticon-017-clipboard"></i>
							<span class="nav-text">Data</span>
						</a>
                    </li>
                </ul>
			</div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

		<!--**********************************
            Content body start
        ***********************************-->
        @yield('content')
        <!--**********************************
            Content body end
        ***********************************-->



        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">

            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="https://dexignzone.com/" target="_blank">NetsinCode</a> 2024</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

		<!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


	</div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->

    @include('layout.js')
</body>
</html>

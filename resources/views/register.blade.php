
<!DOCTYPE html>
<html lang="en" class="h-100">

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
	<title>Halaman Login</title>

	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="vh-100">
    <div class="authincation h-100">
        @include('sweetalert::alert')
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
									<div class="text-center mb-3">
										<a href="{{ route('login') }}"><img src="{{ asset('logo_web.png') }}" alt="" width="130"></a>
									</div>
                                    <h3 class="text-center">Halaman Register</h3>
                                    <h5 class="text-center mb-4">Aplikasi Penyiraman Bayam Otomatis Dengan NodeMCU</h5>
                                    @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger" role="alert">
                                            <ul>
                                                <li>{{ $error }}</li>
                                            </ul>
                                        </div>
                                        @endforeach
                                    @endif
                                    <form method="POST" action="">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Nama</strong></label>
                                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Masukan Nama" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Username</strong></label>
                                            <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="Masukan Username" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Password</strong></label>
                                            <input type="password" name="password" class="form-control" placeholder="Masukan Password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Re-Password</strong></label>
                                            <input type="password" name="repassword" class="form-control" placeholder="Masukan Ulang Password" required>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
                                        </div>
                                    </form>
                                    <div class="new-account mt-3">
                                        <p>Sudah punya akun? <a class="text-primary" href="{{ route('login') }}">Login</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('vendor/global/global.min') }}.js"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>
    <script src="{{ asset('js/deznav-init.js') }}"></script>
	{{-- <script src="{{ asset('js/styleSwitcher.js') }}"></script> --}}
</body>
</html>

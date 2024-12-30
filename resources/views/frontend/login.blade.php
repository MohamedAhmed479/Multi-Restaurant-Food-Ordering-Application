<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Askbootstrap">
    <meta name="author" content="Askbootstrap">
    <title>Osahan Eat - Online Food Ordering Website HTML Template</title>
    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="img/favicon.png">
    <!-- Bootstrap core CSS-->
    <link href="{{ asset('frontend') }}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome-->
    <link href="{{ asset('frontend') }}/vendor/fontawesome/css/all.min.css" rel="stylesheet">
    <!-- Font Awesome-->
    <link href="{{ asset('frontend') }}/vendor/icofont/icofont.min.css" rel="stylesheet">
    <!-- Select2 CSS-->
    <link href="{{ asset('frontend') }}/vendor/select2/css/select2.min.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{ asset('frontend') }}/css/osahan.css" rel="stylesheet">
</head>

<body class="bg-white">
    <div class="container-fluid">
        <div class="row no-gutter">
            <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
            <div class="col-md-8 col-lg-6">
                <div class="login d-flex align-items-center py-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-9 col-lg-8 mx-auto pl-5 pr-5">
                                <h3 class="login-heading mb-4">Welcome back!</h3>

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="form-label-group">
                                        <input type="email" id="inputEmail" name="email" value="{{ old('email') }}"
                                            class="form-control" placeholder="Email address">
                                        <label for="inputEmail">Email address</label>
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />

                                    <div class="form-label-group">
                                        <input type="password" id="inputPassword" class="form-control" name="password"
                                            placeholder="Password">
                                        <label for="inputPassword">Password</label>
                                    </div>
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" name="remember" id="remember">
                                        <label class="custom-control-label" for="remember">Remember password</label>
                                    </div>

                                    <button type="submit"
                                        class="btn btn-lg btn-outline-primary btn-block btn-login text-uppercase font-weight-bold mb-2">
                                        Sign in
                                    </button>

                                    <div class="text-center pt-3">
                                        Don’t have an account? <a class="font-weight-bold"
                                            href="{{ Route('register') }}">Sign
                                            Up</a>
                                    </div>
                                </form>


                                <hr class="my-4">
                                <p class="text-center">LOGIN WITH</p>
                                <div class="row">
                                    <div class="col pr-2">
                                        <button
                                            class="btn pl-1 pr-1 btn-lg btn-google font-weight-normal text-white btn-block text-uppercase"
                                            type="submit"><i class="fab fa-google mr-2"></i> Google</button>
                                    </div>
                                    <div class="col pl-2">
                                        <button
                                            class="btn pl-1 pr-1 btn-lg btn-facebook font-weight-normal text-white btn-block text-uppercase"
                                            type="submit"><i class="fab fa-facebook-f mr-2"></i> Facebook</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="{{ asset('frontend') }}/vendor/jquery/jquery-3.3.1.slim.min.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('frontend') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JavaScript-->
    <script src="{{ asset('frontend') }}/vendor/select2/js/select2.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('frontend') }}/js/custom.js"></script>
</body>

</html>
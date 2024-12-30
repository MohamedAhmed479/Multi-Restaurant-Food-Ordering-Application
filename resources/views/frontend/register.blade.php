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
    <link rel="icon" type="image/png" href="{{ asset('frontend') }}/img/favicon.png">
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
                                <h3 class="login-heading mb-4">New Buddy!</h3>
                                <form action="{{ route('register') }}" method="POST">
                                    @csrf

                                    <div class="form-label-group">
                                        <input type="text" id="name" name="name" class="form-control"
                                            :value="old('name')" placeholder="Your Name">
                                        <label for="name">Your Name</label>
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>

                                    <div class="form-label-group">
                                        <input type="text" id="address" name="address" class="form-control"
                                            :value="old('address')" placeholder="Your Address">
                                        <label for="address">Your Address</label>
                                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                    </div>

                                    <div class="form-label-group">
                                        <input type="text" id="phone" name="phone" class="form-control"
                                            :value="old('phone')" placeholder="Your Phone">
                                        <label for="phone">Your Phone</label>
                                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                    </div>

                                    <div class="form-label-group">
                                        <input type="email" id="inputEmail" name="email" class="form-control"
                                            :value="old('email')" placeholder="Email address">
                                        <label for="inputEmail">Email Address</label>
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>

                                    <div class="form-label-group">
                                        <input type="password" id="inputPassword" name="password" class="form-control"
                                            placeholder="Password">
                                        <label for="inputPassword">Password</label>
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>

                                    <div class="form-label-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" placeholder="Password">
                                        <label for="password_confirmation">Confirm Password</label>
                                    </div>

                                    <button type="submit"
                                        class="btn btn-lg btn-outline-primary btn-block btn-login text-uppercase font-weight-bold mb-2">
                                        Sign Up
                                    </button>


                                    <div class="text-center pt-3">
                                        Already have an Account? <a class="font-weight-bold" href="{{Route("login")}}">Sign
                                            In</a>
                                    </div>

                                </form>
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

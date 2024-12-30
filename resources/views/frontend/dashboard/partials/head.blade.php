<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Askbootstrap">
    <meta name="author" content="Askbootstrap">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Dashboard - Online Food Ordering Website</title>
    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/favicon.png') }}">
    <!-- Bootstrap core CSS-->
    <link href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome-->
    <link href="{{ asset('frontend/vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <!-- Font Awesome-->
    <link href="{{ asset('frontend/vendor/icofont/icofont.min.css') }}" rel="stylesheet">
    <!-- Select2 CSS-->
    <link href="{{ asset('frontend/vendor/select2/css/select2.min.css') }}" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{ asset('frontend/css/osahan.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('frontend/vendor/owl-carousel/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/vendor/owl-carousel/owl.theme.css') }}">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    
    <script src="https://js.stripe.com/v3/"></script>

    <!-- CSS -->
    <style>
        .star-rating i {
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s ease-in-out;
        }

        .star-rating i.active {
            color: #dd646e;
        }

        #rating-value {
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
    </style>
    <style>
        /* الشائع بين الأزرار */
        .like-btn,
        .dislike-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #f5a9a9;
            border-radius: 50px;
            background-color: transparent;
            padding: 5px 15px;
            font-size: 16px;
            color: #f05a5a;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .like-btn i,
        .dislike-btn i {
            margin-right: 5px;
        }

        .like-btn:hover,
        .dislike-btn:hover {
            background-color: #f5a9a9;
            color: white;
        }

        /* زر الإعجاب */
        .like-btn {
            margin-right: 10px;
        }

        /* زر عدم الإعجاب */
        .dislike-btn {
            margin-left: 10px;
        }
    </style>


</head>

<head>

    <meta charset="utf-8" />
    <title>Client Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('backend') }}/assets/images/favicon.ico">

    <!-- plugin css -->
    <link href="{{ asset('backend') }}/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css"
        rel="stylesheet" type="text/css" />

    <!-- preloader css -->
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/css/preloader.min.css" type="text/css" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- DataTables -->
    <link href="{{ asset('backend') }}/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend') }}/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css"
        rel="stylesheet" type="text/css" />


    <!-- Bootstrap Css -->
    <link href="{{ asset('backend') }}/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('backend') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('backend') }}/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">

</head>



<style>
    /* Container for input and buttons */
    .p-3.border-top {
        padding: 15px;
        background-color: #f9f9f9;
        border-top: 1px solid #ddd;
    }

    /* Style the text input field */
    #message {
        border-radius: 30px;
        padding-left: 20px;
        padding-right: 10px;
        height: 40px;
        width: 100%;
        box-sizing: border-box;
        font-size: 14px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
    }

    /* Style the send button to match the previous design */
    .chat-send {
        background-color: #4e73df;
        /* Blue color */
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        display: inline-flex;
        align-items: center;
        font-size: 14px;
        color: white;
    }

    /* Hover effect for the send button */
    .chat-send:hover {
        background-color: #2c3e9d;
    }

    /* Send button icon style */
    .chat-send i {
        margin-left: 5px;
        /* Add some space between the text and icon */
    }


    /* Style the image upload button */
    .chat-upload {
        background-color: #00b0ff;
        border: none;
    }

    /* Style the send button */
    .chat-send {
        background-color: #4e73df;
        border: none;
    }

    /* Hover effect for buttons */
    .btn:hover {
        opacity: 0.8;
    }

    /* Add space between buttons */
    .mx-2 {
        margin-left: 10px;
        margin-right: 10px;
    }
</style>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>تسجيل الدخول</title>

    <link href="{{asset("mdb/css/bootstrap.min.css")}}" rel="stylesheet">
    {{-- MDBootstrap --}}
    <link href="{{asset("mdb/css/mdb.min.css")}}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
          integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>
<body>
<div class="container">
        <div class="card card-image mx-auto d-block primary-color">
            <div class="text-white text-center py-5 px-4 my-5">
                <div>
                    <h2 class="card-title h1-responsive pt-3 mb-5 font-bold"><strong>معهد تراث الانبياء للدراسات
                            الحوزويه الالكترونيه</strong></h2>
                    <p class="mx-5 mb-5">لتسجيل الدخول يرجى التوجه الى موقع المعهد الدراسي
                    </p>
                    <a href="https://edu.turathalanbiaa.com/login" class="btn btn-outline-white btn-md"><i
                                class="fas fa-sign-in-alt left"></i> تسجيل الدخول</a>
                </div>
            </div>
        </div>
    </div>
<footer class="page-footer font-small primary-color fixed-bottom">
    <div class="footer-copyright text-center py-3" style="direction: ltr; text-align: right;">
        <span>© 2018 Copyright:</span>
        <a href="https://turathalanbiaa.com" target="_blank">turathalanbiaa.com</a>
    </div>
</footer>
</body>
</html>

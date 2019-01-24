<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield("title")
    {{-- Bootstrap Core CSS --}}
    <link href="{{asset("mdb/css/bootstrap.min.css")}}" rel="stylesheet">
    {{-- MDBootstrap --}}
    <link href="{{asset("mdb/css/mdb.min.css")}}" rel="stylesheet">
    <style>
        body {
            direction: rtl;
            text-align: right;
        }
    </style>
</head>
<body>
{{-- Main Navigation --}}
<header>
    {{-- Navbar --}}
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark primary-color">
        <div class="container">
            {{-- Navbar Brand --}}
            <a class="navbar-brand pull-right mr-0 ml-4" href="javascript:void(0);">
                <img src="{{asset("mdb/img/escp.png")}}" height="20">
                <span class="d-inline-block align-top mr-2">لوحة التحكم</span>
            </a>
        </div>
    </nav>
</header>

{{-- Main Content--}}
<main style="margin: 60px 0;">
    <div class="container">
        <div class="jumbotron text-center default-color white-text mx-2 mb-5">
            <h2 class="card-title h2">لوحة التحكم الخاصة بالنظام الامتحاني</h2>
            <p class="my-4 h5">معهد تراث الانبياء (عليهم السلام)</p>

            <div class="row d-flex justify-content-center">
                <div class="col pb-2">
                    <p class="card-text h5">لايمكنك القيام باي حدث داخل النظام الامتحاني الا بعد فتح الحساب، يرجى التواصل مع المدير لفتح الحساب</p>
                </div>
            </div>

            <hr class="my-4 rgba-white-light">

            <div class="pt-2">
                <a href="/control-panel/logout" class="btn btn-outline-white">
                    <span>تسجيل الخروج</span>
                </a>
            </div>

        </div>
        <!-- Jumbotron -->
    </div>
</main>

{{-- Footer --}}
<footer class="page-footer font-small primary-color fixed-bottom">
    <div class="footer-copyright text-center py-3" style="direction: ltr; text-align: right;">
        <span>© 2018 Copyright:</span>
        <a href="https://turathalanbiaa.com" target="_blank">turathalanbiaa.com</a>
    </div>
</footer>

{{-- SCRIPTS --}}
{{-- JQuery --}}
<script type="text/javascript" src="{{asset("mdb/js/jquery-3.3.1.min.js")}}"></script>
{{-- Bootstrap Core JavaScript --}}
<script type="text/javascript" src="{{asset("mdb/js/bootstrap.min.js")}}"></script>
{{-- MDB Core JavaScript --}}
<script type="text/javascript" src="{{asset("mdb/js/mdb.min.js")}}"></script>
</body>
</html>
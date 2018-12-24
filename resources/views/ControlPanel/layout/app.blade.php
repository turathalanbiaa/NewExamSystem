<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield("title")
    <!-- Font Awesome icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <!-- Bootstrap core CSS -->
    <link href="{{asset("mdb/css/bootstrap.min.css")}}" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="{{asset("mdb/css/mdb.min.css")}}" rel="stylesheet">
    <!-- MDBootstrap Datatables  -->
    <link href="{{asset("mdb/css/addons/datatables.min.css")}}" rel="stylesheet">
    <!-- My Custom Styles -->
    <link href="{{asset("mdb/css/style.css")}}" rel="stylesheet">

</head>
<body>
    <!--Main Navigation-->
    <header>
        @include("ControlPanel.layout.navbar")
    </header>
    <!--Main Navigation-->

    <!--Main layout-->
    <main style="margin: 60px 0;">
        @yield("content")
    </main>
    <!--Main layout-->

    <!--Footer-->
    <footer class="page-footer font-small default-color fixed-bottom">
        <!-- Copyright -->
        <div class="footer-copyright text-center py-3" style="direction: ltr; text-align: right;">
            <span>© 2018 Copyright:</span>
            <a href="https://turathalanbiaa.com" target="_blank">turathalanbiaa.com</a>
        </div>
        <!-- Copyright -->
    </footer>
    <!--Footer-->

    <!-- SCRIPTS -->
    <!-- JQuery -->
    <script type="text/javascript" src="{{asset("mdb/js/jquery-3.3.1.min.js")}}"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="{{asset("mdb/js/popper.min.js")}}"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="{{asset("mdb/js/bootstrap.min.js")}}"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="{{asset("mdb/js/mdb.min.js")}}"></script>
    <!-- MDBootstrap Datatables  -->
    <script type="text/javascript" src="{{asset("mdb/js/addons/datatables.min.js")}}"></script>
    <!-- My Custom Script -->
    @yield("script")
</body>
</html>

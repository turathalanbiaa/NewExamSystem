<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    @yield("title")

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{asset("fdb/css/bootstrap.min.css")}}">
    <!-- Fluent Design Bootstrap -->
    <link rel="stylesheet" href="{{asset("fdb/css/fluent.css")}}">
    <!-- Micon icons-->
    <link rel="stylesheet" href="{{asset("fdb/css/micon.min.css")}}">
    <!--Custom style -->
    <link rel="stylesheet" href="{{asset("fdb/css/style.css")}}">
    <!-- JQuery -->
    <script type="text/javascript" src="{{asset("fdb/js/jquery-3.3.1.min.js")}}"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="{{asset("fdb/js/popper.min.js")}}"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="{{asset("fdb/js/bootstrap.min.js")}}"></script>
</head>
<body>
    @include("ControlPanel.layout.navbar")

    @yield("content")
    @yield("script")
</body>
</html>

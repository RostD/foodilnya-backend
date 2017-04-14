<!DOCTYPE html>
<html lang="en">
<head>
    <title>Панель управления - @yield('title')</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/ctrl.css')}}">
    @yield('styles')
</head>
<body>
@yield('content')

<!-- jQuery first, then Tether, then Bootstrap JS. -->
<script src="{{asset('js/jquery-3.2.0.min.js')}}"></script>
<script src="{{asset('js/tether.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>

<script src="{{asset('js/ctrl.js')}}"></script>
@yield('script')

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    @include('backend.control.includes.head')
    @yield('head')
    <title>@yield('title') - Панель управления</title>
</head>
<body>

@yield('content')

<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
@yield('scripts')
</body>
</html>
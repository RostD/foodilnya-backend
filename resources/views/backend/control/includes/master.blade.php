<!DOCTYPE html>
<html lang="en">
<head>
    @include('backend.control.includes.head')
    @yield('head')
    <title>@yield('title') - Панель управления</title>
</head>
<body>
@yield('priority-content')
@include('backend.control.includes.top')
<div class="container-fluid my-overflow-scroll-y">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            @include('backend.control.includes.left')
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            @yield('content')
        </div>
    </div>
</div>

<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
@yield('scripts')
</body>
</html>
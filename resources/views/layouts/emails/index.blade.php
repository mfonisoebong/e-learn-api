<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @yield('head')

    <style>
        body {
            font-family: system-ui;
        }
    </style>

</head>
<body style="padding: 15px; font-size: 14px ">
@php
    $categories = \App\Models\Category::where('featured_image', true)->take(3)->get();
@endphp


<div
    style="padding-top: 15px; width: 100%; padding-bottom: 15px; background-color: #1a1a1a; display: flex; align-items: center; justify-content: space-between">
    <div style="padding-left: 10px;">
        <img style="margin-left: auto; margin-right: auto; display: block" src="{{asset('images/logo-white.png')}}"
             width="40" height="40" alt="{{ config('app.name') }}">
    </div>
    <div>
        @foreach ($categories as $category)
            <a href="{{ config('app.client_url') }}/categories/{{ $category->slug }}"
               style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 12px; margin-right: 10px;">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
</div>

@yield('content')

<div>
    <p style="text-align: center;">
        <small style="font-size: 11px;">
            © 2025 Original Peter Leo is a registered trademarks of Peter Leo London. 5055 N. Greeley Avenue Uk, OR
            97217 <a target="_blank" style="color: black;"
                     href="https://originalpeterleo.com">originalpeterleo.com</a>
        </small>
    </p>
</div>


</body>
</html>

<!DOCTYPE html>
<html lang="en" @if (Route::currentRouteName() == 'admin.rtl_layout') dir="rtl" @endif>

<head>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<meta name="csrf-token" content="{{ csrf_token() }}">

     <style>
        /* Target the modal-content for the loading state */
        .content-loading {
            position: relative; /* Essential so the ::before stays inside here */
            pointer-events: none;
            min-height: 200px; /* Ensures the loader is visible even if body is empty */
        }
        #grip-click{
            display: none;
        }
        #cog-click{
            display: none;
        }
        .colorpick-eyedropper-input-trigger{
            display: none;
        }

        .content-loading::before {
            content: '';
            position: absolute; /* Change to absolute */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 100;
            background-color: rgba(255, 255, 255, 0.8);
            background-image: url("https://user-images.githubusercontent.com/4838076/36352948-b8931430-149e-11e8-9f4b-3f00bc444a6d.gif");
            background-repeat: repeat;
            background-position: center;
            background-size: 30% 30%;
            border-radius: calc(0.3rem - 1px); /* Match Bootstrap modal rounded corners */
        }
    </style>

    @include('layouts.simple.head')
    @include('layouts.simple.css')
    
</head>

@switch(Route::currentRouteName())
    @case('admin.dashboard')
        <body onload="startTime()">
        @break

    @case('admin.box_layout')
        <body class="box-layout">
        @break

    @case('admin.rtl_layout')
        <body class="rtl">
        @break

    @case('admin.dark_layout')
        <body class="dark-only">
        @break

    @default
        <body>
@endswitch
    <!-- loader starts-->
    <div class="loader-wrapper">
        <div class="loader-index"><span></span></div>
        <svg>
            <defs></defs>
            <filter id="goo">
                <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
                <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo">
                </fecolormatrix>
            </filter>
        </svg>
    </div>
    <!-- loader ends-->

    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->

    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        @include('layouts.simple.header')
        <!-- Page Body Start-->
        <div class="page-body-wrapper">
            @include('layouts.simple.sidebar')
            <div class="page-body">
            @include('layouts.simple.noti')
                @yield('main_content')
            </div>
            
            @include('layouts.simple.footer')
        </div>
    </div>
    @include('layouts.simple.scripts')
    @include('admin.inc.alerts')
</body>

</html>

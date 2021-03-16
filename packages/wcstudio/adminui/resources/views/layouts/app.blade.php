<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Project') }} CMS System</title>
        <link rel="dns-prefetch" href="//fonts.gstatic.com">

        <link rel="stylesheet" href="https://unpkg.com/@coreui/coreui/dist/css/coreui.min.css">
        <link href="{{ asset('/static/adminui/css/daterangepicker.css') }}" rel="stylesheet" type="text/css">
{{--        <script type="text/javascript" src="{{ URL::asset('/static/adminui/js/third-party/jquery-3.4.1.min.js') }}"></script>--}}
        <script src="https://unpkg.com/@coreui/coreui/dist/js/coreui.bundle.min.js"></script>

        <script type="text/javascript" src="{{ URL::asset('/static/adminui/js/third-party/moment.min.js') }}"></script>

        @yield('head')
    </head>

    <body class="c-app pace-done">
        <div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
            <div class="c-sidebar-brand">
                <h4 class="card-title mb-0">{{ config('app.name', 'Project') }} CMS System</h4>
            </div>
            @component('adminui::components.menulist', ['menulist' => $menulist])
            @endcomponent
        </div>

        <div class="c-wrapper">
            <header class="c-header c-header-light c-header-fixed">
                <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button"
                        data-target="#sidebar" data-class="c-sidebar-show">
                    <span class="c-header-toggler-icon"></span>
                </button>
                <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button"
                        data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
                    <span class="c-header-toggler-icon"></span>
                </button>
                <ul class="c-header-nav mfs-auto">

                    <li class="c-header-nav-item dropdown">
                        <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <div class="card-body">
                                <?php if (Auth::user()) : ?>
                                <h6 class="middle mb-0">{{ Auth::user()->name }}</h6>
                                <?php else : ?>
                                <h6 class="middle mb-0">User</h6>
                                <?php endif; ?>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pt-0">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="c-sidebar-nav-icon fas fa-sign-out-alt"></i>Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>

                @component('adminui::components.breadcrumb', ['menulist' => $menulist])
                @endcomponent

            </header>

            <div class="c-body">
                <main class="c-main">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AdminLTE-3 | </title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- SEARCH FORM -->
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" v-model="search" type="search" @keyup="searchit"
                   placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <a class="btn btn-navbar" @click="searchit">
                    <i class="fas fa-search"></i>
                </a>
            </div>
        </div>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="#" class="brand-link">
            <img src="/img/logo.gif" alt="Logo" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">programs magic</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="/img/profile/{{Auth::user()->photo}}" class="img-circle elevation-2" alt="User image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                    <span><kbd class="bg-gradient-gray">{{Auth::user()->type}}</kbd></span>
                </div>
            </div>
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li class="nav-item">
                        <router-link to="/dashboard" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </router-link>
                    </li>
                    @can('isAdminOrIsAuthor')
                        <li class="nav-item has-treeview ">
                            <a href="#" class="nav-link ">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Managements
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('isAdmin')
                                    <li class="nav-item">
                                        <router-link to="/users" class="nav-link">
                                            <i class="fas fa-users nav-icon"></i>
                                            <p>Users</p>
                                        </router-link>
                                    </li>
                                @endcan
                                @can('isAdminOrIsAuthor')
                                    <li class="nav-item">
                                        <router-link to="/category" class="nav-link">
                                            <i class="fas fa-list-alt nav-icon"></i>
                                            <p>Categories</p>
                                        </router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link to="/posts" class="nav-link">
                                            <i class="fas fa-sticky-note nav-icon"></i>
                                            <p>Posts</p>
                                        </router-link>
                                    </li>
                                     <li class="nav-item">
                                        <router-link to="/comments" class="nav-link">
                                            <i class="fas fa-comments nav-icon"></i>
                                            <p>Comments</p>
                                        </router-link>
                                    </li>
                                     <li class="nav-item">
                                        <router-link to="/web-view" class="nav-link">
                                            <i class="fas fa-paint-roller nav-icon"></i>
                                            <p>WebSite</p>
                                        </router-link>
                                    </li>
                                @endcan
                                    <li class="nav-item">
                                        <router-link to="/ads" class="nav-link">
                                            <i class="fas fa-ad nav-icon"></i>
                                            <p>Ads</p>
                                        </router-link>
                                    </li>
                            </ul>
                        </li>
                        @can('isAdmin')
                            <li class="nav-item">
                                <router-link to="/developers" class="nav-link">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    <p>
                                        Developers
                                    </p>
                                </router-link>
                            </li>
                        @endcan
                    @endcan
                    <li class="nav-item">
                        <router-link to="/profile" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Profile
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                           class="nav-link ">
                            <i class="nav-icon fas fa-power-off"></i>
                            <p>
                                {{ __('Logout') }}
                            </p>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <router-view></router-view>

                <vue-progress-bar></vue-progress-bar>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    {{--    <footer class="main-footer">--}}
    {{--        <!-- To the right -->--}}
    {{--        <div class="float-right d-none d-sm-inline">--}}
    {{--            Anything you want--}}
    {{--        </div>--}}
    {{--        <!-- Default to the left -->--}}
    {{--        <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.--}}
    {{--    </footer>--}}
</div>
@auth
    <script>
        window.user = @json(auth()->user());
    </script>
@endauth
<!-- ./wrapper -->
<script src="/js/app.js"></script>
</body>
</html>

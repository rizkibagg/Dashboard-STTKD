<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Universitas | {{ $title }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('/template/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('/template/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('/template/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/template/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('/template/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/template/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('/template/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('/template/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('/template/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('/template/css/style.css') }}" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin - v2.4.1
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="/home" class="logo d-flex align-items-center">
                <img src="{{ asset('/template/img/logo.png') }}" alt="">
                <span class="d-none d-lg-block mx-3">Universitas</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="" data-bs-toggle="dropdown">
                        {{-- <img src="{{ asset('/template/img/bagus.png') }}" alt="Profile" class="rounded-circle"> --}}
                        <span class="d-none d-md-block dropdown-toggle ps-2">Administrator</span>
                        {{-- <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->nama }}</span> --}}
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>Administrator</h6>
                            <span>Administrator</span>
                            {{-- <h6>{{ Auth::user()->nama }}</h6> --}}
                            {{-- <span>{{ Auth::user()->type }}</span> --}}
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="/about">
                                <i class="bi bi-gear"></i>
                                <span>Account Settings</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="/">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    @include('Partial.sidebar')

    <main id="main" class="main">

        @yield('master')

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Universitas</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </footer><!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="{{ asset('/template/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('/template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/template/vendor/chart.js/chart.min.js') }}"></script>
<script src="{{ asset('/template/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('/template/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('/template/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('/template/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('/template/vendor/php-email-form/validate.js') }}"></script>

<!-- Jquery SweetAlert2 -->
{{-- <script src="https://code.jquery.com/jquery-3.6.3.slim.js" integrity="sha256-DKU1CmJ8kBuEwumaLuh9Tl/6ZB6jzGOBV/5YpNE2BWc=" crossorigin="anonymous"></script> --}}

<!-- Template Main JS File -->
<script src="{{ asset('/template/js/main.js') }}"></script>
{{-- @include('sweetalert::alert') --}}

</body>

</html>

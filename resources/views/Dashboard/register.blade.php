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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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

</head>

<body>

    <main>
        <div class="container">

            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="/register" class="logo d-flex align-items-center w-auto">
                                <img src="{{ asset('/template/img/logo.png') }}" alt="">
                                <span class="d-none d-lg-block">Universitas</span>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                                        <p class="text-center small">Enter your personal details to create account</p>
                                    </div>

                                    <form class="row g-3 needs-validation" method="#" action="/home">
                                        @csrf
                                        <div class="col-12">
                                            <label for="yourName" class="form-label">Your Name</label>
                                            <input type="text" name="nama" class="form-control" id="yourName" autofocus required>
                                            <div class="invalid-feedback">Please, enter your name!</div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourPosision" class="form-label">User Type</label>
                                            <select class="form-select" class="form-control" id="yourPosision" name="type" required>
                                                <option value="" selected>Choose Your Type</option>
                                                <option value="Administrator" disabled>Administrator</option>
                                                <option value="Editor" disabled>Editor</option>
                                                <option value="Contributor">Contributor</option>
                                                <option value="General User">General User</option>
                                            </select>
                                            <div class="invalid-feedback">Please, choose your type!</div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Username</label>
                                            <div class="input-group has-validation">
                                                <input type="text" name="username" class="form-control" id="yourUsername" required>
                                                <div class="invalid-feedback">Please choose a username.</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" id="yourPassword" required>
                                            <div class="invalid-feedback">Please enter your password!</div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required>
                                                <label class="form-check-label" for="acceptTerms">I agree and accept the <a href="#">terms and conditions</a></label>
                                                <div class="invalid-feedback">You must agree before submitting.</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Create Account</button>
                                        </div>
                                        <div class="col-12">
                                            <p class="small mb-0">Already have an account? <a href="/">Log in</a></p>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->

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

    <!-- Template Main JS File -->
    <script src="{{ asset('/template/js/main.js') }}"></script>
    {{-- @include('sweetalert::alert') --}}

</body>

</html>

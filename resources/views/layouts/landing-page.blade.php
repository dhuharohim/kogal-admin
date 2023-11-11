<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css ') }}" rel="stylesheet">

    <title>Kogal Kargo</title>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <div class="navbar-container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
                <div class="logo" style="width: 120px;">
                    <img src="{{ asset('assets/img/kjm-logo.png') }}" width="100%">
                </div>
                <div class="collapse navbar-collapse justify-content-lg-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Cek Resi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Tentang Kami</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Hubungi Kami</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="hero-section" style="background-image: url('/assets/img/plane-boxes.jpg')">
                <div class="background">
                        
                </div>
            </div>
        </div>
        <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>

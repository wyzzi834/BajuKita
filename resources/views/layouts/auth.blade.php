<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main/app-dark.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/png">

    <link rel="stylesheet" href="{{ asset('assets/css/shared/iconly.css') }}">
    <style>
        :root {
            --store-bg: #f4f7fb;
            --store-card: #ffffff;
            --store-text: #1f2f5f;
            --store-muted: #65758b;
            --store-primary: #435ebe;
            --store-primary-hover: #3249a8;
            --store-border: #dbe3ef;
        }

        body {
            min-height: 100vh;
            background:
                linear-gradient(135deg, rgba(67, 94, 190, .08), rgba(25, 135, 84, .06)),
                var(--store-bg);
            color: var(--store-text);
        }

        #app {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }

        #app > .row {
            width: 100%;
            margin-top: 0 !important;
        }

        .auth-card {
            border: 1px solid var(--store-border);
            border-radius: 14px !important;
            background: var(--store-card);
            box-shadow: 0 18px 50px rgba(31, 47, 95, .12) !important;
        }

        .auth-card h3 {
            color: var(--store-text);
            font-weight: 800;
        }

        .auth-card p,
        .auth-card label {
            color: var(--store-muted);
        }

        .auth-card .form-control {
            border-color: var(--store-border);
            color: var(--store-text);
            background-color: #fff;
        }

        .auth-card .form-control:focus {
            border-color: var(--store-primary);
            box-shadow: 0 0 0 .2rem rgba(67, 94, 190, .14);
        }

        .auth-card .form-control-icon {
            color: var(--store-muted);
        }

        .auth-card .btn-primary {
            border-color: var(--store-primary);
            background: var(--store-primary);
        }

        .auth-card .btn-primary:hover,
        .auth-card .btn-primary:focus {
            border-color: var(--store-primary-hover);
            background: var(--store-primary-hover);
        }

        .auth-card a {
            color: var(--store-primary);
            font-weight: 700;
        }

        .auth-card .divider .divider-text {
            color: var(--store-muted);
            background: var(--store-card);
        }
    </style>

</head>

<body>
    <div id="app">
        <div class="row justify-content-center mt-5">
            @yield('content')
        </div>
    </div>
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

</body>

</html>

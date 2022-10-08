<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

        <!-- Scripts -->
        {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>

        <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @auth
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <div class="jumbotron">
                            <h1 class="display-4">You're logged in as {{ Auth::user()->role ? 'Admin' : "user" }}!</h1>
                            <p class="lead"></p>
                            <hr class="my-4">
                        </div>
                    </div>
                </header>
            @endauth

            <!-- Page Content -->
            <main class="album py-5 bg-light">
                {{ $slot }}
            </main>

            <footer class="text-muted py-4">
                <div class="container">
                    <p class="float-right">
                        <a href="#">Back to top</a>
                    </p>
                    <p class="float-left">
                        <a href="{{ route('blog.create') }}">Add Blog</a>
                    </p>
                    <div class="clearfix"></div>
                    <p>Album example is © {{ date('Y') }} Xyz.</p>
                </div>
            </footer>

        </div>
    </body>
</html>

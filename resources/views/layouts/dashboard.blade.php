<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bengkel Management') }} | @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans antialiased" data-url="{{ url('/') }}">
    <div class="flex dark:bg-gray-900">
        <x-sidebar class="flex-grow-0 w-72 min-h-screen flex-shrink-0 hidden md:block" />
        <main class="w-full p-3">
            @yield('content')
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var base_url = $('body').data('url');

        document.getElementsByClassName('numeric-input').addEventListener('input', function(e) {
            // Hanya izinkan angka 0-9
            this.value = this.value.replace(/[^0-9]/g, '');

        });

        function formatRupiah(angka) {
            if (!angka) return '';

            angka = parseInt(angka, 10);
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function originalNumber(angka) {
            if (!angka) return '';

            angka = parseInt(angka.replace(/\D/g, ''));
            return angka;
        }
    </script>

    @stack('scripts')

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                showConfirmButton: true,
            });
        </script>
    @endif

</body>

</html>

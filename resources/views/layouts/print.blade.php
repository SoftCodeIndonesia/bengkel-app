<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="{{ asset('assets/app/img/logo-bengkel.jpg') }}" type="image/jpg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
        @page {
            size: A4;
            /* Sets the page size to A4 */
            /* margin: 20mm; */
            /* Sets a 20mm margin on all sides */
        }

        body {
            display: flex;
            font-size: 10px !important;
            font-style: normal !important;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">



        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            <div class="bg-white shadow overflow-hidden ">
                <div class="p-4 flex justify-between items-center border-b border-gray-600">
                    <p class="text-black">{{ $data['unique_id'] }}</p>
                    <p>{{ $data['tanggal'] }}</p>
                </div>
                <div class="px-4 mt-5">
                    <h3 class="font-bold" style="font-size: 15px">Bengkel 88Autocare</h3>
                    <p>Jl. Kh Hasyim No 19, Jakarta Barat</p>
                </div>
                @yield('content')
                <div class="mt-16 pt-8" style="margin-top: 64px; padding-top: 32px;">
                    <div class="text-center">
                        <p class="mb-4">Pemilik</p>
                        <div class="mt-12">
                            <p class="font-medium">(__________________________)</p>
                            <p class="text-black">{{ $data['customer_name'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    @stack('scripts')
    <script>
        window.print();
    </script>
</body>

</html>

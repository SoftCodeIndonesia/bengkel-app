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
            font-family: 'Times New Roman' !important;
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
                <div class="p-7 text-center border-b border-gray-600">
                    {{-- <p class="text-black">{{ $data['unique_id'] }}</p>
                    <p>{{ $data['tanggal'] }}</p> --}}
                    <div style="font-size: 30px; line-height: 40px" class="font-bold">{{ $data['type'] ?? 'INVOICE' }}
                    </div>
                    <div style="font-size: 20px; line-height: 30px" class="mt-2">Bengkel 88Autocare</div>
                    <div style="font-size: 10px">Jl. K.H.M. Usman RT. 01 RW. 04, Kukusan, Kecamatan Beji, Kota Depok,
                        Jawa Barat 16425
                    </div>
                    <div style="font-size: 10px">
                        Telepon 087821878358/089661739000
                    </div>
                    <div style="font-size: 10px">
                        Email autocare88.workshop@gmail.com
                    </div>
                </div>

                <div class="p-4 mt-3">
                    <table class="" style="width: auto; table-layout: fixed;">

                        <tbody>
                            <tr>
                                <th class="px-2 py-1 text-left" width="100px">No</th>
                                <td class="px-2 py-1 text-end">{{ $data['unique_id'] }}</td>
                            </tr>
                            <tr>
                                <th class="px-2 py-1 text-left">Tanggal</th>
                                <td class="px-2 py-1 text-end">{{ $data['tanggal'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @yield('content')
                <div class="mt-16 pt-8 flex justify-end" style="margin-top: 64px; padding-top: 32px;">
                    <div class="w-1/3">
                        <div class="text-center">
                            <p class="mb-4">Pemilik</p>
                            <div class="mt-12">

                                <p class="text-black">{{ $data['customer_name'] }}</p>
                            </div>
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

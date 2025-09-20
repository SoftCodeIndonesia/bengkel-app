<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="{{ asset('assets/app/img/logo-bengkel.jpg') }}" type="image/jpg">

    <!-- Fonts -->
    {{-- <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}

    <style>
        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 12px;
        }
    </style>

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
            /* font-family: 'Times New Roman' !important; */
        }

        table,
        th,
        td {
            border: 1px solid rgb(187, 187, 187);
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen">



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
            <div class="bg-white overflow-hidden ">
                <div class="p-4 flex justify-between text-start border-b border-gray-40000">


                    <div class="">
                        <div style="font-size: 20px" class="mt-2 font-bold">
                            {{ $data['type'] ?? 'INVOICE' }}</div>
                        <div style="font-size: 10px" class="font-bold">88AUTOCARE - Nissan, Datsun & Umum Specialist
                        </div>
                        <div style="font-size: 10px">Jl. K.H.M. Usman RT. 01 RW. 04, Kukusan, Kecamatan Beji, Kota
                            Depok,
                            Jawa Barat 16425
                        </div>
                        <table class="mt-2" style="width: auto; table-layout: fixed; border: none;">

                            <tbody>
                                <tr>
                                    <th class="p-0 border-none text-left" width="100px">Phone</th>
                                    <td class="p-0 border-none text-left">: 087821878358/089661739000</td>
                                </tr>
                                <tr>
                                    <th class="p-0 border-none text-left">Email</th>
                                    <td class="p-0 border-none text-left">: autocare88.workshop@gmail.com</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="">
                        <img src="{{ asset('assets/app/img/logo-bengkel-1.png') }}" width="300" alt="Logo">
                    </div>

                </div>

                {{-- <div class="p-4 mt-3">
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
                </div> --}}
                @yield('content')


            </div>
        </main>

        <footer class="fixed bottom-0 left-0 right-0">
            <div class="w-1/3 ml-auto text-center">
                <p class="mb-4">
                    {{ $data['type'] == 'WORK ORDER' || $data['type'] == 'ESTIMASI' ? 'Pemilik' : 'Hormat Kami,' }}</p>
                <div class="mt-12">
                    <p class="text-black">
                        {{ $data['type'] == 'WORK ORDER' || $data['type'] == 'ESTIMASI' ? $data['customer_name'] : '88AutoCare' }}
                    </p>
                </div>
            </div>
        </footer>
    </div>
    @stack('scripts')
    <script>
        window.print();
    </script>
</body>

</html>

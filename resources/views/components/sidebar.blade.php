<aside id="sidebar-multi-level-sidebar" {{ $attributes }}
    class="fixed top-0 left-0 z-40 w-72 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-800">
        <div class="pb-2 px-2">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <img src="{{ asset('assets/app/img/logo-bengkel-1.png') }}" class="h-12 w-full mr-3" alt="Logo Bengkel">
                {{-- <span class="text-xl font-semibold">BengBeng</span> --}}
            </a>
        </div>
        <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center p-2 rounded-lg text-gray-400 hover:bg-gray-700 group">
                    <svg class="w-5 h-5 transition duration-75 text-gray-400 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path
                            d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                            d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base transition duration-75 rounded-lg group  text-gray-400 hover:bg-gray-700"
                    aria-controls="dropdown-example-purchase" data-collapse-toggle="dropdown-example-purchase">

                    <svg class="w-6 h-6 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312" />
                    </svg>


                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Pembelian</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dropdown-example-purchase" class="hidden py-2 space-y-2">
                    <li>
                        <a href="{{ route('purchases.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">Data
                            Pembelian Sparepart</a>
                    </li>
                    <li>
                        <a href="{{ route('suppliers.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">
                            Data Supplier</a>
                    </li>
                </ul>
            </li>
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base  transition duration-75 rounded-lg group  text-gray-400 hover:bg-gray-700"
                    aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                    <svg class="w-6 h-6 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M7 2a2 2 0 0 0-2 2v1a1 1 0 0 0 0 2v1a1 1 0 0 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H7Zm3 8a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm-1 7a3 3 0 0 1 3-3h2a3 3 0 0 1 3 3 1 1 0 0 1-1 1h-6a1 1 0 0 1-1-1Z"
                            clip-rule="evenodd" />
                    </svg>

                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Pelanggan</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dropdown-example" class="hidden py-2 space-y-2">
                    <li>
                        <a href="{{ route('customers.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">Data
                            Pelanggan</a>
                    </li>
                    <li>
                        <a href="{{ route('vehicles.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">Data
                            Kendaraan</a>
                    </li>
                </ul>
            </li>
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base  transition duration-75 rounded-lg group  text-gray-400 hover:bg-gray-700"
                    aria-controls="dropdown-example-workorder" data-collapse-toggle="dropdown-example-workorder">

                    <svg class="w-6 h-6 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z" />
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                    </svg>


                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Workshop</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dropdown-example-workorder" class="hidden py-2 space-y-2">
                    <li>
                        <a href="{{ route('job-orders.create') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">
                            Buat Work Order</a>
                    </li>
                    <li>
                        <a href="{{ route('job-orders.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">Daftar
                            Work Order</a>
                    </li>
                    <li>
                        <a href="{{ route('estimation.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">Daftar
                            Estimasi</a>
                    </li>
                    <li>
                        <a href="{{ route('service-packages.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">Paket
                            Service</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('sales.index') }}"
                    class="flex items-center p-2  rounded-lg text-gray-400  hover:bg-gray-700 group">
                    <svg class="w-6 h-6 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                            d="M8 7V6a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1M3 18v-7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                    </svg>


                    <span class="flex-1 ms-3 whitespace-nowrap">Penjualan</span>

                </a>
            </li>
            <li>
                <a href="{{ route('appointments.index') }}"
                    class="flex items-center p-2  rounded-lg text-gray-400  hover:bg-gray-700 group">
                    <svg class="w-6 h-6 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 10h16M8 14h8m-4-7V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z" />
                    </svg>


                    <span class="flex-1 ms-3 whitespace-nowrap">Appointments</span>

                </a>
            </li>
            <li>
                <a href="{{ route('follow-ups.index') }}"
                    class="flex items-center p-2  rounded-lg text-gray-400  hover:bg-gray-700 group">
                    <svg class="w-6 h-6 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m17.0896 13.371 1.1431 1.1439c.1745.1461.3148.3287.4111.5349.0962.2063.1461.4312.1461.6588 0 .2276-.0499.4525-.1461.6587-.0963.2063-.4729.6251-.6473.7712-3.1173 3.1211-6.7739 1.706-9.90477-1.4254-3.13087-3.1313-4.54323-6.7896-1.41066-9.90139.62706-.61925 1.71351-1.14182 2.61843-.23626l1.1911 1.19193c1.1911 1.19194.3562 1.93533-.4926 2.80371-.92477.92481-.65643 1.72741 0 2.38391l1.8713 1.8725c.3159.3161.7443.4936 1.191.4936.4468 0 .8752-.1775 1.1911-.4936.8624-.8261 1.6952-1.6004 2.8382-.4565Zm-2.2152-4.39103 2.1348-2.13485m0 0 2.1597-1.90738m-2.1597 1.90738 2.1597 2.15076m-2.1597-2.15076-2.1348-1.90738" />
                    </svg>



                    <span class="flex-1 ms-3 whitespace-nowrap">History Follow Up</span>

                </a>
            </li>
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base  transition duration-75 rounded-lg group  text-gray-400 hover:bg-gray-700"
                    aria-controls="dropdown-example-sparepart" data-collapse-toggle="dropdown-example-sparepart">

                    <svg class="w-6 h-6 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M14 7h-4v3a1 1 0 0 1-2 0V7H6a1 1 0 0 0-.997.923l-.917 11.924A2 2 0 0 0 6.08 22h11.84a2 2 0 0 0 1.994-2.153l-.917-11.924A1 1 0 0 0 18 7h-2v3a1 1 0 1 1-2 0V7Zm-2-3a2 2 0 0 0-2 2v1H8V6a4 4 0 0 1 8 0v1h-2V6a2 2 0 0 0-2-2Z"
                            clip-rule="evenodd" />
                    </svg>


                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Sparepart</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dropdown-example-sparepart" class="hidden py-2 space-y-2">
                    <li>
                        <a href="{{ route('products.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">
                            Info Stok</a>
                    </li>
                    <li>
                        <a href="{{ route('movement-items.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">Data
                            Barang Masuk</a>
                    </li>
                    <li>
                        <a href="{{ route('supplies.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">Supply</a>
                    </li>
                    <li>
                        <a href="{{ route('returns.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">Retur</a>
                    </li>
                    <li>
                        <a href="{{ route('stock-opname.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">Stok
                            Opname</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('services.index') }}"
                    class="flex items-center p-2  rounded-lg text-gray-400  hover:bg-gray-700 group">
                    <svg class="w-6 h-6 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 15v3c0 .5523.44772 1 1 1h4v-4m-5 0v-4m0 4h5m-5-4V6c0-.55228.44772-1 1-1h16c.5523 0 1 .44772 1 1v1.98935M3 11h5v4m9.4708 4.1718-.8696-1.4388-2.8164-.235-2.573-4.2573 1.4873-2.8362 1.4441 2.3893c.3865.6396 1.2183.8447 1.8579.4582.6396-.3866.8447-1.2184.4582-1.858l-1.444-2.38925h3.1353l2.6101 4.27715-1.0713 2.5847.8695 1.4388" />
                    </svg>

                    <span class="flex-1 ms-3 whitespace-nowrap">Jasa</span>
                </a>
            </li>




            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base  transition duration-75 rounded-lg group  text-gray-400 hover:bg-gray-700"
                    aria-controls="finance" data-collapse-toggle="finance">

                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M9 15a6 6 0 1 1 12 0 6 6 0 0 1-12 0Zm3.845-1.855a2.4 2.4 0 0 1 1.2-1.226 1 1 0 0 1 1.992-.026c.426.15.809.408 1.111.749a1 1 0 1 1-1.496 1.327.682.682 0 0 0-.36-.213.997.997 0 0 1-.113-.032.4.4 0 0 0-.394.074.93.93 0 0 0 .455.254 2.914 2.914 0 0 1 1.504.9c.373.433.669 1.092.464 1.823a.996.996 0 0 1-.046.129c-.226.519-.627.94-1.132 1.192a1 1 0 0 1-1.956.093 2.68 2.68 0 0 1-1.227-.798 1 1 0 1 1 1.506-1.315.682.682 0 0 0 .363.216c.038.009.075.02.111.032a.4.4 0 0 0 .395-.074.93.93 0 0 0-.455-.254 2.91 2.91 0 0 1-1.503-.9c-.375-.433-.666-1.089-.466-1.817a.994.994 0 0 1 .047-.134Zm1.884.573.003.008c-.003-.005-.003-.008-.003-.008Zm.55 2.613s-.002-.002-.003-.007a.032.032 0 0 1 .003.007ZM4 14a1 1 0 0 1 1 1v4a1 1 0 1 1-2 0v-4a1 1 0 0 1 1-1Zm3-2a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1Zm6.5-8a1 1 0 0 1 1-1H18a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0v-.796l-2.341 2.049a1 1 0 0 1-1.24.06l-2.894-2.066L6.614 9.29a1 1 0 1 1-1.228-1.578l4.5-3.5a1 1 0 0 1 1.195-.025l2.856 2.04L15.34 5h-.84a1 1 0 0 1-1-1Z"
                            clip-rule="evenodd" />
                    </svg>



                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Management Keuangan</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="finance" class="hidden py-2 space-y-2">
                    <li>
                        <a href="{{ route('invoices.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">
                            Invoice
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('expenses.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">Data
                            Pengeluaran</a>
                    </li>
                    <li>
                        <a href="{{ route('reports.profit-loss') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">Data
                            Laporan Keuangan</a>
                    </li>

                </ul>
            </li>
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base  transition duration-75 rounded-lg group  text-gray-400 hover:bg-gray-700"
                    aria-controls="dropdown-example-management-karyawan"
                    data-collapse-toggle="dropdown-example-management-karyawan">

                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                            d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                    </svg>


                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Management Karyawan</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dropdown-example-management-karyawan" class="hidden py-2 space-y-2">
                    <li>
                        <a href="{{ route('employees.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">
                            Daftar Karyawan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('attendances.index') }}"
                            class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">Data
                            Absensi Karyawan</a>
                    </li>

                </ul>
            </li>



            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base  transition duration-75 rounded-lg group  text-gray-400 hover:bg-gray-700"
                    aria-controls="dropdown-example-management-user"
                    data-collapse-toggle="dropdown-example-management-user">

                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>


                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Management User</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dropdown-example-management-user" class="hidden py-2 space-y-2">
                    @can('view users')
                        <li>
                            <a href="{{ route('users.index') }}"
                                class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">
                                Daftar User
                            </a>
                        </li>
                    @endcan
                    @can('view roles')
                        <li>
                            <a href="{{ route('roles.index') }}"
                                class="flex items-center w-full p-2  transition duration-75 rounded-lg pl-11 group  text-gray-400 hover:bg-gray-700">Data
                                Role</a>
                        </li>
                    @endcan

                </ul>
            </li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="btn btn-danger flex items-center p-2 text-red-400 rounded-lg hover:bg-red-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2" />
                        </svg> Logout
                    </button>
                </form>

            </li>
        </ul>
    </div>
</aside>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <main>
        <!-- Page Header -->
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="shopping-bag"></i></div>
                                Daftar Transaksi
                            </h1>
                            <div class="page-header-subtitle">Transaksi pembelian paket sebelumnya</div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Tabel Data Transaksi -->
        
        <livewire:pages.user.components.tabel-transaksi />
      
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <!-- Responsive Plugin JS -->
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>


        <script>
                $(document).ready(function () {
                $.fn.dataTable.ext.errMode = 'none'; // Suppress all DataTables warnings

                $(document).ready(function () {
                    $('#allTransactions').DataTable({
                        ordering: false,
                        pageLength: 10,
                        autoWidth: false,
                        responsive: true, // Enable responsive feature
                        language: {
                            paginate: {
                                previous: 'Sebelumnya',
                                next: 'Selanjutnya',
                            },
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            lengthMenu: "Tampilkan _MENU_ data per halaman",
                            zeroRecords: "Tidak ada data yang ditemukan",
                        }
                    });
                });

                // Optionally handle warnings with a custom handler
                $('#allTransactions').on('error.dt', function (e, settings, techNote, message) {
                    console.log('DataTables error:', message); // Log the error instead of showing warnings
                });
            });

        </script>
    </main>
</x-app-layout>

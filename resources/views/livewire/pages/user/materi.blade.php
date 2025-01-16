<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <?php
        $paket = \App\Models\PaketList::find($id);
    ?>

    <main>
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-life-buoy"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="4"></circle><line x1="4.93" y1="4.93" x2="9.17" y2="9.17"></line><line x1="14.83" y1="14.83" x2="19.07" y2="19.07"></line><line x1="14.83" y1="9.17" x2="19.07" y2="4.93"></line><line x1="14.83" y1="9.17" x2="18.36" y2="5.64"></line><line x1="4.93" y1="19.07" x2="9.17" y2="14.83"></line></svg></div>
                                {{$paket->nama_paket}}
                            </h1>
                            <div class="page-header-subtitle">{{$paket->deskripsi}}</div>
                        </div>
                    </div>
                    <div class="page-header-search mt-4">
                        <div class="input-group input-group-joined">
                            <input class="form-control" type="text" placeholder="Search..." aria-label="Search" autofocus="">
                            <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <livewire:pages.user.components.materi-list :id="$id" />

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
        <script>
            // Check for flash message on page load
            document.addEventListener('DOMContentLoaded', function() {
                @if(session()->has('success-free'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success-free') }}',
                    // timer: 3000,
                    // showConfirmButton: false,
                    // position: 'bottom-end',
                    // toast: true
                });
                @endif
            });
        </script>
    </main>
</x-app-layout>

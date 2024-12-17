
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="user"></i></div>
                                Daftar User
                            </h1>
                        </div>
                        <div class="col-12 col-xl-auto mb-3">
                            <a class="btn btn-sm btn-light text-primary" href="{{ route('admin.user-groups') }}">
                                <i class="me-1" data-feather="users"></i>
                                Daftar Universitas
                            </a>
                            <a class="btn btn-sm btn-light text-primary" href="{{ route('admin.users.create') }}">
                                <i class="me-1" data-feather="user-plus"></i>
                                Tambahkan User Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <livewire:pages.admin.components.user-table />
        
        
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
        
        
       
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#usersTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    autoWidth: false,
                    searching: true,
                    ordering: true,
                    order: [[4, 'desc']], // Order by Tanggal Bergabung column
                    language: {
                        search: 'Cari:',
                        searchPlaceholder: 'Cari data...',
                        paginate: {
                            previous: 'Sebelumnya',
                            next: 'Selanjutnya',
                        },
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        zeroRecords: "Tidak ada data yang ditemukan",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        infoFiltered: "(disaring dari _MAX_ total data)"
                    },
                    columnDefs: [
                        { orderable: false, targets: [0, 5] } // Disable sorting for nama (with avatar) and action columns
                    ]
                });
            });
        </script>
       
</x-app-layout>
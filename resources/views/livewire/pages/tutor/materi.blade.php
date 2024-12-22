<x-app-layout>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-3">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="book"></i></div>
                            Materi
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="row">
        <div class="col col-end-1 m-3 px-4">
            <a type="button" href="{{route('materi.create')}}" class="btn btn-primary"><i data-feather="plus-square" class="me-2"></i>Buat Materi</a>
        </div>
    </div>

    <div class="row">

        <div class="col">
            <!-- Main page content -->
            <livewire:pages.tutor.components.tabel-materi />
        </div>

    </div>



    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#materiTable').DataTable({
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
                    { orderable: false, targets: [0, 4] } // Disable sorting for nama (with avatar) and action columns
                ]
            });
        });
    </script>

</x-app-layout>

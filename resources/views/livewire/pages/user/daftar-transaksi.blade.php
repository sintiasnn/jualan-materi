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

        <style>
            .table {
                border: 1px solid #dee2e6;
                border-radius: 8px;
                overflow: hidden;
            }
        
            .table th, .table td {
                border: 1px solid #dee2e6;
            }
        
            .dataTable-table {
                margin-bottom: 0;
            }
        </style>
    
        <!-- Main Page Content -->
        <div class="container-xl px-4 mt-n10">
            <div class="card shadow-sm">
                <!-- Card Header with Tabs -->
                <div class="card-header border-bottom">
                    <ul class="nav nav-tabs card-header-tabs" id="transactionTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="all-tab" href="#all" data-bs-toggle="tab" role="tab" aria-controls="all" aria-selected="true">Semua Transaksi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="unpaid-tab" href="#unpaid" data-bs-toggle="tab" role="tab" aria-controls="unpaid" aria-selected="false">Belum Dibayar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="completed-tab" href="#completed" data-bs-toggle="tab" role="tab" aria-controls="completed" aria-selected="false">Selesai</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="cancelled-tab" href="#cancelled" data-bs-toggle="tab" role="tab" aria-controls="cancelled" aria-selected="false">Batal</a>
                        </li>
                    </ul>
                </div>
            
                <!-- Card Body with Tab Content -->
                <div class="card-body">
                    <div class="tab-content" id="transactionTabContent">
                        <!-- Semua Transaksi -->
                        <div class="tab-pane fade show active" id="all">
                            <table id="allTransactions" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Nama Paket</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>TRX-1234567890</td>
                                        <td>Paket Tryout</td>
                                        <td>2024-12-10</td>
                                        <td><span class="badge bg-success">Selesai</span></td>
                                        <td><a href="#" class="btn btn-sm btn-primary">Buka Tryout</a></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>TRX-0987654321</td>
                                        <td>Paket Kelas</td>
                                        <td>2024-12-12</td>
                                        <td><span class="badge bg-warning text-dark">Belum Dibayar</span></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>TRX-5678901234</td>
                                        <td>Paket Prediksi</td>
                                        <td>2024-12-05</td>
                                        <td><span class="badge bg-danger">Batal</span></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
            
                        <!-- Belum Dibayar -->
                        <div class="tab-pane fade" id="unpaid">
                            <table id="unpaidTransactions" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Nama Paket</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>TRX-0987654321</td>
                                        <td>Paket Kelas</td>
                                        <td>2024-12-12</td>
                                        <td><span class="badge bg-warning text-dark">Belum Dibayar</span></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
            
                        <!-- Selesai -->
                        <div class="tab-pane fade" id="completed">
                            <table id="completedTransactions" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Nama Paket</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>TRX-1234567890</td>
                                        <td>Paket Tryout</td>
                                        <td>2024-12-10</td>
                                        <td><span class="badge bg-success">Selesai</span></td>
                                        <td><a href="#" class="btn btn-sm btn-primary">Buka Tryout</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
            
                        <!-- Batal -->
                        <div class="tab-pane fade" id="cancelled">
                            <table id="cancelledTransactions" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Nama Paket</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>TRX-5678901234</td>
                                        <td>Paket Prediksi</td>
                                        <td>2024-12-05</td>
                                        <td><span class="badge bg-danger">Batal</span></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- DataTables Integration -->
            <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
            
            <script>
                $(document).ready(function () {
                    // Konfigurasi DataTables
                    $('#allTransactions').DataTable({
                        ordering: false, // Menonaktifkan kemampuan sorting
                        order: [[3, 'desc']] // Urutkan berdasarkan kolom ke-4 (Tanggal) secara descending
                    });
            
                    $('#unpaidTransactions').DataTable({
                        ordering: false, 
                        order: [[3, 'desc']]
                    });
            
                    $('#completedTransactions').DataTable({
                        ordering: false, 
                        order: [[3, 'desc']]
                    });
            
                    $('#cancelledTransactions').DataTable({
                        ordering: false, 
                        order: [[3, 'desc']]
                    });
                });
            </script>
            
            
            
</x-app-layout>

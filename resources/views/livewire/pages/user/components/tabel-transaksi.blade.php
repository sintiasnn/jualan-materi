<?php
use Livewire\Volt\Component;

new class extends Component {
    public $selectedTab = 'all';

    public function switchTab($tab)
    {
        $this->selectedTab = $tab;
    }
}; ?>

<div class="container-xl px-4 mt-n10">
    <div class="card shadow-sm">
        <div class="card-header border-bottom">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <button class="nav-link {{ $selectedTab === 'all' ? 'active' : '' }}" 
                       wire:click="switchTab('all')">Semua Transaksi</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $selectedTab === 'pending' ? 'active' : '' }}" 
                       wire:click="switchTab('pending')">Belum Dibayar</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $selectedTab === 'success' ? 'active' : '' }}" 
                       wire:click="switchTab('success')">Transaksi Selesai</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $selectedTab === 'cancelled' ? 'active' : '' }}" 
                       wire:click="switchTab('cancelled')">Transaksi Batal</button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div wire:ignore>
                <table id="transactionsTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Transaksi</th>
                            <th>Tanggal</th>
                            <th>Nama Paket</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @script
    <script>
        let table;
        
        $wire.watch('selectedTab', (value) => {
            if (table) {
                table.ajax.reload();
            }
        });

        table = $('#transactionsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: @js(route('datatables.transactions')),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                data: function(d) {
                    d.status = $wire.selectedTab;
                }
            },
            columns: [
                { 
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                { data: 'kode_transaksi' },
                { data: 'tanggal_pembelian' },
                { data: 'nama_paket' },
                { data: 'harga_paket' },
                { 
                    data: 'status',
                    render: function(data) {
                        switch(data) {
                            case 'pending':
                                return '<span class="badge bg-warning text-dark">Belum Dibayar</span>';
                            case 'success':
                                return '<span class="badge bg-success">Sukses</span>';
                            case 'cancelled':
                                return '<span class="badge bg-danger">Batal</span>';
                            case 'failed':
                                return '<span class="badge bg-danger">Gagal</span>';
                            default:
                                return '-';
                        }
                    }
                },
                { 
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [[2, 'desc']],
            language: {
                processing: 'Loading...',
                search: 'Cari:',
                searchPlaceholder: 'Cari transaksi...',
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Belum ada transaksi yang tersedia",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Selanjutnya"
                }
            }
        });
    </script>
    @endscript
</div>
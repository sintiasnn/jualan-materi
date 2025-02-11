<?php

use App\Models\PaketList;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component {

    public $paketId = null;
    public function mount()
    {

    }

    public function deletePaket()
    {
        DB::beginTransaction();
        try {
            PaketList::find($this->paketId)->delete();
            DB::commit();
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Paket berhasil dihapus'
            ]);
            $this->dispatch('refreshDatatable');
        } catch (\Exception $exception){
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Gagal!',
                'text' => 'Paket gagal dihapus karena ' . $exception->getMessage(),
            ]);
        }
    }

    public function setPaketId($id){
        return $this->paketId = $id;
    }
}; ?>

<div>
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-4">

                    <div class="col-md-3 mb-3">
                        <label for="tipeFilter" class="form-label">Filter Tipe</label>
                        <select id="tipeFilter" class="form-select">
                            <option value="">Semua Tipe</option>
                            <option value="materi">Materi</option>
                            <option value="tryout">Tryout</option>
                        </select>
                    </div>


                    <div class="col-md-3 mb-3">
                        <label for="tierFilter" class="form-label">Filter Tier</label>
                        <select id="tierFilter" class="form-select">
                            <option value="">Semua Tier</option>
                            <option value="free">Gratis</option>
                            <option value="paid">Berbayar</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button id="resetFilter" class="btn btn-blue">
                            <i data-feather="refresh-ccw" class="me-1"></i>
                            Reset Filter
                        </button>
                    </div>
                </div>

                <div wire:ignore>
                    <table id="paketTable" class="table table-striped table-bordered dt-responsive nowrap"
                           style="width:100%">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Paket</th>
                            <th>Tipe</th>
                            <th>Tier</th>
                            <th>Harga</th>
                            <th>Discount</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>


                <style>
                    .btn-datatable {
                        width: 35px;
                        height: 35px;
                        padding: 0;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 0.25rem;
                    }

                    .filter-section {
                        background: #f8f9fa;
                        padding: 1rem;
                        border-radius: 0.25rem;
                        margin-bottom: 1rem;
                    }

                    div.dataTables_processing {
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        margin: 0;
                        transform: translate(-50%, -50%);
                        background: rgba(255, 255, 255, 0.9);
                        border-radius: 0.25rem;
                        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                        padding: 1rem;
                        z-index: 1000;
                    }
                </style>


            </div>
        </div>
    </div>


    <div class="modal fade" wire:ignore id="deletePaketModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="terminateSessionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="terminateSessionModalLabel">Konfirmasi Hapus Paket</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin akan menghapus paket ini ?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" type="button" wire:click="deletePaket" data-bs-dismiss="modal">Hapus</button>
                </div>
            </div>
        </div>
    </div>

</div>

@script
<script>
    document.addEventListener('livewire:initialized', function () {
        let table = $('#paketTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("datatables.paket") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                data: function (d) {
                    d.tipeFilter = $('#tipeFilter').val();
                    d.tierFilter = $('#tierFilter').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'nama_paket'},
                {data: 'tipe'},
                {data: 'tier'},
                {
                    data: 'harga',
                    render: function (data) {
                        let number = data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return `<div>Rp. ${number}</div>`
                    }
                },
                {
                    data: 'discount',
                    render: function (data){
                        let number = data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return `<div>Rp. ${number}</div>`
                    }
                },
                {
                    data: 'actions',
                    orderable: false,
                    render: function (data) {
                        return `
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="${data.edit_url}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Paket">
                                            <i data-feather="edit"></i>
                                        </a>
                                        <button class="btn btn-datatable btn-icon btn-transparent-dark" data-bs-toggle="modal" data-bs-target="#deletePaketModal" data-bs-placement="top" title="Hapus Paket" wire:click="setPaketId(${data.delete})" href="javascript:void(0);">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    `;
                    }
                }
            ],
            order: [[4, 'desc']],
            pageLength: 10,
            language: {
                processing: 'Loading...',
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
            drawCallback: function () {
                // Re-initialize feather icons after table draw
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
                // Initialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });

        // Apply filters when select values change
        $('#tipeFilter, #tierFilter').on('change', function () {
            table.draw();
        });

        // Reset filters
        $('#resetFilter').on('click', function () {
            $('#tipeFilter, #tierFilter').val('');
            table.draw();
        });

        // Re-initialize feather icons after initial load
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        Livewire.on('refreshDatatable', () => {
            table.ajax.reload();
        });

    });
</script>
@endscript

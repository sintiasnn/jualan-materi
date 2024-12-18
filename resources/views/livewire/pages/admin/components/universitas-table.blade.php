<?php
// resources/views/livewire/pages/admin/components/universitas-table.blade.php

use Livewire\Volt\Component;

new class extends Component {
}; ?>

<div>
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body">
                <table id="universitasTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama Universitas</th>
                            <th>Singkatan</th>
                            <th>Jumlah User</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>

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

                    div.dataTables_processing {
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        margin: 0;
                        transform: translate(-50%, -50%);
                        background: rgba(255,255,255,0.9);
                        border-radius: 0.25rem;
                        box-shadow: 0 0 15px rgba(0,0,0,0.1);
                        padding: 1rem;
                        z-index: 1000;
                    }
                </style>

                <script>
                document.addEventListener('livewire:initialized', function() {
                    let table = $('#universitasTable').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        ajax: {
                            url: '{{ route("datatables.universitas") }}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        },
                        columns: [
                            { data: 'name' },
                            { data: 'singkatan' },
                            { 
                                data: 'users_count',
                                className: 'text-center'
                            },
                            { 
                                data: 'actions',
                                orderable: false,
                                className: 'text-center',
                                render: function(data) {
                                    return `
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="${data.edit_url}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Universitas">
                                            <i data-feather="edit"></i>
                                        </a>
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark" href="${data.view_url}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Universitas">
                                            <i data-feather="eye"></i>
                                        </a>
                                    `;
                                }
                            }
                        ],
                        order: [[0, 'asc']],
                        pageLength: 10,
                        language: {
                            processing: 'Loading...',
                            search: 'Cari:',
                            searchPlaceholder: 'Cari universitas...',
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
                        drawCallback: function() {
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
                });
                </script>
            </div>
        </div>
    </div>
</div>
<?php
// resources/views/livewire/pages/admin/components/user-table.blade.php

use Livewire\Volt\Component;
use App\Models\RefUniversitasList;

new class extends Component {
    public $universities;

    public function mount()
    {
        $this->universities = RefUniversitasList::select('id', 'universitas_name')
            ->orderBy('universitas_name')
            ->get();
    }
}; ?>

<div>
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <label for="roleFilter" class="form-label">Filter Role</label>
                        <select id="roleFilter" class="form-select">
                            <option value="">Semua Role</option>
                            <option value="admin">Admin</option>
                            <option value="tutor">Tutor</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="universitasFilter" class="form-label">Filter Universitas</label>
                        <select id="universitasFilter" class="form-select">
                            <option value="">Semua Universitas</option>
                            @foreach($universities as $univ)
                                <option value="{{ $univ->id }}">{{ $univ->universitas_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="statusFilter" class="form-label">Filter Status</label>
                        <select id="statusFilter" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button id="resetFilter" class="btn btn-blue">
                            <i data-feather="refresh-ccw" class="me-1"></i>
                            Reset Filter
                        </button>
                    </div>
                </div>

                <table id="usersTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Universitas</th>
                            <th>Tanggal Bergabung</th>
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
                        background: rgba(255,255,255,0.9);
                        border-radius: 0.25rem;
                        box-shadow: 0 0 15px rgba(0,0,0,0.1);
                        padding: 1rem;
                        z-index: 1000;
                    }
                </style>

                <script>
                document.addEventListener('livewire:initialized', function() {
                    let table = $('#usersTable').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        ajax: {
                            url: '{{ route("datatables.users") }}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            data: function(d) {
                                d.roleFilter = $('#roleFilter').val();
                                d.universitasFilter = $('#universitasFilter').val();
                                d.statusFilter = $('#statusFilter').val();
                            }
                        },
                        columns: [
                            { 
                                data: 'name',
                                render: function(data) {
                                    return `
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <img class="avatar-img img-fluid" src="/assets/img/avatar/${data.avatar}" alt="${data.full}" />
                                            </div>
                                            ${data.full}
                                        </div>
                                    `;
                                }
                            },
                            { data: 'email' },
                            { 
                                data: 'role',
                                render: function(data) {
                                    let roleBadge = '';
                                    switch(data.name) {
                                        case 'admin':
                                            roleBadge = '<span class="badge bg-danger">Admin</span>';
                                            break;
                                        case 'tutor':
                                            roleBadge = '<span class="badge bg-purple">Tutor</span>';
                                            break;
                                        default:
                                            roleBadge = '<span class="badge bg-blue">User</span>';
                                    }
                                    
                                    let statusBadge = `<span class="badge ${data.active ? 'bg-success' : 'bg-danger'}">
                                        ${data.active ? 'Aktif' : 'Tidak Aktif'}
                                    </span>`;
                                    
                                    return roleBadge + ' ' + statusBadge;
                                }
                            },
                            { data: 'universitas' },
                            { data: 'created_at' },
                            { 
                                data: 'actions',
                                orderable: false,
                                render: function(data) {
                                    return `
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="${data.edit_url}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit User">
                                            <i data-feather="edit"></i>
                                        </a>
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark" href="${data.view_url}" data-bs-toggle="tooltip" data-bs-placement="top" title="View User">
                                            <i data-feather="eye"></i>
                                        </a>
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

                    // Apply filters when select values change
                    $('#roleFilter, #universitasFilter, #statusFilter').on('change', function() {
                        table.draw();
                    });

                    // Reset filters
                    $('#resetFilter').on('click', function() {
                        $('#roleFilter, #universitasFilter, #statusFilter').val('');
                        table.draw();
                    });

                    // Re-initialize feather icons after initial load
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                });
                </script>
            </div>
        </div>
    </div>
</div>
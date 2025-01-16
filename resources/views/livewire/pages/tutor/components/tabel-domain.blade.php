<main>
    <div class="container-xl px-4" x-data="{ activeTab: 'domain' }">
        <!-- Account page navigation -->
        <nav class="nav nav-borders">
            <a class="nav-link" href="#" :class="{ 'active': activeTab === 'domain' }" @click.prevent="activeTab = 'domain'">Domain</a>
            <a class="nav-link" href="#" :class="{ 'active': activeTab === 'subdomain' }" @click.prevent="activeTab = 'subdomain'">Subdomain</a>
        </nav>
        <hr class="mt-0 mb-4" />

        <div class="row">
            <div x-show="activeTab === 'domain'">
                <div class="row">
                    <div class="col col-end-1 m-3 d-flex flex-row justify-content-end">
                        <a type="button" onclick="$('.card-form-add-domain').removeClass('d-none').addClass('d-block')" class="btn btn-primary"><i data-feather="plus-square" class="me-2"></i>Tambah Domain</a>
                    </div>
                </div>

                <div class="card mb-3 d-none card-form-add-domain">
                    <div class="card-header d-flex flex-row justify-content-between">
                        <h5 class="h5">Tambah Domain</h5>
                        <button class="btn-close" type="button" aria-label="Close" onclick="$('.card-form-add-domain').addClass('d-none').removeClass('d-block')"></button>
                    </div>


                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Deskripsi Domain</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="text" id="input-domain-code" name="code" class="form-control" placeholder="Masukkan kode domain"></td>
                                <td><input type="text" id="input-domain-keterangan" name="keterangan" class="form-control" placeholder="Masukkan deskripsi domain"></td>
                            </tr>
                            </tbody>
                        </table>
                        <input type="hidden" id="input-domain-isactive" name="is_active" value="1" class="form-control">
                        <div class="d-flex flex-row justify-content-end">
                            <button type="submit" id="submit-domain" class="btn btn-primary ">Submit</button>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div wire:ignore>
                            <table id="domainTable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Code</th>
                                    <th>Deskripsi Domain</th>
                                    <th>Status</th>
                                    <th>Jumlah Subdomain</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'subdomain'">
                <div class="row">
                    <div class="col col-end-1 m-3 d-flex flex-row justify-content-end">
                        <a type="button" onclick="$('.card-form-add-subdomain').removeClass('d-none').addClass('d-block')" class="btn btn-primary"><i data-feather="plus-square" class="me-2"></i>Tambah Subdomain</a>
                    </div>
                </div>

                <div class="card mb-3 d-none card-form-add-subdomain">
                    <div class="card-header d-flex flex-row justify-content-between">
                        <h5 class="h5">Tambah Subdomain</h5>
                        <button class="btn-close" type="button" aria-label="Close" onclick="$('.card-form-add-subdomain').addClass('d-none').removeClass('d-block')"></button>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Domain</th>
                                <th>Subdomain Code</th>
                                <th>Deskripsi Domain</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><select id="domain-select" name="domain_code" class="form-select">
                                        @foreach(\App\Models\Domain::get() as $domain)
                                            <option value="{{ $domain->code}}">{{ $domain->keterangan }}</option>
                                        @endforeach
                                    </select></td>
                                <td><input type="text" id="input-subdomain-code" name="code" class="form-control" placeholder="Masukkan kode domain"></td>
                                <td><input type="text" id="input-subdomain-keterangan" name="keterangan" class="form-control" placeholder="Masukkan deskripsi domain"></td>
                            </tr>
                            </tbody>
                        </table>
                        <input type="hidden" id="input-subdomain-isactive" name="is_active" value="1" class="form-control">
                        <div class="d-flex flex-row justify-content-end">
                            <button type="submit" id="submit-subdomain" class="btn btn-primary ">Submit</button>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-body">
                        <div class="col-md-4 mb-3">
                            <label for="domainFilter" class="form-label">Filter Domain</label>
                            <select id="domainFilter" class="form-select">
                                <option value="">Semua Domain</option>
                                @foreach(\App\Models\Domain::get() as $domain)
                                    <option value="{{ $domain->code}}">{{ $domain->keterangan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div wire:ignore>
                            <table id="subdomainTable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Code</th>
                                    <th>Deskripsi Subdomain</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="modalConfirm" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateAvatarModalLabel" >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateAvatarModalLabel"></h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" onclick="modal.hide()"></button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal" onclick="modal.hide()">Batal</button>
                        <button class="btn btn-success" id="btn-submit" type="submit" form="avatar-form">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
        <script>

            function addDomain(){
                axios.post(`{{route('tutor.domain.store')}}`, null, {
                    params : {
                        code : $('#input-domain-code').val(),
                        keterangan : $('#input-domain-keterangan').val(),
                        is_active : $('#input-domain-isactive').val(),
                    }})
                    .then(response => {
                        Swal.fire('Success', response.data.message, 'success')
                        $('#input-domain-code, #input-domain-keterangan').val('')
                        $('.card-form-add-domain').addClass('d-none').removeClass('d-block')
                    })
                    .catch(err => {
                        Swal.fire('Error', err.response?.data, 'error')
                    })
                    .finally(() => {
                        table.draw();
                    })
            }

            function addSubdomain(){
                axios.post(`{{route('tutor.subdomain.store')}}`, null, {
                    params : {
                        domain_code : $('#domain-select').val(),
                        code : $('#input-subdomain-code').val(),
                        keterangan : $('#input-subdomain-keterangan').val(),
                        is_active : $('#input-subdomain-isactive').val(),
                    }})
                    .then(response => {
                        Swal.fire('Success', response.data.message, 'success')
                        $('#input-subdomain-code, #input-subdomain-keterangan').val('')
                        $('.card-form-add-subdomain').addClass('d-none').removeClass('d-block')
                    })
                    .catch(err => {
                        Swal.fire('Error', err.response?.data, 'error')
                    })
                    .finally(() => {
                        subdomainTable.draw();
                    })
            }

            $('#submit-domain').on('click',function (){
                addDomain();
            })

            $('#submit-subdomain').on('click',function (){
                addSubdomain();
            })

            $('#domainFilter').on('change', function(){
                subdomainTable.draw();
            })

            function deleteNewsId(id){
                axios.delete(`{{route('tutor.domain')}}/delete/${id}`, {})
                    .then(response => {
                        Swal.fire('Success', 'Berhasil menghapus domain', 'success')
                        table.draw();
                    })
                    .catch(err => {
                        Swal.fire('Error', err.response?.data, 'error')
                    })
            }

            let table = $('#domainTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                pageLength: 10,
                autoWidth: false,
                searching: true,
                ordering: true,
                ajax: {
                    url: @js(route('tutor.domain.datatable')),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    data: function(d) {
                        d.domainFilter = $('#domainFilter').val();
                        d.subdomainFilter = $('#subdomainFilter').val();
                    }
                },
                language: {
                    search: 'Cari:',
                    searchPlaceholder: 'Cari Materi...',
                    processing: 'Loading...',
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
                columns: [
                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'code' },
                    { data: 'keterangan' },
                    { data: 'status' },
                    { data: 'jumlah_subdomain' },
                    {
                        data: 'actions.delete',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, full, meta){
                            return `${full.jumlah_subdomain > 0 ? '' : `<button class="btn btn-datatable btn-icon btn-transparent-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Materi"  onclick="deleteNewsId(${data})" href="javascript:void(0);">
                                    <i data-feather="trash-2"></i>
                                </button>`}`;
                        }
                    },
                ],
                drawCallback: function() {
                    // Re-initialize feather icons after table draw
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                    // Initialize tooltips
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        //return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                }
            });

            let subdomainTable = $('#subdomainTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                pageLength: 10,
                autoWidth: false,
                searching: true,
                ordering: true,
                ajax: {
                    url: @js(route('tutor.subdomain.datatable')),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    data: function(d) {
                        d.domainFilter = $('#domainFilter').val();
                        d.subdomainFilter = $('#subdomainFilter').val();
                    }
                },
                language: {
                    search: 'Cari:',
                    searchPlaceholder: 'Cari Materi...',
                    processing: 'Loading...',
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
                columns: [
                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'code' },
                    { data: 'keterangan' },
                    { data: 'status' },
                ],
                drawCallback: function() {
                    // Re-initialize feather icons after table draw
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                    // Initialize tooltips
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        //return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                }
            });

            $(document).ready(function(){
                table.draw();
                subdomainTable.draw();
            })
        </script>

    </div>
</main>

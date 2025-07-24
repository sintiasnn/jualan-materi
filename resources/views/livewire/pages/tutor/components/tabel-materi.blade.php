<div class="container-fluid px-4">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col col-end-1 d-flex flex-row justify-content-start">
                    <h1 class="m-0">Tabel Materi</h1>
                </div>
                <div class="col col-end-1 d-flex flex-row justify-content-end">
             </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="domainFilter" class="form-label">Filter Domain</label>
                    <select id="domainFilter" class="form-select">
                        <option value="">Semua Domain</option>
                        @foreach(\App\Models\Domain::get() as $domain)
                            <option value="{{ $domain->code}}">{{ $domain->keterangan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="subdomainFilter" class="form-label">Filter Subdomain</label>
                    <select id="subdomainFilter" class="form-select select2">
                        <option value="">Semua Subdomain</option>
                        @foreach(\App\Models\Subdomain::get() as $subdomain)
                            <option value="{{ $subdomain->id}}">{{ $subdomain->keterangan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button id="resetFilter" class="btn btn-blue">
                        <i data-feather="refresh-ccw" class="me-1"></i>
                    </button>
                </div>
                <div class="col-md-2 mb-3 d-flex flex-column justify-content-end">
                    <a type="button" href="{{route('materi.create')}}" class="btn btn-primary"><i data-feather="plus-square" class="me-2"></i>Buat Materi</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">

                <table id="submateriTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Materi</th>
                        <th>Nama Materi</th>
                        <th>Tingkat Kesulitan</th>
                        <th>Referensi</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
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

        {{--let id = @json($content->id);--}}
        const indexRoute = `{{route('materi.index')}}`
        const updateModal = document.getElementById('updateAvatarModal');
        const deleteMateri = document.getElementById('formHapus')
        const deleteConfirm = document.querySelector('.btn-hapus');
        //const table = document.getElementById('materiTable')
        const indexUrl = `{{route('materi.index')}}`;
        const modalConfirm = 'modalConfirm'


        function deleteNews(id){
            modal
                .setTitle('Konfirmasi Hapus')
                .setBody('Apakah anda yakin ingin menghapus data ini ?')
                .setBtnOk('Hapus')
                .show(function (){
                    axios.delete(`${indexUrl}/${id}`, {})
                        .then(response => {
                            Swal.fire('Success', response.data.message, 'success')
                            modal.hide();
                            table.ajax.reload();
                        })
                        .catch(err => {
                            Swal.fire('Error', err.response?.data, 'error')
                        })
                })
        }

        const modal = {
            title: function(){
                return $(`#modalConfirm .modal-title`)
            },
            body: function(){
                return $(`#modalConfirm .modal-body`)
            },
            btnOk: function(){
                return $(`#modalConfirm .modal-footer #btn-submit`)
            },

            setTitle: function(title){
                this.title().html(title)
                return this;
            },

            setBody: function(body){
                this.body().html(body)
                return this;
            },

            setBtnOk: function(label){
                this.btnOk().html(label)
                return this;
            },

            show: function(callbackOk){
                $(`#modalConfirm`).fadeIn(150)
                    .css('background-color','rgba(0, 0, 0, 0.5)')
                $('#btn-submit').bind('click', function(){
                    if(callbackOk != undefined && callbackOk != null && callbackOk != false){
                        callbackOk();
                    }
                    $(this).unbind();
                })
            },

            hide: function (){
                $('#btn-submit').unbind('click');
                $(`#modalConfirm`).fadeOut(150)
            },
        }

            let table = $('#submateriTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                pageLength: 10,
                autoWidth: false,
                searching: true,
                ordering: true,
                ajax: {
                    url: @js(route('tutor.materi.materiDatatable')),
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
                    { data: 'kode_materi' },
                    { data: 'nama_materi' },
                    { data: 'tingkat_kesulitan' },
                    { data: 'reference' },
                    { data: 'created_at' },
                    {
                        data: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, full, meta){

                        return `<a class="btn btn-datatable btn-icon btn-transparent-dark" href="${data.edit_url}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Materi">
                                            <i data-feather="edit"></i>
                                        </a>
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark" href="${data.view_url}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Materi">
                                            <i data-feather="eye"></i>
                                        </a>
                                        <button class="btn btn-datatable btn-icon btn-transparent-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Materi"  onclick="deleteNews(${full.id})" href="javascript:void(0);">
                                            <i data-feather="trash-2"></i>
                                        </button>`;
                    }
                }
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

        $('#domainFilter, #subdomainFilter').on('change', function() {
            table.draw();
        });

        // Reset filters
        $('#resetFilter').on('click', function() {
            $('#domainFilter, #subdomainFilter').val('');
            table.draw();
        });

        $(document).ready(function(){
            table.draw();
        })
    </script>
</div>

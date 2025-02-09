<x-app-layout>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-3">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="book"></i></div>
                            Pilih Materi ke Paket
                        </h1>
                        <h5 class="page-header-subtitle">
                            {{$namaPaket}}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="row">
        <div class="col">
            <!-- Main page content -->
            <main>
                <div class="container-fluid px-4">

                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="h5">Konten Materi</h5>
                        </div>

                        <div class="card-body">
                            <div class="accordion" id="accordionExample">
                                @forelse($arrayMateri as $materi)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$loop->iteration}}" aria-expanded="false" aria-controls="collapse-{{$loop->iteration}}">
                                                {{$materi->nama_materi}}
                                            </button>
                                        </h2>
                                        <div id="collapse-{{$loop->iteration}}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <ul>
                                                    @foreach($materi->submateri as $submateri)
                                                        <li class="mb-2">{{$submateri->nama_submateri}}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <em>Belum ada materi yang ditambahkan</em>
                                @endforelse

                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="h5">Pilih Materi</h5>
                        </div>

                        <div class="card-body">
                            @if (session()->has('error') || session()->has('error-message'))
                                <div class="alert alert-danger alert-dismissible pe-auto fade show" role="alert">
                                    {{ session('error') ?? session('error-message')}}
                                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="row mb-4">
                                <div class="col-md-3 mb-3">
                                    <label for="domainFilter" class="form-label">Filter Domain</label>
                                    <select id="domainFilter" class="form-select">
                                        <option value="" selected>Semua Domain</option>
                                        @foreach(\App\Models\Domain::all() as $domain)
                                            <option value="{{$domain->id}}">{{$domain->keterangan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="subdomainFilter" class="form-label">Filter Subdomain</label>
                                    <select id="subdomainFilter" class="form-select">
                                        <option value="" selected>Semua Subdomain</option>
                                        @foreach(\App\Models\Subdomain::all() as $subdomain)
                                            <option value="{{$subdomain->id}}">{{$subdomain->keterangan}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <button id="resetFilterMateri" class="btn btn-blue">
                                        <i data-feather="refresh-ccw" class="me-1"></i>
                                        Reset Filter
                                    </button>
                                </div>
                            </div>


                            <form method="POST" action="{{route('tutor.paket.materi.store', $id) }}">
                                @csrf

                                <div class="container mt-3" id="formMateri">
                                </div>

                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                    <a type="button" class="btn btn-outline-danger" href="{{route('tutor.paket.materi')}}">
                                        {{ __('Kembali') }}
                                    </a>

                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>

            <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

            <script>
                const materiUrl = `{{route('tutor.paket.materi')}}`;

                $('#resetFilterMateri').on('click', function (e) {
                    e.preventDefault();
                    $('#domainFilter').val('');
                    $('#subdomainFilter option').remove();
                    let optionDisabled = document.createElement('option')
                    optionDisabled.text = 'Semua Subdomain'
                    optionDisabled.value = ''
                    $('#subdomainFilter').append(optionDisabled);

                    fetchMateri();
                })

                function deleteMateri(id){
                    axios.delete(`${materiUrl}/delete/${id}`, {})
                        .then(response => {
                            Swal.fire('Success', 'Berhasil menghapus materi', 'success')
                            fetchMateri()
                        })
                        .catch(err => {
                            Swal.fire('Error', err.response?.data, 'error')
                        })
                }

                function fetchMateri()
                {
                    axios.post(`${materiUrl}/data`,null, {
                        params : {
                            domain : $('#domainFilter').val(),
                            subdomain : $('#subdomainFilter').val(),
                            paket : @js($id)
                        }
                    })
                        .then(response => {
                            let html = '';
                            const arrMateri = Object.keys(response.data).map((k) => response.data[k])
                            if(arrMateri.length > 0){
                                arrMateri.forEach(el => {
                                    let subHtml = '';
                                    let arrMateri = Object.keys(el.submateri).map((k) => el.submateri[k])
                                    arrMateri.forEach(ell => {
                                        subHtml += `
                                    <div class="col mb-2 d-flex flex-row justify-content-between">
                                    <div>
                                            <label class="form-check-label" for="check-submateri-${el.kode_materi}">
                                                ${ell.nama_submateri}
                                            </label>
                                    </div>

                                    </div>`
                                    })

                                    html += `
                                <input class="form-check-input" type="checkbox" name="materi_id[]" value="${el.id}" id="check-materi-${el.kode_materi}" ${el.is_selected ? 'checked disabled' : ''}/>
                                <label class="form-check-label h4" for="check-materi-${el.kode_materi}">
                                                ${el.nama_materi}
                                            </label>
${el.paket_materi_id ? `<button class="btn btn-outline-danger btn-sm py-1 px-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Materi dari Paket" onclick="deleteMateri(${el.paket_materi_id})" href="#">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>` : ''}
                                        <div class="row row-cols-lg-2 row-cols-md-1 row-cols-sm-1 row-cols-1 mb-5">
                                ${subHtml}
                                </div>`
                                })
                            } else {
                                html += ` <div class="row row-cols-1 mb-5">
                                        <div class="col mb-2">
                                            <em>Belum ada materi pada domain dan subdomain ini</em>
                                        </div>
                                    </div>`
                            }

                            $('#formMateri').html(html);
                        })
                        .catch(err => {
                            Swal.fire('Error', err.response?.data, 'error')
                        })
                }

                function changeDomainSelect(code){
                    const materiIndex = `{{route('materi.index')}}`;
                    axios.get(`${materiIndex}/subdomain/${code}`,{})
                        .then(res =>{
                            $('#subdomainFilter option').remove();
                            let optionDisabled = document.createElement('option')
                            optionDisabled.text = 'Semua Subdomain'
                            optionDisabled.value = ''
                            $('#subdomainFilter').append(optionDisabled);
                            res.data.forEach((val, i) => {
                                let option = document.createElement("option");
                                option.value = val.id;
                                option.text = val.keterangan;
                                $('#subdomainFilter').append(option);
                            })
                        });
                }

                $(document).ready(function() {
                    feather.replace();

                    fetchMateri();
                })

                $('#domainFilter, #subdomainFilter').on('change', function(){
                    fetchMateri();
                })

                $('#domainFilter').on('change', function (){
                    const code = $(this).val();
                    changeDomainSelect(code)
                })

            </script>
        </div>
    </div>

</x-app-layout>

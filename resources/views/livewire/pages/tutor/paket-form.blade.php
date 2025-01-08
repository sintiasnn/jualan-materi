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
                                @foreach($arrayMateri as $materi)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$loop->iteration}}" aria-expanded="false" aria-controls="collapse-{{$loop->iteration}}">
                                                {{$materi['nama_materi']}}
                                            </button>
                                        </h2>
                                        <div id="collapse-{{$loop->iteration}}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <ul>
                                                    @foreach($materi['nama_submateri'] as $submateri)
                                                        <li>{{$submateri}}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">


                            <!-- Filters -->
                            <div class="row mb-4">
                                <div class="col-md-3 mb-3">
                                    <label for="domainFilter" class="form-label">Filter Domain</label>
                                    <select id="domainFilter" class="form-select">
                                        <option value="" selected disabled>Semua Domain</option>
                                        @foreach(\App\Models\Domain::all() as $domain)
                                            <option value="{{$domain->id}}">{{$domain->keterangan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="subdomainFilter" class="form-label">Filter Subdomain</label>
                                    <select id="subdomainFilter" class="form-select">
                                        <option value="">Semua Subdomain</option>
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

                                <div class="container mt-3">
                                    @foreach($allMateri as $materi)
                                        <h5 class="h5 my-2">{{$materi['nama_materi']}}</h5>
                                        <div class="row row-cols-3 mb-5">
                                            @foreach($materi['nama_submateri'] as $key =>$submateri)
                                                <div class="col mb-2">
                                                    <input class="form-check-input" type="checkbox" name="content_id[]" value="{{$key}}" id="flexCheckDefault">
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        {{$submateri}}
                                                    </label>
                                                </div>
                                        @endforeach
                                    </div>
                                    @endforeach
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

                $('#resetFilterMateri').on('click', function () {
                    $('#domainFilter, #subdomainFilter').val('');
                })

                function fetchMateri()
                {
                    axios.post(`${materiUrl}`, {})
                        .then(response => {
                            console.log(response);
                        })
                        .catch(err => {
                            //Swal.fire('Error', err.response?.data, 'error')
                        })
                }

            </script>
        </div>
    </div>

</x-app-layout>

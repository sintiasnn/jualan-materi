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
                            {{\App\Models\PaketList::find($id)->nama_paket}}
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
                                    <button id="resetFilter" class="btn btn-blue">
                                        <i data-feather="refresh-ccw" class="me-1"></i>
                                        Reset Filter
                                    </button>
                                </div>
                            </div>


                            <form method="POST" action="{{route('tutor.paket.materi.store', $id) }}">
                                @csrf

                                <div class="container mt-3">
                                    <div class="row row-cols-3">
                                        @foreach($materis as $materi)
                                            <div class="col">
                                                <div>
                                                    <input class="form-check-input" type="checkbox" name="content_id[]" value="{{$materi->id}}" id="flexCheckDefault">
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        {{$materi->nama_materi}}
                                                    </label>
                                                </div>

                                            </div>
                                        @endforeach
                                    </div>
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


            </script>
        </div>
    </div>

</x-app-layout>

<x-app-layout>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-3">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="book"></i></div>
                            Tampilkan Materi
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>


    <main>
        <div class="container-fluid px-4">
            <div class="card">

                <div class="card-header">
                    <h5 class="">{{ $content->nama_materi }}</h5>
                </div>

                <div class="card-body">

                    <table class="mt-3">
                        <tr>
                            <th>Materi</th>
                            <th> : {{ $content->nama_materi}}</th>
                        </tr>
                        <tr>
                            <th>Domain</th>
                            <th> : {{ $content->subdomain->domain->keterangan}}</th>
                        </tr>
                        <tr>
                            <th>Subdomain</th>
                            <th> : {{ $content->subdomain->keterangan}}</th>
                        </tr>
                        <tr>
                            <th>Waktu & Tanggal</th>
                            <th> : {{ $content->created_at->diffForHumans() }}</th>
                        </tr>
                    </table>
                    <hr>

                    <div style="overflow-wrap: break-word;">
                        {!! $content->deskripsi !!}
                    </div>

                </div>


                <div class="card-footer">
                    <a href="{{route('materi.index')}}" class="btn btn-primary mt-3">Kembali</a>
                </div>

            </div>

        </div>
    </main>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>


</x-app-layout>

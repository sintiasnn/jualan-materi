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
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="h5">{{ __('Detail') }}</h5>
                </div>

                <div class="card-body">
                    <table class="mt-3">
                        <tr>
                            <th class="pe-3">Domain</th>
                            <td> : {{ $content->subdomain->domain->keterangan}}</td>
                        </tr>
                        <tr>
                            <th class="pe-3">Subdomain</th>
                            <td> : {{ $content->subdomain->keterangan}}</td>
                        </tr>
                        <tr>
                            <th class="pe-3">Tingkat Kesulitan</th>
                            <?php
                                $level = '';
                                foreach (range(1, $content->tingkat_kesulitan) as $item){
                                    $level .= '⭐';
                                }
                            ?>
                            <td> : {{$level}}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">

                <div class="card-header">
                    <h4 class="h4">{{ $content->nama_materi }}</h4>
                </div>

                <div class="card-body">
                    <div style="overflow-wrap: break-word;">
                        @foreach($content->submateri as $submateri)
                            {!! $submateri->deskripsi !!}
                        @endforeach
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{route('materi.index')}}" class="btn btn-primary">Kembali</a>
                </div>

            </div>

        </div>
    </main>

</x-app-layout>

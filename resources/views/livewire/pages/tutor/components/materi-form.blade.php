<x-app-layout>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-3">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="book"></i></div>
                            Buat Materi
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>


    <main>
        <div class="container-fluid px-4">
            <div class="card">
                <div class="card-body">
                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{route('materi.store')}}">
                        @csrf
                        <div class="row">

                            <div class="col col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <!-- Domain -->
                                <div class="mb-3">
                                    <label class="small mb-1" for="selectDomain">Domain</label>
                                    <select class="form-control"
                                            {{isset($domains) && isset($content) && $content->viewOnly ? 'readonly disabled' : ''}} id="selectSubdomain"
                                            wire:model="form.domain" required autofocus>
                                        <option disabled selected>{{ __('Pilih Domain') }}</option>
                                        @foreach($domains as $domain)
                                            <option
                                                {{isset($content) && $content->$domain_id ? 'selected' : ''}}  value="{{$domain->id}}">{{$domain->keterangan}}</option>
                                        @endforeach
                                    </select>
                                    @error('form.domain') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                            </div>


                            <div class="col col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <!-- Subdomain -->
                                <div class="mb-3">
                                    <label class="small mb-1" for="selectDomain">Subdomain</label>
                                    <select class="form-control" name="subdomain_id"
                                            {{isset($subdomains) && isset($content) && $content->viewOnly ? 'readonly disabled' : ''}} id="selectSubdomain"
                                            wire:model="form.subdomain" required autofocus>
                                        <option disabled selected>{{ __('Pilih Subdomain') }}</option>
                                        @foreach($subdomains as $subdomainItem)
                                            <option
                                                {{isset($content) && $content->subdomain_id ? 'selected' : ''}}  value="{{$subdomainItem->id}}">{{$subdomainItem->keterangan}}</option>
                                        @endforeach
                                    </select>
                                    @error('form.subdomain') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>


                            <div class="col col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <!-- Bidang -->
                                <div class="mb-3">
                                    <label class="small mb-1" for="selectBidang">Bidang</label>
                                    <select class="form-control" name="bidang_id"
                                            {{isset($content) && $content->viewOnly ? 'readonly disabled' : ''}} id="selectBidang"
                                            wire:model="form.bidang" required autofocus>
                                        <option disabled selected>{{ __('Pilih Bidang') }}</option>
                                        @foreach($bidang as $bidangItem)
                                            <option
                                                {{isset($content) && $content->bidang_id ? 'selected' : ''}} value="{{$bidangItem->id}}">{{$bidangItem->bidang_name}}</option>
                                        @endforeach
                                    </select>
                                    @error('form.bidang') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <!-- Kode Materi -->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputKodeMateri">Kode Materi</label>
                                    <div class="input-group mb-3">
                                        <input name="kode_materi"
                                               type="text"
                                               value="{{isset($content) ? $content->kode_materi : ''}}"
                                               {{isset($content) && $content->viewOnly ? 'readonly disabled' : ''}} class="form-control"
                                               placeholder="Kode Materi" wire:model="form.kodeMateri" required
                                               autofocus>
                                    </div>
                                    @error('form.kodeMateri') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>


                            <div class="col col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <!-- Nama Materi -->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputNamaMateri">Nama Materi</label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="nama_materi"
                                               value="{{isset($content) ? $content->nama_materi : ''}}"
                                               {{isset($content) && $content->viewOnly ? 'readonly disabled' : ''}} class="form-control"
                                               placeholder="Nama Materi" wire:model="form.namaMateri" required
                                               autofocus>
                                    </div>
                                    @error('form.namaMateri') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>


                            <div class="col col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputVideoUrl">URL Video</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1">@</span>
                                        <input type="url" name="video_url"
                                               value="{{isset($content) ? $content->video_url : ''}}"
                                               {{isset($content) && $content->viewOnly ? 'readonly disabled' : ''}} class="form-control"
                                               placeholder="Masukkan URL Video"
                                               aria-describedby="basic-addon1" wire:model="form.videoUrl" required
                                               autofocus>
                                    </div>
                                    @error('form.videoUrl') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="mb-3">
                                    <label for="inputDescription" class="small mb-1">Deskripsi</label>
                                    <textarea class="form-control summernote" name="deskripsi" wire:model="form.deskripsi"
                                              {{isset($content) && $content->viewOnly ? 'readonly disabled' : ''}} required
                                              autofocus id="inputDescription" rows="3" wrap="hard">
                                        {{isset($content) ? $content->deskripsi : ''}}
                                    </textarea>
                                    @error('form.type') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>


                        <!-- Login Button -->
                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            <a type="button" class="btn btn-outline-danger" href="{{route('materi.index')}}">
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
        $(document).ready(function() {
            $("#inputDescription").summernote('code',{
                placeholder: "Hello stand alone ui",
                tabsize: 2,
                height: 120,
                toolbar:[
                    ["style",["style"]],
                    ["font",["bold","underline","clear"]],
                    ["color",["color"]],
                    ["para",["ul","ol","paragraph"]],
                    ["table",["table"]],
                    ["insert",["link","picture","video"]],
                    ["view",["fullscreen","help"]]
                ],
                callbacks: {
                    onPaste: function (e) {
                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                        e.preventDefault();

                        // Firefox fix
                        setTimeout(function () {
                            document.execCommand('insertText', false, bufferText);
                        }, 10);
                    }
                }
            })
        });
    </script>

</x-app-layout>

<?php

use App\Models\Domain;
use App\Models\Materi;
use App\Models\Subdomain;
use App\Models\Submateri;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Component;

new class extends Component {
    public bool $editMode;
    public $domains;
    public $domain;
    public $subdomains;
    public $materi_id;
    public $deleted_submateri_id;
    public $submateriCnt = [];
    public $submateriId = [];
    public Materi $materi;
    public Collection $submateri;
    public bool $openEditMateri;

    public string $subdomain_id;
    public string $kode_materi;
    public string $nama_materi;
    public string $tingkat_kesulitan;

    public function mount($editMode)
    {
        if ($editMode) {
            $materi_id = Route::current()->materi;
            $this->materi_id = $materi_id;

            $this->materi = Materi::find($materi_id);
            $this->submateri = Submateri::where('materi_id', $materi_id)->get();
            $this->submateriId = $this->submateri->map(function ($submateri) {
                return $submateri->toArray()['id'];
            })->toArray();
            $this->openEditMateri = false;
        } else {
            $this->openEditMateri = true;
        }
        $this->submateriCnt = [1];
        $this->editMode = $editMode;
        $this->domain = 1;
        $this->domains = $this->getDomain();
        $this->subdomains = $this->getSubdomain();
    }

    public function getDomain()
    {
        return Domain::all();
    }

    public function getSubdomain()
    {
        $query = Subdomain::query();
        if (!empty($this->domain)) {
            $query->where('domain_code', $this->domain);
        }
        $subdomain_item = $query->get();
        $this->subdomain_id = $subdomain_item[0]->id;
        return $this->subdomains = $subdomain_item;
    }

    public function addSubmateriComponent()
    {
        if($this->editMode){
            $this->submateriId[] = max($this->submateriId) + 1;
            return $this->submateriId;
        } else {
            if ($this->submateriCnt) {
                $this->submateriCnt[] = max($this->submateriCnt) + 1;
            } else {
                $this->submateriCnt[] = 1;
            }
            return $this->submateriCnt;
        }
    }

    public function editMateri()
    {
        $this->openEditMateri = true;
    }

    public function updateMateri()
    {
        $materi = Materi::where('id', $this->materi->id);
        try {
            DB::beginTransaction();
            $materi->update([
                'subdomain_id' => $this->subdomain_id,
                'kode_materi' => $this->kode_materi,
                'nama_materi' => $this->nama_materi,
                'tingkat_kesulitan' => $this->tingkat_kesulitan,
            ]);
            DB::commit();
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data materi berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data materi gagal diperbarui karena ' . $e->getMessage(),
            ]);
        }

        $this->openEditMateri = false;
    }
};
?>

<div class="container-fluid px-4">
    @if (session()->has('error') || session()->has('error-message'))
        <div class="alert alert-danger alert-dismissible pe-auto fade show" role="alert">
            {{ session('error') ?? session('error-message')}}
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form id="materi_form" method="POST"
          action="{{isset($content) && $content->editMode ? route('materi.update',$content->id) : route('materi.store')}}">
        @if(isset($content) && $content->editMode)
            @method('PUT')
        @endif
        @csrf
        <div class="row gx-4">

            <div class="col-lg-3">
                <div class="card card-header-actions mb-4">
                    <div class="card-header">Materi
                        <div class="dropdown no-caret">
                            <button class="btn btn-transparent-dark btn-sm btn-icon dropdown-toggle"
                                    id="dropdownMenuButton" type="button" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-more-vertical">
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="12" cy="5" r="1"></circle>
                                    <circle cx="12" cy="19" r="1"></circle>
                                </svg>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right animated--fade-in-up"
                                 aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item">
                                    <div class="dropdown-item-icon">
                                        <i data-feather="search"></i>
                                    </div>
                                    Tambah referensi materi</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item">
                                    <div class="dropdown-item-icon">
                                        <i data-feather="youtube"></i>
                                    </div>
                                    Tambah video url materi</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <!-- Domain -->
                                <input type="hidden" name="materi_id" value="{{$materi_id ?? ''}}"/>
                                <div class="mb-3">
                                    <label class="small mb-1" for="selectDomain">Domain</label>
                                    <select class="form-control" {{$openEditMateri ? '' : 'disabled'}}
                                            {{isset($domains) ? 'readonly' : ''}}
                                            id="selectDomain"
                                            required
                                    >
                                        <option disabled selected>{{ __('Pilih Domain') }}</option>
                                        @foreach($domains as $domain)
                                            <option {{isset($materi) && $materi->subdomain->domain_code == $domain->code ? 'selected' : ''}}  value="{{$domain->code}}">{{$domain->keterangan}}</option>
                                        @endforeach
                                    </select>
                                    @error('domain') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <!-- Subdomain -->
                                <div class="mb-3">
                                    <label class="small mb-1 form-label" for="selectSubdomain">Subdomain</label>
                                    <select class="form-control" name="subdomain_id" {{$openEditMateri ? '' : 'disabled'}} id="selectSubdomain" required>
                                        <option disabled selected>{{ __('Pilih Subdomain') }}</option>
                                        @foreach($subdomains as $subdomainItem)
                                            <option
                                                {{isset($materi) && $materi->subdomain_id == $subdomainItem->id ? 'selected' : ''}}  value="{{$subdomainItem->id}}">{{$subdomainItem->keterangan}}</option>
                                        @endforeach
                                    </select>
                                    @error('form.subdomain') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-xl-4 col-lg-4 col-md-4 col-sm-4">
                                <!-- Kode Materi -->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputKodeMateri">Kode</label>
                                    <div class="input-group mb-3">
                                        <input name="kode_materi" wire:model.fill="kode_materi"
                                               type="text"
                                               value="{{isset($materi) ? $materi->kode_materi : old('kode_materi')}}"
                                               class="form-control"
                                               placeholder="Kode Materi" autocomplete="off" required>
                                    </div>
                                    @error('form.kodeMateri') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col col-xl-8 col-lg-8 col-md-8 col-sm-8">
                                <!-- Nama Materi -->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputNamaMateri">Nama Materi</label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="nama_materi" wire:model.fill="nama_materi"
                                               value="{{isset($materi) ? $materi->nama_materi : old('nama_materi')}}"
                                               class="form-control"
                                               placeholder="Nama Materi" autocomplete="off" required>
                                    </div>
                                    @error('form.namaMateri') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <!-- Subdomain -->
                                <div class="mb-3">
                                    <label class="small mb-1" for="selectHardness">Tingkat Kesulitan</label>
                                    <select class="form-control" name="tingkat_kesulitan"
                                            wire:model.fill="tingkat_kesulitan"
                                            id="selectHardness"
                                            required>
                                        <option disabled selected>{{ __('Pilih Tingkat Kesulitan') }}</option>
                                        @foreach(range(1, 5) as $item)
                                            <option
                                                {{($editMode && $materi->tingkat_kesulitan == $item) ? 'selected' : ''}}
                                                value="{{$item}}">{{$item . ($item == 1 ? ' - Paling Mudah' : ($item == 5 ? ' - Paling Sulit' : ''))}}</option>
                                        @endforeach
                                    </select>
                                    @error('form.subdomain') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-header-actions">
                    <div class="card-body">
                        <div class="d-grid mb-3">
                            <a type="button" class="btn btn-outline-danger" href="{{route('materi.index')}}">
                                {{ __('Kembali') }}
                            </a>
                        </div>
                        <div class="d-grid">
                            <button type="button" id="materi_submit" class="btn btn-primary">
                                {{ __('Submit') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card card-header-actions mb-4">
                    <div class="card-header">
                        <p class="m-0">Konten Materi</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="app">
                                    <text-editor :editable="true" content-value="{{$editMode && isset($materi->content) ? $materi->content : ''}}" name="materi_content"></text-editor>
                                    {{--<novel-editor></novel-editor>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--<div class="card card-header-actions mb-4">
                    <div class="card-header">
                        <p class="m-0">Test Page Renderer</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <iframe id="Iframe" src="{{asset('storage/notion-html/03 Faringitis 0abb5357e5b1415a87c5e0b75564027b.html')}}"></iframe>
                            </div>
                        </div>
                    </div>
                </div>--}}

                {{--@foreach((isset($editMode) && $editMode ? $submateriId : $submateriCnt) as $val)
                    <livewire:pages.tutor.components.submateri-form :materiId="$materi_id" :editMode="$editMode" :num="$val" wire:key="{{ $val }}"/>
                @endforeach

                <div class="card card-header-actions mb-4">
                    <div class="card-header d-flex flex-row justify-content-end">
                        <div class="dropdown no-caret">
                            <button wire:click="addSubmateriComponent" type="button" class="btn btn-blue btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                                Tambah Submateri
                            </button>
                        </div>
                    </div>
                </div>--}}
            </div>

        </div>
    </form>
</div>

<script>
    $(document).ready(function (){
        $('#materi_submit').on('click', function(){
            swal.fire({
                title: "Konfirmasi",
                text: "Apakah anda yakin submit materi? ",
                icon: "warning",
                showCancelButton: true,
                showConfirmButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Iya'
            }).then((result) => {
                if(result.isConfirmed){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ambil CSRF token dari meta tag
                        }
                    });
                    $.ajax({
                        url: `/tutor/materi`,
                        method: 'POST',
                        data: JSON.stringify({
                            materi_id : $('input[name=materi_id]').val(),
                            subdomain_id : $('#selectSubdomain').val(),
                            kode_materi: $('input[name=kode_materi]').val(),
                            nama_materi: $('input[name=nama_materi]').val(),
                            tingkat_kesulitan: $('select[name=tingkat_kesulitan]').val(),
                            materi_content: $('.tiptap').html()
                        }),
                        contentType: 'application/json',
                        processData: false,
                        success: function (response) {
                            swal.fire({
                                text: response.message,
                                icon: "success",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            }).then((res) => {
                                if(res.isConfirmed){
                                    window.location.href = `{{route('materi.index')}}`
                                }
                            })
                        }
                    })
                }
            })
        });

        $('#selectDomain').on('change', function(){
            let domainCode = $(this).val();
            let getSubdomainUrl = `/tutor/materi/subdomain/${domainCode}`;
            axios.get(getSubdomainUrl, null, {
                params : {

                }})
                .then(response => {
                    let option_domain = '';
                    $.each(response.data, function(key, value){
                        option_domain += `<option value="${value.id}">${value.keterangan}</option>`;
                    });
                    $('#selectSubdomain').find('option').remove().end().append(option_domain);
                })
                .catch(err => {
                    Swal.fire('Error', err.response?.data, 'error')
                })
                .finally(() => {
                })
        });
    });


</script>


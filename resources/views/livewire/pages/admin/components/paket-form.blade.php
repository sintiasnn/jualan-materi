<?php

use App\Models\PaketList;
use Livewire\Volt\Component;

new class extends Component {
    public bool $editMode;
    public PaketList $paketList;

    public function mount($editMode)
    {
        $this->editMode = $editMode;
        if($editMode){
            $id = request()->route('id');
            $this->paketList = PaketList::find($id);
        }
        $this->domain = 1;
    }
};
?>

<main>
    <div class="container-fluid px-4">
        @if (session()->has('error') || session()->has('error-message'))
            <div class="alert alert-danger alert-dismissible pe-auto fade show" role="alert">
                {{ session('error') ?? session('error-message')}}
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form method="POST"
              enctype="multipart/form-data"
              action="{{isset($editMode) && $editMode ? route('materi.update',$paketList->id) : route('admin.paket.store')}}">
            @if(isset($editMode) && $editMode)
                @method('PUT')
            @endif
            @csrf
            <div class="row gx-4">
                <div class="col-lg-8">
                    <div class="card card-header-actions mb-4">
                        <div class="card-header">Detail Paket</div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <!-- Nama Paket -->
                                    <div class="mb-3">
                                        <label class="small mb-1">Nama Paket</label>
                                        <div class="input-group mb-3">
                                            <input name="nama_paket" type="text" class="form-control"
                                                   value="{{isset($paketList) ? $paketList->nama_paket : old('nama_paket')}}"
                                                   placeholder="Nama Paket" required>
                                        </div>
                                        @error('form.kodeMateri') <span
                                            class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <!-- Audience -->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="selectAudience">Audience</label>
                                        <select class="form-control" name="audience"
                                                {{ isset($content) && $content->viewOnly ? 'readonly disabled' : ''}} id="selectAudience"
                                                required>
                                            <option disabled selected>{{ __('Pilih Audience') }}</option>
                                            <option {{isset($paketList) && $paketList->audience == 'ukmppd' ? 'selected' : ''}} value="ukmppd">{{ __('UKMPPD') }}</option>
                                            <option {{isset($paketList) && $paketList->audience == 'aipki' ? 'selected' : ''}} value="aipki">{{ __('AIPKI') }}</option>
                                            <option {{isset($paketList) && $paketList->audience == 'osce' ? 'selected' : ''}} value="osce">{{ __('OSCE') }}</option>
                                        </select>
                                        @error('form.subdomain') <span
                                            class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <!-- Tipe -->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="selectTipe">Tipe</label>
                                        <select class="form-control" name="tipe"
                                                {{ isset($content) && $content->viewOnly ? 'readonly disabled' : ''}} id="selectHardness"
                                                required>
                                            <option disabled selected>{{ __('Pilih Tipe') }}</option>
                                            <option {{isset($paketList) && $paketList->tipe == 'tryout' ? 'selected' : ''}} value="tryout">{{ __('Tryout') }}</option>
                                            <option {{isset($paketList) && $paketList->tipe == 'materi' ? 'selected' : ''}} value="materi">{{ __('Materi') }}</option>
                                        </select>
                                        @error('form.subdomain')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="input-deskripsi-paket" class="small mb-1">Deskripsi</label>
                                        <textarea class="form-control summernote" name="deskripsi" required id="input-deskripsi-paket" rows="3" wrap="hard">{{isset($paketList) ? $paketList->deskripsi : old('deskripsi')}}</textarea>
                                        @error('form.type') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="card card-header-actions mb-4">
                        <div class="card-header"> Harga</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <!-- Harga-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputHarga">Harga</label>
                                        <div class="input-group mb-3">
                                            <input type="number" min="0" name="harga" class="form-control"
                                                   value="{{isset($paketList) ? $paketList->harga : old('harga')}}"
                                                   placeholder="Harga" required>
                                        </div>
                                        @error('form.kodeSubmateri') <span
                                            class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <!-- Discount -->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputDiscount">Diskon</label>
                                        <div class="input-group mb-3">
                                            <input type="number" min="0" name="discount" class="form-control"
                                                   value="{{isset($paketList) ? $paketList->discount : old('discount')}}"
                                                   placeholder="Diskon" required>
                                        </div>
                                        @error('form.namaSubmateri') <span
                                            class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <!-- Audience -->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="selectTier">Tier</label>
                                        <select class="form-control" name="tier"
                                                {{ isset($content) && $content->viewOnly ? 'readonly disabled' : ''}} id="selectTier"
                                                required>
                                            <option {{isset($paketList) && $paketList->tier == 'free' ? 'selected' : ''}}  value="free">{{ __('Gratis') }}</option>
                                            <option {{isset($paketList) && $paketList->tipe == 'paid' ? 'selected' : ''}} value="paid">{{ __('Berbayar') }}</option>
                                        </select>
                                        @error('form.subdomain') <span
                                            class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">Foto Paket</div>
                        <div class="card-body text-center">
                            <img id="paket-image" class="img-account-profile rounded-circle mb-2"
                                 src="{{ asset('assets/img/avatar/default.jpg') }}"
                                 style="width: 150px; height: 150px; overflow: hidden; position: relative; justify-content: center; align-items: center;"/>

                            <div class="small font-italic text-muted mb-4">JPG atau PNG tidak lebih dari 1 MB</div>
                            <input type="file" name="input-paket-image" id="input-paket-image" accept="image/*" class="form-control mb-3"/>
                        </div>
                    </div>

                    <div class="card card-header-actions">
                        <div class="card-header">
                            Publish
                            <i class="text-muted" data-feather="info" data-bs-toggle="tooltip"
                               data-bs-placement="left"
                               title="After submitting, your post will be published once it is approved by a moderator."></i>
                        </div>
                        <div class="card-body">
                            <div class="d-grid mb-3">
                                <a type="button" class="btn btn-outline-danger" href="{{route('admin.paket')}}">
                                    {{ __('Kembali') }}
                                </a>
                            </div>
                            @if(!$editMode)
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

@script
<script>
    $('#input-paket-image').on('change', function(){
        console.log($(this).val());
    })

</script>
@endscript

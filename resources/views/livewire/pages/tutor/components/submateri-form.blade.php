<?php

use App\Models\Submateri;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Component;

new class extends Component {
    public bool $editMode;
    public $num;
    public $materiId;
    public Submateri $submateri;
    public bool $openEditSubmateri;
    public bool $createOnEdit;

    public string $kode_submateri;
    public string $nama_submateri;
    public string $deskripsi;


    public function mount($num, $editMode, $materiId = null)
    {
        $this->materiId = $materiId;
        $this->createOnEdit = !(Submateri::where('id', $num)->exists());
        if ($editMode && !$this->createOnEdit) {
            $this->submateri = Submateri::find($num);
            $this->openEditSubmateri = false;
        } else {
            $this->openEditSubmateri = true;
        }

        $this->num = $num;
        $this->editMode = $editMode;
    }

    public function editSubmateri()
    {
        $this->openEditSubmateri = true;
        $this->dispatch('editDeskripsi.{num}');
    }

    public function createSubmateri()
    {
        $submateri = new Submateri();
        try {
            DB::beginTransaction();
            $submateri->create([
                'materi_id' => $this->materiId,
                'kode_submateri' => $this->kode_submateri,
                'nama_submateri' => $this->nama_submateri,
                'deskripsi' => $this->deskripsi,
            ]);
            DB::commit();
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data submateri berhasil ditambahkan'
            ]);
            redirect()->route('materi.edit', $this->materiId);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data submateri gagal ditambahkan karena ' . $e->getMessage(),
            ]);
        }
    }

    public function updateSubmateri()
    {
        $submateri = Submateri::where('id', $this->submateri->id);
        try {
            DB::beginTransaction();
            $submateri->update([
                'kode_submateri' => $this->kode_submateri,
                'nama_submateri' => $this->nama_submateri,
                'deskripsi' => $this->deskripsi,
            ]);
            DB::commit();
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data submateri berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data submateri gagal diperbarui karena ' . $e->getMessage(),
            ]);
        }
        $this->openEditSubmateri = false;
        $this->dispatch('updateDeskripsi.{num}');
    }

    public function deleteSubmateri()
    {
        DB::beginTransaction();
        try {
            Submateri::find($this->num)->delete();
            DB::commit();
            redirect()->route('materi.edit', $this->materiId);
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Submateri berhasil dihapus'
            ]);
        } catch (\Exception $exception){
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Gagal!',
                'text' => 'Submateri gagal dihapus karena ' . $exception->getMessage(),
            ]);
        }
    }

};
?>

<div class="card card-header-actions mb-4">
    <div class="card-header">
        Submateri {{$num}}
        <div class="dropdown no-caret">
            <button class="btn btn-transparent-dark btn-sm btn-icon dropdown-toggle" id="dropdownMenuButton"
                    type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-more-vertical">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="12" cy="5" r="1"></circle>
                    <circle cx="12" cy="19" r="1"></circle>
                </svg>
            </button>
            <div class="dropdown-menu dropdown-menu-right animated--fade-in-up" aria-labelledby="dropdownMenuButton">
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
                    Tambah video url materi
                </a>
            </div>
            @if($editMode)
                @if(!$openEditSubmateri)

                    <button wire:click="editSubmateri" class="btn btn-warning btn-icon mr-2 btn-sm" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-edit">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </button>

                    @if(!$createOnEdit)
                        <button class="btn btn-danger btn-icon mr-2 btn-sm" data-bs-toggle="modal"
                                data-bs-target="#toggleDeleteSubmateri-{{$num}}"
                                data-bs-placement="top"
                                type="button" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-trash-2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path
                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                        </button>
                    @else

                    @endif

                @else
                    @if($createOnEdit)
                        <button class="btn btn-success btn-icon mr-2 btn-sm" wire:click="createSubmateri"
                                onclick="@this.set('deskripsi', $('#inputDescription-{{$num}}').val(), true)"
                                type="button" data-toggle="tooltip" data-placement="top" title="Simpan Submateri Baru">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-save">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                        </button>

                        <button wire:click="$parent.removeSubmateri({{ $num }})"
                                class="btn btn-danger btn-icon mr-2 btn-sm"
                                type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-trash-2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path
                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                        </button>
                    @else
                        <button class="btn btn-blue mr-2 btn-sm" wire:click="updateSubmateri"
                                onclick="@this.set('deskripsi', $('#inputDescription-{{$num}}').val(), true)"
                                type="button">Update
                        </button>

                    @endif
                @endif

            @else
                <button wire:click="$parent.removeSubmateri({{ $num }})" class="btn btn-danger btn-icon mr-2 btn-sm"
                        type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-trash-2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                </button>

            @endif

        </div>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="col col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <!-- Kode Submateri -->
                <div class="mb-3">
                    <label class="small mb-1" for="inputKodeSubmateri">Kode Submateri</label>
                    <div class="input-group mb-3">
                        <input type="text" name="kode_submateri[{{$num}}]" class="form-control"
                               wire:model.fill="kode_submateri"
                               {{$openEditSubmateri ? '' : 'disabled'}}
                               value="{{isset($submateri) ? $submateri->kode_submateri : old('kode_submateri')}}"
                               placeholder="Kode Submateri" required>
                    </div>
                    @error('form.kodeSubmateri') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="col col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <!-- Nama Submateri -->
                <div class="mb-3">
                    <label class="small mb-1" for="inputNamaSubmateri">Nama Submateri</label>
                    <div class="input-group mb-3">
                        <input type="text" name="nama_submateri[{{$num}}]" class="form-control"
                               wire:model.fill="nama_submateri"
                               {{$openEditSubmateri ? '' : 'disabled'}}
                               value="{{isset($submateri) ? $submateri->nama_submateri : old('nama_submateri')}}"
                               placeholder="Nama Submateri" required>
                    </div>
                    @error('form.namaSubmateri') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div wire:ignore>
                    <div class="mb-3">
                        <label for="inputDescription-{{$num}}" class="small mb-1">Konten</label>
                        <textarea wire:model.fill="deskripsi" class="form-control summernote" name="deskripsi[{{$num}}]"
                                  required {{$openEditSubmateri ? '' : 'disabled'}} id="inputDescription-{{$num}}"
                                  rows="3"
                                  wrap="hard"> {{isset($submateri) ? $submateri->deskripsi : old('deskripsi')}}</textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if($editMode && !$createOnEdit)
        <div class="modal fade" id="toggleDeleteSubmateri-{{$num}}" data-bs-backdrop="static" tabindex="-1"
             role="dialog" aria-labelledby="terminateSessionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="terminateSessionModalLabel">Konfirmasi Hapus Submateri</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin akan menghapus submateri {{$submateri->nama_submateri}} ini ?
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="button" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-danger" type="button" wire:click="deleteSubmateri({{$num}})"
                                data-bs-dismiss="modal">Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>


@script
<script>

    let html = @json(isset($submateri) ? $submateri->deskripsi : '');
    let editMode = @json($editMode);
    let createOnEdit = @json($createOnEdit);
    summernote();

    function summernote() {
        $("#inputDescription-{{$num}}").summernote('code', {
            tabsize: 2,
            height: 120,
            toolbar: [
                ["style", ["style"]],
                ["font", ["bold", "underline", "clear"]],
                ["color", ["color"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["table", ["table"]],
                ["insert", ["link", "picture", "video"]],
                ["view", ["fullscreen", "help"]]
            ],
            callbacks: {
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();

                    // Firefox fix
                    setTimeout(function () {
                        document.execCommand('insertText', false, bufferText);
                    }, 10);
                },
                onChange: function (contents, $editable) {

                }
            }
        });
        $('#inputDescription-{{$num}}').summernote('code', html);
        $('#inputDescription-{{$num}}').summernote(`${editMode && !createOnEdit ? 'disable' : 'enable'}`);
    }

    Livewire.on('editDeskripsi.{num}', () => {
        $('#inputDescription-{{$num}}').summernote('enable');
    })

    Livewire.on('updateDeskripsi.{num}', () => {
        $('#inputDescription-{{$num}}').summernote('disable');
    })

</script>
@endscript

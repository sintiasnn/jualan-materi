<?php

use App\Models\RefUniversitasList;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    // Form inputs
    public $universitas_name = '';
    public $singkatan = '';
    public $deskripsi = '';

    // Validation states
    public bool $isUniversitasNameValid = false;
    public bool $isSingkatanValid = false;
    public ?string $universitasNameError = null;
    public ?string $singkatanError = null;

    protected function rules()
    {
        return [
            'universitas_name' => ['required', 'string', 'max:255', 'unique:'.RefUniversitasList::class.',universitas_name'],
            'singkatan' => ['nullable', 'string', 'max:20', 'unique:'.RefUniversitasList::class.',singkatan'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function updatedUniversitasName()
    {
        $this->validateUniversitasName();
    }

    public function updatedSingkatan()
    {
        $this->validateSingkatan();
    }

    private function validateUniversitasName()
    {
        $this->universitasNameError = null;
        
        if (strlen($this->universitas_name) === 0) {
            $this->isUniversitasNameValid = false;
            return;
        }

        if (strlen($this->universitas_name) > 255) {
            $this->isUniversitasNameValid = false;
            $this->universitasNameError = 'Nama universitas tidak boleh lebih dari 255 karakter';
            return;
        }

        if (RefUniversitasList::where('universitas_name', $this->universitas_name)->exists()) {
            $this->isUniversitasNameValid = false;
            $this->universitasNameError = 'Nama universitas sudah digunakan';
            return;
        }

        $this->isUniversitasNameValid = true;
    }

    private function validateSingkatan()
    {
        $this->singkatanError = null;

        if (empty($this->singkatan)) {
            $this->isSingkatanValid = true;
            return;
        }
        
        if (strlen($this->singkatan) > 20) {
            $this->isSingkatanValid = false;
            $this->singkatanError = 'Singkatan tidak boleh lebih dari 20 karakter';
            return;
        }

        if (RefUniversitasList::where('singkatan', $this->singkatan)->exists()) {
            $this->isSingkatanValid = false;
            $this->singkatanError = 'Singkatan sudah digunakan';
            return;
        }

        $this->isSingkatanValid = true;
    }

    public function save()
    {
        $validated = $this->validate();
        
        try {
            RefUniversitasList::create($validated);

            session()->flash('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Universitas baru berhasil ditambahkan!'
            ]);

            return redirect()->route('admin.universitas');
            
        } catch (\Exception $e) {
            $this->dispatch('error', 'Terjadi kesalahan saat menambahkan universitas.');
        }
    }

    public function render(): mixed
    {
        return view('livewire.pages.admin.components.universitas-form');
    }
}; ?>

<div>
    <div class="container-xl px-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Detail Universitas</span>
                </div>
            </div>
            <div class="card-body">
                <form wire:submit="save">
                    <!-- University Name -->
                    <div class="mb-3">
                        <label class="small mb-1" for="universitas_name">Nama Universitas</label>
                        <input wire:model.live.debounce.500ms="universitas_name" 
                            class="form-control @if($universitas_name) {{ $isUniversitasNameValid ? 'is-valid' : 'is-invalid' }} @endif" 
                            type="text" 
                            placeholder="Masukkan nama universitas" />
                        @if($universitasNameError)
                            <div class="small text-danger">{{ $universitasNameError }}</div>
                        @endif
                        @error('universitas_name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <!-- Abbreviation -->
                    <div class="mb-3">
                        <label class="small mb-1" for="singkatan">Singkatan</label>
                        <input wire:model.live.debounce.500ms="singkatan" 
                            class="form-control @if($singkatan) {{ $isSingkatanValid ? 'is-valid' : 'is-invalid' }} @endif" 
                            type="text" 
                            placeholder="Masukkan singkatan (opsional)" />
                        @if($singkatanError)
                            <div class="small text-danger">{{ $singkatanError }}</div>
                        @endif
                        @error('singkatan') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="small mb-1" for="deskripsi">Deskripsi</label>
                        <textarea wire:model="deskripsi" 
                            class="form-control" 
                            rows="3"
                            placeholder="Masukkan deskripsi (opsional)"></textarea>
                        @error('deskripsi') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex mt-4">
                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#confirmModal">
                            Tambah Universitas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div class="modal fade" id="confirmModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Tambah Universitas</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Apakah anda yakin ingin menambahkan universitas baru?</div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success" type="button" wire:click="save" data-bs-dismiss="modal">Tambah</button>
                </div>
            </div>
        </div>
    </div>
</div>
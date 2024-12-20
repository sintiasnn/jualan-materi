<?php

use App\Models\RefUniversitasList;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    // Form inputs
    public $universitasId;
    public $universitas_name = '';
    public $singkatan = '';
    public $deskripsi = '';
    
    // Validation states
    public bool $isUniversitasNameValid = false;
    public bool $isSingkatanValid = false;
    public ?string $universitasNameError = null;
    public ?string $singkatanError = null;
    public bool $nameModified = false;
    public bool $singkatanModified = false;

    public function with(): array 
    {
        return [
            'universitasId' => request()->route('universitasId'),
        ];
    }

    public function mount(): void
    {
        $universitasId = request()->route('universitasId');
        $universitas = RefUniversitasList::findOrFail($universitasId);
        $this->universitasId = $universitasId;
        $this->universitas_name = $universitas->universitas_name;
        $this->singkatan = $universitas->singkatan;
        $this->deskripsi = $universitas->deskripsi;
        
        // Initialize validation states
        $this->validateUniversitasName();
        $this->validateSingkatan();
    }

    public function updatedUniversitasName()
    {
        $this->nameModified = true;
        $this->validateUniversitasName();
    }

    public function updatedSingkatan()
    {
        $this->singkatanModified = true;
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

        $exists = RefUniversitasList::where('universitas_name', $this->universitas_name)
            ->where('id', '!=', $this->universitasId)
            ->exists();
            
        if ($exists) {
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

        $exists = RefUniversitasList::where('singkatan', $this->singkatan)
            ->where('id', '!=', $this->universitasId)
            ->exists();

        if ($exists) {
            $this->isSingkatanValid = false;
            $this->singkatanError = 'Singkatan sudah digunakan';
            return;
        }

        $this->isSingkatanValid = true;
    }

    public function updateUniversityInformation(): void
    {
        $validated = $this->validate([
            'universitas_name' => [
                'required', 
                'string', 
                'max:255',
                'unique:ref_universitas_list,universitas_name,'.$this->universitasId
            ],
            'singkatan' => [
                'nullable', 
                'string', 
                'max:20',
                'unique:ref_universitas_list,singkatan,'.$this->universitasId
            ],
            'deskripsi' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $universitas = RefUniversitasList::findOrFail($this->universitasId);
            $universitas->update($validated);

            $this->dispatch('swal:modal', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data universitas berhasil diperbarui.',
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'title' => 'Error!',
                'text' => 'Terjadi kesalahan saat memperbarui data universitas.',
            ]);
        }
    }

    public function render(): mixed
    {
        return view('livewire.pages.admin.components.universitas-detail');
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
                <form wire:submit.prevent="updateUniversityInformation">
                    <!-- University Name -->
                    <div class="mb-3">
                        <label class="small mb-1" for="universitas_name">Nama Universitas</label>
                        <input wire:model.live="universitas_name" 
                            class="form-control {{ $nameModified ? ($isUniversitasNameValid ? 'is-valid' : 'is-invalid') : '' }}" 
                            type="text" 
                            placeholder="Masukkan nama universitas" />
                        @if($universitasNameError)
                            <div class="small text-danger">{{ $universitasNameError }}</div>
                        @endif
                        <x-input-error :messages="$errors->get('universitas_name')" class="mt-2 text-danger" />
                    </div>

                    <!-- Abbreviation -->
                    <div class="mb-3">
                        <label class="small mb-1" for="singkatan">Singkatan</label>
                        <input wire:model.live="singkatan" 
                            class="form-control {{ $singkatanModified ? ($isSingkatanValid ? 'is-valid' : 'is-invalid') : '' }}" 
                            type="text" 
                            placeholder="Masukkan singkatan (opsional)" />
                        @if($singkatanError)
                            <div class="small text-danger">{{ $singkatanError }}</div>
                        @endif
                        <x-input-error :messages="$errors->get('singkatan')" class="mt-2 text-danger" />
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="small mb-1" for="deskripsi">Deskripsi</label>
                        <textarea wire:model="deskripsi" 
                            class="form-control" 
                            rows="3"
                            placeholder="Masukkan deskripsi (opsional)"></textarea>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2 text-danger" />
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex mt-4">
                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#updateUniversityModal">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Update Confirmation Modal -->
    <div class="modal fade" id="updateUniversityModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateUniversityModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateUniversityModalLabel">Konfirmasi Perubahan</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Apakah anda yakin ingin menyimpan perubahan data universitas?</div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success" type="button" wire:click="updateUniversityInformation" data-bs-dismiss="modal">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    @if(session()->has('swal:modal'))
        @script
        <script>
            const swalData = @json(session('swal:modal'));
            Swal.fire({
                icon: swalData.type,
                title: swalData.title,
                text: swalData.text,
            });
        </script>
        @endscript
    @endif

    @script
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const updateModal = document.getElementById('updateUniversityModal');
            
            // Listen for Bootstrap modal events
            updateModal.addEventListener('shown.bs.modal', function() {
                updateModal.querySelector('.btn-success').focus();
            });

            // Listen for Livewire events
            window.addEventListener('swal:modal', event => {
                const modal = bootstrap.Modal.getInstance(updateModal);
                if (modal) {
                    modal.hide();
                    // Remove modal backdrop if still present
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                    document.body.classList.remove('modal-open');
                }
            });
        });
    </script>
    @endscript
</div>
<?php

use App\Models\User;
use App\Models\RefUniversitasList;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $username = '';
    public int $universitas_id; 
    public string $universitas_name = ''; 
    public string $phone_number = ''; 
    public $universitas_list;

    // Validation state properties
    public bool $isNameValid = false;
    public bool $isPhoneValid = false;
    public ?string $phoneError = null;
    
    // Track whether fields have been modified
    public bool $nameModified = false;
    public bool $phoneModified = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->phone_number = $user->phone_number;
        if (!str_starts_with($this->phone_number, '+62')) {
            $this->phone_number = '+62' . $this->phone_number;
        }
        $this->universitas_list = RefUniversitasList::all();
        $this->universitas_id = $user->universitas_id;
        $this->universitas_name = $user->universitas->universitas_name;
    }

    public function updatedName()
    {
        $this->nameModified = true;
        $this->validateName();
    }

    public function updatedPhoneNumber()
    {
        $this->phoneModified = true;
        $this->validatePhoneNumber();
    }

    protected function validateName()
    {
        $this->isNameValid = strlen($this->name) <= 19 && strlen($this->name) > 0;
    }

    protected function validatePhoneNumber()
    {
        $this->phoneError = null;
        
        // Ensure +62 prefix
        if (!str_starts_with($this->phone_number, '+62')) {
            $this->phone_number = '+62' . substr($this->phone_number, 3);
        }

        // Validate length
        if (strlen($this->phone_number) > 20) {
            $this->isPhoneValid = false;
            $this->phoneError = 'Nomor telepon terlalu panjang';
            return;
        }

        if (strlen($this->phone_number) < 5) {
            $this->isPhoneValid = false;
            return;
        }

        // Check uniqueness excluding current user
        $existingUser = User::where('phone_number', $this->phone_number)
            ->where('id', '!=', Auth::id())
            ->first();
            
        if ($existingUser) {
            $this->isPhoneValid = false;
            $this->phoneError = 'Nomor telepon sudah digunakan';
            return;
        }

        $this->isPhoneValid = true;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:19'],
            'universitas_id' => ['required', 'exists:ref_universitas_list,id'],
            'phone_number' => [
                'required', 
                'string', 
                'max:20',
                'regex:/^\+62\d+$/',
                Rule::unique('users', 'phone_number')->ignore($user->id)
            ],
        ]);

        $user->fill($validated);
        $user->save();

        $universitas_singkatan = $user->universitas->singkatan ?? 'Unknown';

        $this->dispatch('profile-updated', name: $user->name, universitas_singkatan: $universitas_singkatan);

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Profil berhasil diperbarui.',
        ]);
    }
}; ?>

<div class="container-xl px-4">
    <div class="row">
        <!-- Profile picture card-->
        <div class="col-xl-4">
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Foto Profil</div>
                <div class="card-body text-center">
                    <img id="avatar-image" class="img-account-profile rounded-circle mb-2" 
                        src="{{ asset('assets/img/avatar/' . auth()->user()->avatar) }}" 
                        alt="{{ auth()->user()->name }}" 
                        style="width: 150px; height: 150px; overflow: hidden; position: relative; justify-content: center; align-items: center;" />
                    
                    <div class="small font-italic text-muted mb-4">JPG atau PNG tidak lebih dari 1 MB</div>
                    <form method="POST" enctype="multipart/form-data" action="{{ route('profile.updateAvatar') }}">
                        @csrf
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="form-control mb-3" />
                        <button type="submit" class="btn btn-primary">Upload foto baru</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">Detail Akun</div>
                <div class="card-body">
                    <form wire:submit.prevent="updateProfileInformation">
                        <!-- Name -->
                        <div class="mb-3">
                            <label class="small mb-1" for="name">Nama</label>
                            <input 
                                wire:model.live="name" 
                                class="form-control {{ $nameModified ? ($isNameValid ? 'is-valid' : 'is-invalid') : '' }}"
                                id="name" 
                                type="text" 
                                maxlength="19"
                                placeholder="Masukkan nama anda" 
                            />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                        </div>

                        <!-- Username and Phone -->
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="username">Username</label>
                                <input 
                                    disabled 
                                    class="form-control" 
                                    id="username" 
                                    type="text" 
                                    wire:model="username" 
                                />
                            </div>
                            
                            <div class="col-md-6">
                                <label class="small mb-1" for="phone_number">No Telpon</label>
                                <input 
                                    wire:model.live="phone_number" 
                                    class="form-control {{ $phoneModified ? ($isPhoneValid ? 'is-valid' : 'is-invalid') : '' }}"
                                    id="phone_number" 
                                    type="tel" 
                                    maxlength="20"
                                    placeholder="+62"
                                />
                                @if($phoneModified && $phoneError)
                                    <div class="small text-danger">{{ $phoneError }}</div>
                                @endif
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2 text-danger" />
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="small mb-1" for="email">Email</label>
                            <input 
                                disabled 
                                class="form-control" 
                                id="email" 
                                type="email" 
                                wire:model="email" 
                            />
                        </div>

                        <!-- University -->
                        <div class="mb-3">
                            <label class="small mb-1" for="universitas">Universitas</label>
                            <select 
                                class="form-control" 
                                id="universitas" 
                                wire:model="universitas_id">
                                <option value="" disabled>Pilih Universitas</option>
                                @foreach($universitas_list as $universitas)
                                    <option value="{{ $universitas->id }}">
                                        {{ $universitas->universitas_name." (".$universitas->singkatan.")" }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('universitas_id')" class="mt-2 text-danger" />
                        </div>

                        <!-- Submit Button -->
                        <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('swal:modal'))
    <script>
        const swalData = @json(session('swal:modal'));
        Swal.fire({
            icon: swalData.type,
            title: swalData.title,
            text: swalData.text,
        });
    </script>
@endif
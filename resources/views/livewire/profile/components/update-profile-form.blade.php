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
    // Properties remain the same
    public string $name = '';
    public string $email = '';
    public string $username = '';
    public int $universitas_id;
    public string $universitas_name = '';
    public string $phone_number = '';
    public string $joined_date = '';
    public $universitas_list;
    public bool $isNameValid = false;
    public bool $isPhoneValid = false;
    public ?string $phoneError = null;
    public bool $nameModified = false;
    public bool $phoneModified = false;
    public string $role = '';

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
        $this->joined_date = $user->created_at->format('d F Y');
        $this->role = $user->role;
    }

    // Validation methods remain the same
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
        
        if (!str_starts_with($this->phone_number, '+62')) {
            $this->phone_number = '+62' . substr($this->phone_number, 3);
        }

        if (strlen($this->phone_number) > 20) {
            $this->isPhoneValid = false;
            $this->phoneError = 'Nomor telepon terlalu panjang';
            return;
        }

        if (strlen($this->phone_number) < 5) {
            $this->isPhoneValid = false;
            return;
        }

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

    // Main update method that matches the wire:submit
    public function save(): void
    {
        $user = Auth::user();

        $validationRules = [
            'name' => ['required', 'string', 'max:19'],
            'phone_number' => [
                'required', 
                'string', 
                'max:20',
                'regex:/^\+62\d+$/',
                Rule::unique('users', 'phone_number')->ignore($user->id)
            ],
        ];

        // Only add universitas_id validation for regular users
        if ($this->role !== 'admin' && $this->role !== 'tutor') {
            $validationRules['universitas_id'] = ['required', 'exists:ref_universitas_list,id'];
        }

        $validated = $this->validate($validationRules);

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
}; 
?>

<div class="container-xl px-4">
    <div class="row">
        <!-- Profile picture card -->
        <div class="col-xl-4">
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Foto Profil</div>
                <div class="card-body text-center">
                    <img id="avatar-image" class="img-account-profile rounded-circle mb-2" 
                        src="{{ asset('assets/img/avatar/' . auth()->user()->avatar) }}" 
                        alt="{{ auth()->user()->name }}" 
                        style="width: 150px; height: 150px; overflow: hidden; position: relative; justify-content: center; align-items: center;" />
                    
                    <div class="small font-italic text-muted mb-4">JPG atau PNG tidak lebih dari 1 MB</div>
                    <form id="avatar-form" method="POST" enctype="multipart/form-data" action="{{ route('profile.updateAvatar') }}">
                        @csrf
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="form-control mb-3" />
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateAvatarModal">
                            Upload foto baru
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span>Detail Akun</span>
                        @if($role === 'admin')
                            <span class="badge bg-danger ms-2">Administrator</span>
                        @elseif($role === 'tutor')
                            <span class="badge bg-purple ms-2">Tutor</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <form id="profile-form" wire:submit="save">
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

                        <!-- Email and Joined Date -->
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="email">Email</label>
                                <input 
                                    disabled 
                                    class="form-control" 
                                    id="email" 
                                    type="email" 
                                    wire:model="email" 
                                />
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="joined_date">Tanggal Bergabung</label>
                                <input 
                                    disabled 
                                    class="form-control bg-light" 
                                    id="joined_date" 
                                    type="text" 
                                    wire:model="joined_date"
                                />
                            </div>
                        </div>

                        <!-- University -->
                        @if($role !== 'admin' && $role !== 'tutor')
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
                        @endif

                        <!-- Submit Button -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateProfileModal">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Update Confirmation Modal -->
    <div class="modal fade" id="updateProfileModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProfileModalLabel">Konfirmasi Perubahan</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin ingin menyimpan perubahan pada profil?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success" type="button" wire:click="save" data-bs-dismiss="modal">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Avatar Update Confirmation Modal -->
    <div class="modal fade" id="updateAvatarModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateAvatarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateAvatarModalLabel">Konfirmasi Upload Foto</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin ingin mengupload foto profil baru?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success" type="submit" form="avatar-form">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const updateAvatarModal = document.getElementById('updateAvatarModal');
        const avatarForm = document.getElementById('avatar-form');
        const avatarInput = document.getElementById('avatar');
        const avatarButton = document.querySelector('[data-bs-target="#updateAvatarModal"]');
        const avatarImage = document.getElementById('avatar-image');

        // Handle avatar input change
        avatarInput.addEventListener('change', function() {
            avatarButton.disabled = this.files.length === 0;
        });

        avatarButton.disabled = avatarInput.files.length === 0;

        // Handle avatar form submission
        avatarForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response;
            })
            .then(() => {
                // Hide modal
                const modal = bootstrap.Modal.getInstance(updateAvatarModal);
                modal.hide();

                // Remove backdrop
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                document.body.classList.remove('modal-open');

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Foto profil berhasil diperbarui.'
                });

                // Force image refresh without affecting URL
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.message !== 'NetworkError when attempting to fetch resource.') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat mengupload foto.'
                    });
                }
            });
        });
    });
    </script>
</div>
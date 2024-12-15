<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\RefUniversitasList;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $phone_number = '+62';
    public string $username = '';
    public string $universitas_id = '';

    // Validation state properties
    public bool $isNameValid = false;
    public bool $isUsernameValid = false;
    public bool $isPhoneValid = false;
    public bool $isEmailValid = false;
    public ?string $usernameError = null;
    public ?string $emailError = null;

    public function mount()
    {
        $this->phone_number = '+62';
    }

    public function updatedName()
    {
        $this->isNameValid = strlen($this->name) <= 19 && strlen($this->name) > 0;
    }

    public function updatedUsername()
    {
        $this->validateUsername();
    }

    public function updatedEmail()
    {
        $this->validateEmail();
    }

    public function validateUsername()
    {
        $this->usernameError = null;
        
        if (strlen($this->username) > 13) {
            $this->isUsernameValid = false;
            $this->usernameError = 'Username tidak boleh lebih dari 13 karakter';
            return;
        }

        if (strlen($this->username) === 0) {
            $this->isUsernameValid = false;
            return;
        }

        // Check uniqueness
        if (User::where('username', $this->username)->exists()) {
            $this->isUsernameValid = false;
            $this->usernameError = 'Username sudah digunakan';
            return;
        }

        $this->isUsernameValid = true;
    }

    public function validateEmail()
    {
        $this->emailError = null;
        
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->isEmailValid = false;
            $this->emailError = 'Format email tidak valid';
            return;
        }

        if (strlen($this->email) === 0) {
            $this->isEmailValid = false;
            return;
        }

        // Check uniqueness
        if (User::where('email', strtolower($this->email))->exists()) {
            $this->isEmailValid = false;
            $this->emailError = 'Email sudah digunakan';
            return;
        }

        $this->isEmailValid = true;
    }

    public function updatedPhoneNumber()
    {
        // Ensure +62 prefix remains
        if (!str_starts_with($this->phone_number, '+62')) {
            $this->phone_number = '+62' . substr($this->phone_number, 3);
        }
        $this->isPhoneValid = strlen($this->phone_number) <= 15 && strlen($this->phone_number) > 4;
    }

    public function generateReferralCode(): string
    {
        do {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:19'],
            'username' => ['required', 'string', 'max:13', 'unique:'.User::class],
            'phone_number' => ['required', 'string', 'max:15', 'min:10', 'regex:/^\+62\d+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'universitas_id' => ['required', 'exists:ref_universitas_list,id'],
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $validated['referral_code'] = $this->generateReferralCode();
        
        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(RouteServiceProvider::HOME, navigate: true);
    }
}
?>

<div>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header justify-content-center">
                                    <h3 class="fw-light my-4">Buat Akun LMSAxon</h3>
                                </div>
                                <div class="card-body">
                                    <form wire:submit.prevent="register">
                                        <!-- Full Name -->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="name">Nama</label>
                                            <input 
                                                wire:model.live="name" 
                                                class="form-control {{ $isNameValid ? 'is-valid' : ($name ? 'is-invalid' : '') }}" 
                                                id="name" 
                                                type="text" 
                                                maxlength="19"
                                                placeholder="Masukkan nama anda" 
                                                required 
                                            />
                                            {{-- <div class="small text-muted">Maksimal 19 karakter</div> --}}
                                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                                        </div>

                                        <!-- Username -->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="username">Username</label>
                                            <input 
                                                wire:model.live.debounce.500ms="username" 
                                                class="form-control {{ $isUsernameValid ? 'is-valid' : ($username ? 'is-invalid' : '') }}" 
                                                id="username" 
                                                type="text" 
                                                maxlength="13"
                                                placeholder="Masukkan username" 
                                                required 
                                            />
                                            {{-- <div class="small text-muted">Maksimal 13 karakter</div> --}}
                                            @if($usernameError)
                                                <div class="small text-danger mt-2">{{ $usernameError }}</div>
                                            @endif
                                            <x-input-error :messages="$errors->get('username')" class="mt-2 text-danger" />
                                        </div>

                                        <!-- Email -->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="email">Email</label>
                                            <input 
                                                wire:model.live.debounce.500ms="email" 
                                                class="form-control {{ $isEmailValid ? 'is-valid' : ($email ? 'is-invalid' : '') }}" 
                                                id="email" 
                                                type="email" 
                                                maxlength="255"
                                                placeholder="Masukkan email" 
                                                required 
                                            />
                                            @if($emailError)
                                                <div class="small text-danger mt-2">{{ $emailError }}</div>
                                            @endif
                                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                                        </div>

                                        <!-- Phone Number -->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="phone_number">No Telp</label>
                                            <input 
                                                wire:model.live="phone_number" 
                                                class="form-control {{ $isPhoneValid ? 'is-valid' : ($phone_number != '+62' ? 'is-invalid' : '') }}" 
                                                id="phone_number" 
                                                type="tel" 
                                                maxlength="15"
                                                placeholder="+62"
                                                required 
                                            />
                                            {{-- <div class="small text-muted">Format: +62 diikuti nomor telepon</div> --}}
                                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2 text-danger" />
                                        </div>

                                        <!-- University -->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="universitas_id">Universitas</label>
                                            <select wire:model="universitas_id" class="form-control" id="universitas_id" required>
                                                <option value="">Pilih Universitas</option>
                                                @foreach(App\Models\RefUniversitasList::all() as $universitas)
                                                    <option value="{{ $universitas->id }}">{{ $universitas->universitas_name." (".$universitas->singkatan.")" }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('universitas_id')" class="mt-2 text-danger" />
                                        </div>

                                        <!-- Password Row -->
                                        <div class="row gx-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="small mb-1" for="password">Password</label>
                                                    <input 
                                                        wire:model="password" 
                                                        class="form-control" 
                                                        id="password" 
                                                        type="password" 
                                                        placeholder="Masukkan password" 
                                                        required 
                                                    />
                                                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="small mb-1" for="password_confirmation">Konfirmasi Password</label>
                                                    <input 
                                                        wire:model="password_confirmation" 
                                                        class="form-control" 
                                                        id="password_confirmation" 
                                                        type="password" 
                                                        placeholder="Masukkan kembali password" 
                                                        required 
                                                    />
                                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small text-decoration-none" href="{{ route('login') }}">Sudah punya akun? Login disini!</a>
                                            <button class="btn btn-primary" type="submit">Buat Akun</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <div id="layoutAuthentication_footer">
            <footer class="footer-admin mt-auto footer-dark">
                <div class="container-xl px-4">
                    <div class="row">
                        <div class="col-md-6 small">Copyright &copy; LMSAxon 2024</div>
                        <div class="col-md-6 text-md-end small">
                            <a href="#!">Privacy Policy</a>
                            &middot;
                            <a href="#!">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>
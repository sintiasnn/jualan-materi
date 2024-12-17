<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\RefUniversitasList;

new #[Layout('layouts.guest')] class extends Component
{
    // Form inputs
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $phone_number = '+62';
    public string $username = '';
    public string $universitas_id = '';
    public ?string $g_recaptcha_response = null;

    // Validation states
    public bool $isNameValid = false;
    public bool $isUsernameValid = false;
    public bool $isPhoneValid = false;
    public bool $isEmailValid = false;
    public ?string $usernameError = null;
    public ?string $emailError = null;
    public ?string $phoneError = null;

    public function mount(): void
    {
        $this->phone_number = '+62';
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:19'],
            'username' => ['required', 'string', 'max:13', 'unique:'.User::class],
            'phone_number' => ['required', 'string', 'regex:/^\+62[0-9]{8,12}$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'universitas_id' => ['required', 'exists:ref_universitas_list,id'],
            'g_recaptcha_response' => ['required']
        ];
    }

    protected function messages(): array
    {
        return [
            'g_recaptcha_response.required' => 'Mohon centang reCAPTCHA terlebih dahulu.'
        ];
    }

    public function updatedName(): void
    {
        $this->isNameValid = strlen($this->name) <= 19 && strlen($this->name) > 0;
    }

    public function updatedUsername(): void
    {
        $this->validateUsername();
    }

    public function updatedEmail(): void
    {
        $this->validateEmail();
    }

    public function updatedPhoneNumber(): void
    {
        $this->validatePhoneNumber();
    }

    private function validateUsername(): void
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

        if (User::where('username', $this->username)->exists()) {
            $this->isUsernameValid = false;
            $this->usernameError = 'Username sudah digunakan';
            return;
        }

        $this->isUsernameValid = true;
    }

    private function validateEmail(): void
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

        if (User::where('email', strtolower($this->email))->exists()) {
            $this->isEmailValid = false;
            $this->emailError = 'Email sudah digunakan';
            return;
        }

        $this->isEmailValid = true;
    }

    private function validatePhoneNumber(): void
    {
        $this->phoneError = null;
        
        $phone = preg_replace('/[^0-9+]/', '', $this->phone_number);
        
        if (!str_starts_with($phone, '+62')) {
            if (str_starts_with($phone, '0')) {
                $phone = '+62' . substr($phone, 1);
            } elseif (str_starts_with($phone, '62')) {
                $phone = '+' . $phone;
            } else {
                $phone = '+62' . $phone;
            }
        }

        $this->phone_number = $phone;
        
        $digits = substr($phone, 3);
        if (strlen($digits) < 8) {
            $this->isPhoneValid = false;
            $this->phoneError = 'Nomor telepon terlalu pendek';
            return;
        }
        
        if (strlen($digits) > 12) {
            $this->isPhoneValid = false;
            $this->phoneError = 'Nomor telepon terlalu panjang';
            return;
        }

        $this->isPhoneValid = true;
    }

    private function generateReferralCode(): string
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

    private function validateRecaptcha(): bool
    {
        if (empty($this->g_recaptcha_response)) {
            return false;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $this->g_recaptcha_response,
        ]);

        return $response->json('success', false);
    }

    public function setRecaptcha($token): void
    {
        $this->g_recaptcha_response = $token;
    }

    public function register(): void
    {
        try {
            // First validate form inputs
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:19'],
                'username' => ['required', 'string', 'max:13', 'unique:'.User::class],
                'phone_number' => ['required', 'string', 'regex:/^\+62[0-9]{8,12}$/'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
                'universitas_id' => ['required', 'exists:ref_universitas_list,id'],
                'g_recaptcha_response' => ['required', function ($attribute, $value, $fail) {
                    if (empty($value)) {
                        $fail('Mohon centang reCAPTCHA terlebih dahulu.');
                        return;
                    }

                    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret' => config('services.recaptcha.secret_key'),
                        'response' => $value,
                    ]);

                    if (!$response->json('success')) {
                        $fail('Verifikasi reCAPTCHA gagal. Silakan coba lagi.');
                    }
                }],
            ]);

            // Format email to lowercase
            $validated['email'] = strtolower($validated['email']);

            // Create user data array
            $userData = [
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'password' => Hash::make($validated['password']),
                'universitas_id' => $validated['universitas_id'],
                'referral_code' => $this->generateReferralCode(),
            ];

            // Create the user
            $user = User::create($userData);

            // Fire registered event
            event(new Registered($user));

            // Log the user in
            Auth::login($user);

            // Show success message
            session()->flash('alert', [
                'type' => 'success',
                'title' => 'Registrasi Berhasil!',
                'message' => "Selamat datang di Axon Education, {$validated['name']}!"
            ]);

            // Redirect to user dashboard since we know this is a new user registration
            $this->redirect(route('user.dashboard'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('registrationFailed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('registrationFailed');
            $this->addError('registration', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
        }
    }
}?>

<div>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header d-flex align-items-center">
                                    <!-- Image beside the text -->
                                    <img src="{{asset('assets/img/favicon.png')}}" alt="LMSAxon Logo" class="img-fluid me-2" style="width: 40px; height: 40px; border-radius:10px">
                                    
                                    <!-- LMSAxon Text -->
                                    <h3 class="fw-light my-0">Axon Education - Buat Akun</h3>
                                </div>
                                <div class="card-body">
                                    <script>
                                        // Define global function for reCAPTCHA
                                        window.setRecaptchaResponse = function(token) {
                                            @this.set('g_recaptcha_response', token);
                                        }
                                    </script>

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

                                        <!-- reCAPTCHA -->
                                        <div class="mb-3">
                                            <div wire:ignore>
                                                <div class="g-recaptcha" 
                                                     data-sitekey="{{ config('services.recaptcha.site_key') }}"
                                                     data-callback="setRecaptchaResponse">
                                                </div>
                                            </div>
                                            <x-input-error :messages="$errors->get('g_recaptcha_response')" class="mt-2 text-danger" />
                                        </div>
                                        
                                    

                                        @error('general')
                                            <div class="alert alert-danger mb-3">{{ $message }}</div>
                                        @enderror

                                        <!-- Submit Button -->
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button class="btn btn-primary" type="submit">Buat Akun</button>
                                            <a class="small text-decoration-none" href="{{ route('login') }}">Sudah punya akun? Login disini!</a>
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

    @push('scripts')
    <script>
        // Handle registration failed event
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('registrationFailed', () => {
                grecaptcha.reset();
                @this.set('g_recaptcha_response', null);
            });
        });
    </script>
    @endpush
</div>
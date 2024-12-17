<?php

use App\Livewire\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\User;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        // Validate the login form
        $this->form->validate();

        try {
            // Attempt to authenticate
            $this->form->authenticate();
            
            // Regenerate session for security
            Session::regenerate();

            $user = Auth::user();
            $name = $user->name ?? 'User';
            
            // Prepare redirect based on user role
            $redirectRoute = match ($user->role) {
                User::ROLE_ADMIN => route('admin.dashboard'),
                User::ROLE_TUTOR => route('tutor.dashboard'),
                User::ROLE_USER => route('user.dashboard'),
                default => route('dashboard')
            };

            session()->flash('alert', [
                'type' => 'success',
                'title' => 'Login Berhasil!',
                'message' => "Selamat datang kembali, {$name}!"
            ]);

            $this->redirect($redirectRoute);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->addError('form.email', trans('auth.failed'));
            
            session()->flash('alert', [
                'type' => 'error',
                'title' => 'Login Gagal!',
                'message' => 'Email atau password yang anda masukkan salah.'
            ]);

        } catch (\Exception $e) {
            session()->flash('alert', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Terjadi kesalahan. Silahkan coba beberapa saat lagi.'
            ]);
            
            $this->redirect(route('login'));
        }
    }
}
?>

<div>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <!-- Basic login form-->
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header d-flex align-items-center">
                                    <!-- Image beside the text -->
                                    <img src="{{asset('assets/img/favicon.png')}}" alt="LMSAxon Logo" class="img-fluid me-2" style="width: 40px; height: 40px; border-radius:10px">
                                    
                                    <!-- LMSAxon Text -->
                                    <h3 class="fw-light my-0">Axon Education - Login</h3>
                                </div>
                                <div class="card-body">
                                    <!-- Session Status -->
                                    <x-auth-session-status class="mb-4" :status="session('status')" />

                                    <!-- Login Form -->
                                    <form wire:submit.prevent="login">
                                        <!-- Email Address -->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="email">Email</label>
                                            <input wire:model="form.email" id="email" class="form-control" type="email" name="email" required autofocus autocomplete="username" placeholder="Masukkan email"/>
                                            <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-danger" />
                                        </div>

                                        <!-- Password -->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="password">Password</label>
                                            <input wire:model="form.password" id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password"/>
                                            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-danger" />
                                        </div>

                                        <!-- Remember Me -->
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input wire:model="form.remember" class="form-check-input" id="rememberPasswordCheck" type="checkbox" />
                                                <label class="form-check-label" for="rememberPasswordCheck">Ingat saya</label>
                                            </div>
                                        </div>

                                        <!-- Login Button -->
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="{{ route('password.request') }}">Lupa Password?</a>
                                            {{-- <x-primary-button class="ms-3">
                                                {{ __('Log in') }}
                                            </x-primary-button> --}}
                                            <button type="submit" class="btn btn-primary ms-3">
                                                {{ __('Log in') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Footer -->
                                <div class="card-footer text-center">
                                    <div class="small">
                                        <a href="{{ route('register') }}">Belum punya akun? Buat disini!</a>
                                    </div>
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
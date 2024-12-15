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
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        try {
            $role = Auth::user()->role;

            if ($role === User::ROLE_ADMIN) {
                $this->redirect(route('admin.dashboard'));
            } elseif ($role === User::ROLE_TUTOR) {
                $this->redirect(route('tutor.dashboard'));
            } elseif ($role === User::ROLE_USER) {
                $this->redirect(route('user.dashboard'));
            } else {
                $this->redirect(route('dashboard'));
            }
        } catch (\Exception $e) {
            // Fallback to default dashboard if something goes wrong
            $this->redirect(route('dashboard'));
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
                                <div class="card-header justify-content-center">
                                    <h3 class="fw-light my-4">LMSAxon</h3>
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
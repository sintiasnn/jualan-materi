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

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->username = Auth::user()->username;
        $this->phone_number = Auth::user()->phone_number;
        $this->universitas_list = RefUniversitasList::all();
        $this->universitas_id = Auth::user()->universitas_id;
        $this->universitas_name = Auth::user()->universitas->universitas_name;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */


    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone_number' => ['required', 'string', 'max:20', 'unique:users,phone_number,' . $user->id],
            'universitas_id' => ['required', 'exists:ref_universitas_list,id'], 
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        $universitas_singkatan = $user->universitas->singkatan ?? 'Unknown';


        $this->dispatch('profile-updated', name: $user->name, universitas_singkatan: $universitas_singkatan);

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Profil berhasil diperbarui.',
        ]);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: RouteServiceProvider::HOME);

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<x-app-layout>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-3">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="settings"></i></div>
                            Pengaturan Akun
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content -->
    <div class="container-xl px-4" x-data="{ activeTab: 'profile' }">
        <!-- Account page navigation -->
        <nav class="nav nav-borders">
            <a class="nav-link" href="#" :class="{ 'active': activeTab === 'profile' }" @click.prevent="activeTab = 'profile'">Profil</a>
            <a class="nav-link" href="#" :class="{ 'active': activeTab === 'password' }" @click.prevent="activeTab = 'password'">Password</a>
        </nav>
        <hr class="mt-0 mb-4" />

        <div class="row">
            <div x-show="activeTab === 'profile'">
                <livewire:profile.components.update-profile-form />
            </div>

            <div class="col-lg-8" x-show="activeTab === 'password'">
                <!-- Password Card -->
            <livewire:profile.components.update-password-form />
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
</x-app-layout>



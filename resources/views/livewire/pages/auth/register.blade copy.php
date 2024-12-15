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
    public string $phone_number = '';
    public string $username = '';
    public string $universitas_id = '';

    public function generateReferralCode(): string
    {
        do {
            // Generate a random 6-character referral code using uppercase letters and numbers
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while (User::where('referral_code', $code)->exists()); // Ensure the code is unique

        return $code;
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:13'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'universitas_id' => ['required', 'exists:ref_universitas_list,id'],
        ]);
        
        $validated['password'] = Hash::make($validated['password']);

        // Generate a unique 6-character referral code
        $validated['referral_code'] = $this->generateReferralCode();
        
        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(RouteServiceProvider::HOME, navigate: true);
    }

    
}; 
?>

<div>
    <form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

         <!-- Username -->
         <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input wire:model="username" id="username" class="block mt-1 w-full" type="text" name="username" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone_number" :value="__('Phone Number')" />
            <x-text-input wire:model="phone_number" id="phone_number" class="block mt-1 w-full" type="number" name="phone_number" required autofocus autocomplete="phone_number" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <!-- University -->
        <div class="mb-4">
            <label for="universitas_id" class="block text-sm font-medium text-gray-700">Universitas</label>
            <select wire:model="universitas_id" id="universitas_id" name="universitas_id" required class="block w-full mt-1">
                <option value="">Select Universitas</option>
                @foreach(App\Models\RefUniversitasList::all() as $universitas)
                    <option value="{{ $universitas->id }}">{{ $universitas->universitas_name." (".$universitas->singkatan.")" }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('universitas_id')" class="mt-2" />
        </div>


        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>

    </form>
</div>

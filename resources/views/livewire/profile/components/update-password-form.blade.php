<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Validation state properties
    public bool $isCurrentPasswordValid = false;
    public bool $isNewPasswordValid = false;
    public bool $isConfirmationValid = false;
    
    // Track field modifications
    public bool $currentPasswordModified = false;
    public bool $newPasswordModified = false;
    public bool $confirmationModified = false;

    // Error messages
    public ?string $currentPasswordError = null;
    public ?string $newPasswordError = null;

    public function updatedCurrentPassword()
    {
        $this->currentPasswordModified = true;
        $this->validateCurrentPassword();
    }

    public function updatedPassword()
    {
        $this->newPasswordModified = true;
        $this->validateNewPassword();
    }

    public function updatedPasswordConfirmation()
    {
        $this->confirmationModified = true;
        $this->validateConfirmation();
    }

    protected function validateCurrentPassword()
    {
        $this->currentPasswordError = null;
        
        if (strlen($this->current_password) === 0) {
            $this->isCurrentPasswordValid = false;
            return;
        }

        // Check if current password is correct
        if (!Hash::check($this->current_password, Auth::user()->password)) {
            $this->isCurrentPasswordValid = false;
            $this->currentPasswordError = 'Password saat ini tidak sesuai';
            return;
        }

        $this->isCurrentPasswordValid = true;
    }

    protected function validateNewPassword()
    {
        $this->newPasswordError = null;
        
        if (strlen($this->password) === 0) {
            $this->isNewPasswordValid = false;
            return;
        }

        // Check password requirements
        if (strlen($this->password) < 8) {
            $this->isNewPasswordValid = false;
            $this->newPasswordError = 'Password minimal 8 karakter';
            return;
        }

        // Validate password confirmation if it's been entered
        if ($this->confirmationModified) {
            $this->validateConfirmation();
        }

        $this->isNewPasswordValid = true;
    }

    protected function validateConfirmation()
    {
        if ($this->password !== $this->password_confirmation) {
            $this->isConfirmationValid = false;
            return;
        }

        $this->isConfirmationValid = true;
    }

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        $this->currentPasswordModified = false;
        $this->newPasswordModified = false;
        $this->confirmationModified = false;

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Password berhasil diperbarui.',
        ]);
    }
}; ?>

<section>
    <div class="card mb-4">
        <div class="card-header">Ubah Password</div>
        <div class="card-body">
            <form id="password-form" wire:submit.prevent="updatePassword" class="space-y-4">
                <!-- Current Password -->
                <div class="mb-3">
                    <x-input-label for="update_password_current_password" :value="__('Password Sekarang')" class="small mb-1" />
                    <x-text-input 
                        wire:model.live="current_password" 
                        id="update_password_current_password" 
                        name="current_password" 
                        type="password" 
                        class="form-control {{ $currentPasswordModified ? ($isCurrentPasswordValid ? 'is-valid' : 'is-invalid') : '' }}" 
                        placeholder="Masukkan password sekarang" 
                        autocomplete="current-password" 
                    />
                    @if($currentPasswordModified && $currentPasswordError)
                        <div class="small text-danger">{{ $currentPasswordError }}</div>
                    @endif
                    <x-input-error :messages="$errors->get('current_password')" class="mt-2 text-danger" />
                </div>

                <!-- New Password -->
                <div class="mb-3">
                    <x-input-label for="update_password_password" :value="__('Password Baru')" class="small mb-1" />
                    <x-text-input 
                        wire:model.live="password" 
                        id="update_password_password" 
                        name="password" 
                        type="password" 
                        class="form-control {{ $newPasswordModified ? ($isNewPasswordValid ? 'is-valid' : 'is-invalid') : '' }}" 
                        placeholder="Masukkan password baru" 
                        autocomplete="new-password" 
                    />
                    @if($newPasswordModified && $newPasswordError)
                        <div class="small text-danger">{{ $newPasswordError }}</div>
                    @endif
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password Baru')" class="small mb-1" />
                    <x-text-input 
                        wire:model.live="password_confirmation" 
                        id="update_password_password_confirmation" 
                        name="password_confirmation" 
                        type="password" 
                        class="form-control {{ $confirmationModified ? ($isConfirmationValid ? 'is-valid' : 'is-invalid') : '' }}" 
                        placeholder="Masukkan kembali password baru" 
                        autocomplete="new-password" 
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                </div>

                <div class="flex items-center gap-4">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updatePasswordModal">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Update Confirmation Modal -->
    <div class="modal fade" id="updatePasswordModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePasswordModalLabel">Konfirmasi Perubahan Password</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin ingin mengubah password?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success" type="button" wire:click="updatePassword" data-bs-dismiss="modal">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</section>
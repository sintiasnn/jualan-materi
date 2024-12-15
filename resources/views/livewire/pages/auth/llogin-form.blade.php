<div class="card-body">
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="login">
        <!-- Email Address -->
        <div class="mb-3">
            <label class="small mb-1" for="inputEmailAddress">Email</label>
            <input class="form-control" id="inputEmailAddress" type="email" placeholder="Enter email address"
                   wire:model="form.email" required autofocus autocomplete="username"/>
            @error('form.email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label class="small mb-1" for="inputPassword">Password</label>
            <input class="form-control" id="inputPassword" type="password" placeholder="Enter password"
                   wire:model="form.password" required autocomplete="current-password"/>
            @error('form.password') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" id="rememberPasswordCheck" type="checkbox" wire:model="form.remember">
                <label class="form-check-label" for="rememberPasswordCheck">Remember password</label>
            </div>
        </div>

        <!-- Login Button -->
        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
            @if (Route::has('password.request'))
                <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
            @endif
            <button type="submit" class="btn btn-primary">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</div>

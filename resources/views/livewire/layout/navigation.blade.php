<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    public function logoutUser()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return $this->redirect('/login', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
    <!-- Sidenav Toggle Button-->
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i data-feather="menu"></i></button>
    <!-- Navbar Brand-->
    <!-- * * Tip * * You can use text or an image for your navbar brand.-->
    <!-- * * * * * * When using an image, we recommend the SVG format.-->
    <!-- * * * * * * Dimensions: Maximum height: 32px, maximum width: 240px-->
    <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="index.html">{{ config('app.name', 'Your App Name') }}</a>
    
    <!-- Navbar Items-->
    <ul class="navbar-nav align-items-center ms-auto">
        
        <!-- User Dropdown-->
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="img-fluid" style="width: 45px; height: 45px; overflow: hidden; position: relative; justify-content: center; align-items: center;" src="{{ asset('assets/img/avatar/' . auth()->user()->avatar) }}" 
                    alt="{{ auth()->user()->name }}" /></a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src="{{ asset('assets/img/avatar/' . auth()->user()->avatar) }}" 
                    alt="{{ auth()->user()->name }}" />
                    <div class="dropdown-user-details">
                        {{-- <div class="dropdown-user-details-name">{{ auth()->user()->name }}</div> --}}
                        <div class="dropdown-user-details-name">
                        <div x-data="{ name: '{{ auth()->user()->name }}' }" 
                            x-text="name" 
                            x-on:profile-updated.window="name = $event.detail.name">
                       </div>
                    </div>
                    <div>
                        @if(auth()->user()->role === 'user')
                        <div class="dropdown-user-details-email mt-1">
                            <div x-data="{ singkatan: '{{ auth()->user()->universitas->singkatan ?? 'Unknown' }}' }" 
                                x-text="singkatan" 
                                x-on:profile-updated.window="singkatan = $event.detail.universitas_singkatan">
                            </div>
                        </div>
                        @endif
                        @if(auth()->user()->role === 'admin')
                        <div class="dropdown-user-details-email mt-1">
                            <div>Administrator</div>
                        </div>
                        @endif
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('profile') }}">
                    <div class="dropdown-item-icon">
                        <i data-feather="settings"></i>
                    </div>
                    {{ __('Pengaturan Akun') }}
                </a>
                @if(auth()->user()->role === 'user')
                <a class="dropdown-item" href="{{ route('user.transaksi') }}">
                    <div class="dropdown-item-icon">
                        <i data-feather="shopping-bag"></i>
                    </div>
                    {{ __('Transaksi Saya') }}
                </a>
                @endif
                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <div class="dropdown-item-icon">
                        <i data-feather="log-out"></i>
                    </div>
                    {{ __('Logout') }}
                </button>          
            </div>
        </li>
    </ul>
</nav>
<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah anda yakin ingin keluar dari aplikasi?
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-danger" type="button" onclick="handleLogout()">Logout</button>
            </div>
        </div>
    </div>
</div>


<script>
    function handleLogout() {
        // Close modal first
        const modal = bootstrap.Modal.getInstance(document.getElementById('logoutModal'));
        modal.hide();
        
        // Then call Livewire's logout
        @this.logoutUser();
    }
</script>



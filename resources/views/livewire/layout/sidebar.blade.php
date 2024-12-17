
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sidenav shadow-right sidenav-light">
            <div class="sidenav-menu">
                <div class="nav accordion" id="accordionSidenav">
                    <!-- Sidenav Menu Heading (Account)-->
                    <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                    <div class="sidenav-menu-heading d-sm-none">Account</div>
                    <!-- Sidenav Link (Alerts)-->
                    <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                    <a class="nav-link d-sm-none" href="#!">
                        <div class="nav-link-icon"><i data-feather="bell"></i></div>
                        Alerts
                        <span class="badge bg-warning-soft text-warning ms-auto">4 New!</span>
                    </a>
                    <!-- Sidenav Link (Messages)-->
                    <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                    <a class="nav-link d-sm-none" href="#!">
                        <div class="nav-link-icon"><i data-feather="mail"></i></div>
                        Messages
                        <span class="badge bg-success-soft text-success ms-auto">2 New!</span>
                    </a>
                    <!-- Sidenav Menu Heading (Core)-->
                    <div class="sidenav-menu-heading">Dashboard</div>
                    <!-- Sidenav Accordion (Dashboard)-->
                    <a class="nav-link {{ request()->is(auth()->user()->role.'/dashboard*') ? 'active' : '' }}" 
                        href="{{ '/'.auth()->user()->role.'/dashboard' }}">
                         <div class="nav-link-icon"><i data-feather="home"></i></div>
                         Halaman Utama
                     </a>
                    @if(auth()->user()->role === 'admin')
                    <div class="sidenav-menu-heading">Paket</div>
                    <a class="nav-link {{ request()->is('user/beli*') ? 'active' : '' }}" 
                        href="{{ '/user/beli' }}">
                        <div class="nav-link-icon"><i data-feather="package"></i></div>
                        Daftar Paket
                    </a>
                    <a class="nav-link {{ request()->is('user/transaksi*') ? 'active' : '' }}" 
                        href="{{ '/user/transaksi' }}">
                        <div class="nav-link-icon"><i data-feather="shopping-bag"></i></div>
                        Daftar Transaksi
                    </a>
                    <a class="nav-link {{ request()->is('user/transaksi*') ? 'active' : '' }}" 
                        href="{{ '/user/transaksi' }}">
                        <div class="nav-link-icon"><i data-feather="dollar-sign"></i></div>
                        Kode Promo
                    </a>
                    <div class="sidenav-menu-heading">App</div>
                    <a class="nav-link {{ request()->is('user/beli*') ? 'active' : '' }}" 
                        href="{{ '/user/beli' }}">
                        <div class="nav-link-icon"><i data-feather="user"></i></div>
                        User Management
                    </a>
                    <a class="nav-link {{ request()->is('user/beli*') ? 'active' : '' }}" 
                        href="{{ '/user/beli' }}">
                        <div class="nav-link-icon"><i data-feather="settings"></i></div>
                        App Configuration
                    </a>
                    {{-- <a class="nav-link {{ request()->is('user/transaksi*') ? 'active' : '' }}" 
                        href="{{ '/user/transaksi' }}">
                        <div class="nav-link-icon"><i data-feather="shopping-bag"></i></div>
                        Daftar Transaksi
                    </a> --}}
                    @endif
                    
                    @if(auth()->user()->role === 'user')
                        <div class="sidenav-menu-heading">Pembelian</div>
                        <a class="nav-link {{ request()->is('user/beli*') ? 'active' : '' }}" 
                            href="{{ '/user/beli' }}">
                            <div class="nav-link-icon"><i data-feather="dollar-sign"></i></div>
                            Beli Paket
                        </a>
                        <a class="nav-link {{ request()->is('user/transaksi*') ? 'active' : '' }}" 
                            href="{{ '/user/transaksi' }}">
                            <div class="nav-link-icon"><i data-feather="shopping-bag"></i></div>
                            Transaksi
                        </a>
                        <div class="sidenav-menu-heading">Tryout</div>
                        {{-- <a class="nav-link" href="#">
                            <div class="nav-link-icon"><i data-feather="dollar-sign"></i></div>
                            Beli Tryout
                        </a> --}}
                        <a class="nav-link" href="#">
                            <div class="nav-link-icon"><i data-feather="book"></i></div>
                            Kerjakan Tryout
                        </a>
                        <a class="nav-link" href="{#">
                            <div class="nav-link-icon"><i data-feather="check-square"></i></div>
                            Lihat Pembahasan
                        </a>

                        <div class="sidenav-menu-heading">Kelas</div>
                        {{-- <a class="nav-link" href="#">
                            <div class="nav-link-icon"><i data-feather="dollar-sign"></i></div>
                            Beli Kelas
                        </a> --}}
                        <a class="nav-link" href="#">
                            <div class="nav-link-icon"><i data-feather="monitor"></i></div>
                            Belajar
                        </a>
                    @endif

                    @if(in_array(auth()->user()->role, ['user', 'tutor']))
                    <div class="sidenav-menu-heading">Akun</div>
                    <a class="nav-link {{ request()->is('profile*') ? 'active' : '' }}" 
                        href="{{ '/profile' }}">
                        <div class="nav-link-icon"><i data-feather="settings"></i></div>
                        Pengaturan Akun
                    </a>
                   @endif
                    
                   
                </div>
            </div>
            <!-- Sidenav Footer-->
            <div class="sidenav-footer">
                <div class="sidenav-footer-content">
                    <div class="sidenav-footer-subtitle">Login sebagai:</div>
                    <div class="sidenav-footer-title">
                        <div x-data="{ name: '{{ auth()->user()->name }}' }" 
                            x-text="name" 
                            x-on:profile-updated.window="name = $event.detail.name">
                       </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    

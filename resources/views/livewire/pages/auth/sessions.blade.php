<x-guest-layout>
    <div class="container-xl px-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header d-flex align-items-center">
                        <img src="{{asset('assets/img/favicon.png')}}" alt="LMSAxon Logo" class="img-fluid me-2" style="width: 40px; height: 40px; border-radius:10px">
                        <h3 class="fw-light my-0">Axon Education - Sesi Perangkat Aktif</h3>
                    </div>
                    <div class="card-body">
                        @forelse($sessions as $session)
                            @php
                                $deviceInfo = $session->getDeviceInfo();
                            @endphp
                            <div class="mb-4 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <!-- Device Icon -->
                                        <div class="me-3">
                                            <i class="fas fa-{{ $deviceInfo['icon'] }} fa-lg text-black"></i>
                                        </div>
                                        <div>
                                            <!-- Device and Browser Info -->
                                            <div class="fw-bold">
                                                {{ ucfirst($deviceInfo['platform']) }} - {{ $deviceInfo['browser'] }}
                                            </div>
                                            <div class="small text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                Aktif terakhir: {{ $session->last_active_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    @if($session->token !== session()->getId())
                                        <form method="POST" action="{{ route('sessions.destroy', $session) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-sign-out-alt me-1"></i>
                                                Logout Device
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Perangkat Saat Ini
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">
                                <p>Tidak ditemukan sesi aktif.</p>
                            </div>
                        @endforelse

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                    <div class="card-footer text-center">
                        <div class="small text-sm mb-2">
                            Maksimum <span class="text-danger"><strong>3 PERANGKAT</strong></span> diperbolehkan secara bersamaan!</div>
                        <div class="small text-sm mb-2">
                            Untuk melanjutkan silahkan logout salah satu device terlebih dahulu
                        </div>
                        @if($sessions->count() < 3)
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                {{-- <i class="fas fa-arrow-left me-1"></i> --}}
                                Lanjutkan
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
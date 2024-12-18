{{-- resources/views/livewire/pages/auth/sessions.blade.php --}}
<x-guest-layout>
    <div class="container-xl px-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Active sessions card -->
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header d-flex align-items-center">
                        <!-- Image beside the text -->
                        <img src="{{asset('assets/img/favicon.png')}}" alt="LMSAxon Logo" class="img-fluid me-2" style="width: 40px; height: 40px; border-radius:10px">
                        
                        <!-- LMSAxon Text -->
                        <h3 class="fw-light my-0">Axon Education - Sesi Perangkat Aktif</h3>
                    </div>
                    <div class="card-body">
                        @forelse($sessions as $session)
                            <div class="mb-4 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ $session->device_name }}</div>
                                        <div class="small text-muted">
                                            Aktif terakhir: {{ $session->last_active_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    @if($session->token !== session()->getId())
                                        <form method="POST" action="{{ route('sessions.destroy', $session) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Logout Device
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-success">Current Device</span>
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
                                Untuk melanjutkan silahkan logout salah satu device terlebih dahulu</div>
                        <a href="{{ route('login') }}" class="btn btn-primary">Kembali ke halaman Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
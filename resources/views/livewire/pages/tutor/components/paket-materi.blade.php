<?php

use App\Models\PaketList;
use App\Models\TransaksiUser;
use App\Models\PaketMateri;
use Livewire\Volt\Component;

new class extends Component {
    public $pakets;
    public $materis;

    public function mount()
    {
        $this->loadPaket();
    }

    public function loadPaket()
    {
        $query = PaketList::where('active_status', true);

        // Cek paket yang sudah dibeli oleh user
        $purchasedPackages = TransaksiUser::where('user_id', auth()->id())
            ->whereIn('status', ['success', 'pending']) // Exclude both 'success' and 'pending'
            ->pluck('paket_id'); // Ambil hanya paket_id yang sudah dibeli atau pending

        // Exclude the purchased packages from the query
        if (request()->is('paket*')) {
            $query->whereIn('id', $purchasedPackages);
        }

        // Ambil paket-paket yang belum dibeli oleh user
        $this->pakets = $query->get();
    }
};
?>

<div class="container-xl px-4 mt-n10">
    <div class="card">
        <div class="card-body mt-2">

            <div class="col-lg-12">
                <div class="row">
                    @foreach($pakets as $paket)

                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm rounded-3">
                                <img class="card-img-top" src="{{ asset('assets/img/paket/' . $paket->image) }}"
                                     alt="{{ $paket->nama_paket }}">
                                <div class="card-body">
                                    <!-- Badges -->
                                    <div class="mb-2">
                                    <span class="badge bg-blue">
                                        @if ($paket->tipe === 'class')
                                            Kelas
                                        @elseif ($paket->tipe === 'tryout')
                                            Tryout
                                        @elseif ($paket->tipe === 'osce')
                                            OSCE
                                        @else
                                            {{ ucfirst($paket->tipe) }}
                                        @endif
                                    </span>
                                        <span class="badge bg-orange">
                                        @if (strtolower($paket->audience) === 'ukmppd' || strtolower($paket->audience) === 'aipki' || strtolower($paket->audience) === 'koas' || strtolower($paket->audience) === 'osce')
                                                {{ strtoupper($paket->audience) }}
                                            @elseif (strtolower($paket->audience) === 'preklinik' || strtolower($paket->audience) === 'kelas')
                                                {{ ucfirst($paket->audience) }}
                                            @else
                                                {{ $paket->audience }}
                                            @endif
                                    </span>

                                    </div>
                                    <h5 class="card-title">{{ $paket->nama_paket }}</h5>
                                    <p class="card-text">{{ Str::limit($paket->deskripsi, 100) }}</p>
                                    <div class="mt-2">
                                        @if ($paket->tier == 'free')
                                            <!-- Jika Gratis -->
                                            <span class="fw-bold text-success fs-5">GRATIS!</span>
                                        @else
                                            <!-- Jika Ada Diskon -->
                                            @if ($paket->discount > 0)
                                                <span class="text-danger text-decoration-line-through fs-6 me-2">
                                                Rp {{ number_format($paket->harga, 0, ',', '.') }}
                                            </span>
                                                <span class="fw-bold text-success fs-5">
                                                Rp {{ number_format($paket->harga - $paket->discount, 0, ',', '.') }}
                                            </span>
                                            @else
                                                <!-- Jika Tidak Ada Diskon -->
                                                <span class="fw-bold text-success fs-5">
                                                Rp {{ number_format($paket->harga, 0, ',', '.') }}
                                            </span>
                                            @endif
                                        @endif
                                    </div>

                                </div>
                                @if(request()->is('paket*'))
                                    <div class="card-footer bg-white text-center">
                                        <a href="{{route('paket.index').'/'. $paket->id}}"
                                           class="btn btn-md btn-primary w-100">Buka Paket</a>
                                    </div>
                                @else
                                    <div class="card-footer bg-white text-center">
                                        @if($paket->materi->count() > 0)
                                            <a href="{{route('tutor.paket.materi.create', $paket->id)}}"
                                               class="btn btn-md btn-primary w-100">Lihat Detail Materi</a>
                                        @else
                                            <a href="{{route('tutor.paket.materi.create', $paket->id)}}"
                                               class="btn btn-md btn-success w-100">Tambahkan Materi pada Paket
                                                Ini</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

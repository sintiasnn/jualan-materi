<?php

use App\Constants\Constants;
use App\Models\PaketList;
use App\Models\TransaksiUser;
use Livewire\Volt\Component;

new class extends Component {
    public $pakets;
    public $filterHarga = [];

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
        $query->whereNotIn('id', $purchasedPackages);

        // Filter Harga
        if (!empty($this->filterHarga)) {
            if (in_array('gratis', $this->filterHarga)) {
                $query->orWhere('tier', 'free');
            }
            if (in_array('berbayar', $this->filterHarga)) {
                $query->orWhere('tier', 'paid');
            }
        }

        // Ambil paket-paket yang belum dibeli oleh user
        $this->pakets = $query->get();
    }

    public function updatedFilterHarga()
    {
        $this->loadPaket();
    }

};
?>

    <!-- Blade View -->
<div class="container-xl px-4 mt-n10">

    <div class="row">
        <!-- Filter Card -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-filter me-2"></i>Filter Paket</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <!-- Accordion Filters -->
                            <div class="accordion" id="filterAccordion">
                                <!-- Harga Section -->
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="headingHarga">
                                        <button class="accordion-button bg-light text-dark fw-bold collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseHarga"
                                                aria-expanded="false" aria-controls="collapseHarga">
                                            <i class="bi bi-currency-dollar me-2"></i> Harga
                                        </button>
                                    </h2>
                                    <div id="collapseHarga" class="accordion-collapse collapse" aria-labelledby="headingHarga"
                                         data-bs-parent="#filterAccordion">
                                        <div class="accordion-body">
                                            <div class="form-check mb-2">
                                                <input wire:model="filterHarga" type="checkbox" value="berbayar"
                                                       class="form-check-input" id="berbayar">
                                                <label class="form-check-label" for="berbayar">Berbayar</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input wire:model="filterHarga" type="checkbox" value="gratis"
                                                       class="form-check-input" id="gratis">
                                                <label class="form-check-label" for="gratis">Gratis</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">

                        </div>
                        <div class="col-lg-2">
                            <!-- Apply Button -->
                            <button wire:click="loadPaket" class="btn btn-primary w-100">
                                <i class="bi bi-check-circle me-2"></i>Terapkan Filter
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Paket Cards -->
        <div class="col-lg-12">
            <div class="row">
                @forelse ($pakets as $paket)
                    <div class="col-lg-4 col-md-4 col-sm-3 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm rounded-3">
                            <img class="card-img-top"
                                 src="{{ file_exists(public_path() . '/storage' . Constants::PAKET_IMG_DIR . $paket->image) ? asset('storage' . Constants::PAKET_IMG_DIR . $paket->image) : asset(Constants::PAKET_IMG_DIR . $paket->image) }}"
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
                            <div class="card-footer bg-white text-center">
                                <div class="card-footer bg-white text-center">
                                    @if ($paket->tier === 'free')
                                        <a href="{{ route('user.checkout', ['id' => $paket->id]) }}"
                                           class="btn btn-sm btn-primary w-100">Daftar Sekarang!</a>
                                    @else
                                        <a href="{{ route('user.checkout', ['id' => $paket->id]) }}"
                                           class="btn btn-sm btn-success w-100">Beli Sekarang!</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted text-center">Tidak ada paket yang ditemukan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>


<?php

use App\Models\PaketList;
use App\Models\TransaksiUser;
use App\Models\PaketContent;
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
        $query->whereNotIn('id', $purchasedPackages);

        // Filter Kategori
        if (!empty($this->filterKategori)) {
            $query->whereIn('tipe', $this->filterKategori);
        }

        // Filter Harga
        if (!empty($this->filterHarga)) {
            if (in_array('gratis', $this->filterHarga)) {
                $query->orWhere('tier', 'free');
            }
            if (in_array('berbayar', $this->filterHarga)) {
                $query->orWhere('tier', 'paid');
            }
        }

        // Filter Audience
        if (!empty($this->filterAudience)) {
            $query->whereIn('audience', $this->filterAudience);
        }

        // Ambil paket-paket yang belum dibeli oleh user
        $this->pakets = $query->get();
    }

    public function loadPaketMateri($id)
    {
        return PaketContent::where('paket_id', $id);
    }
};
?>

<main>
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-title">
                <h5 class="card-body pb-0">
                    List Paket
                </h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="accordionExample">
                    @foreach($pakets as $paket)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-{{$loop->iteration}}">
                                <button class="accordion-button {{$loop->iteration == '1' ? '' : 'collapsed'}}" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{$loop->iteration}}" aria-expanded="true"
                                        aria-controls="collapse-{{$loop->iteration}}">
                                    {{$paket->nama_paket}}
                                </button>
                            </h2>
                            <div id="collapse-{{$loop->iteration}}" class="accordion-collapse {{$loop->iteration == '1' ? 'show' : 'collapse'}}"
                                 aria-labelledby="heading-{{$loop->iteration}}" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="py-3">
                                        <a type="button" href="{{route('tutor.paket.materi.create', $paket->id)}}" class="btn btn-primary">
                                            <i data-feather="plus-square" class="me-2"></i>
                                            Tambah Materi
                                        </a>
                                    </div>

                                    <ul class="list-group ">
                                        @foreach($paket->materi as $materi)
                                            <li class="list-group-item list-group-item-action text-gray-700">{{$materi->nama_materi}}</li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</main>

<?php

use App\Models\PaketList;
use App\Models\Submateri;
use Livewire\Volt\Component;

new class extends Component {
    public $pakets;
    public $paketId;

    public function mount($id)
    {
        $this->paketId = $id;
        $this->loadPaket($id);
    }

    public function loadPaket($id)
    {
        $paketMateri = PaketList::find($id)->materi;
        foreach ($paketMateri as $materi) {
            $materi->submateri_count = Submateri::where('materi_id', $materi->id)->count();
        }
        $this->pakets = $paketMateri;
    }
};
?>
    <!-- Main page content-->
<div class="container-xl px-4">
    <h4 class="mb-0 mt-5">Materi</h4>
    <hr class="mt-2 mb-4">
    <!-- Knowledge base main category card 1-->
    @foreach($pakets as $paket)
        <a class="card card-icon lift lift-sm mb-4"
           href="{{route('paket.materi.content',['id' => $paketId, 'code' => $paket->kode_materi])}}">
            <div class="row g-0">
                <div class="col-auto card-icon-aside bg-teal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-book text-white-50">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <div class="col">
                    <div class="card-body py-4">
                        <h5 class="card-title text-teal mb-2">{{$paket->nama_materi}}</h5>
                        <p class="card-text mb-1"></p>
                        <div class="small text-muted">{{$paket->submateri_count}} submateri pada materi ini</div>
                    </div>
                </div>
            </div>
        </a>
    @endforeach

</div>

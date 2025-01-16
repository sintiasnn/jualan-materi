<?php

use App\Models\ClassContent;
use App\Models\PaketContent;
use App\Models\PaketList;
use App\Models\TransaksiUser;
use Livewire\Volt\Component;

new class extends Component {
    public $contents;
    public $paket_id;
    public $domain;
    public $subdomain;

    public function mount($id, $code)
    {
        $this->paket_id = $id;
        $this->loadContent($code);
    }

    public function loadContent($code)
    {
        $classContent = ClassContent::where('kode_materi', $code);
        $this->contents = $classContent->get();

        $subdomain_id = $classContent->select('subdomain_id')->with('subdomain')->distinct()->first();
        $subdomain = \App\Models\Subdomain::find($subdomain_id);
        $this->subdomain = $subdomain->first()->keterangan;
        foreach ($subdomain as $item){
            $this->domain = $item->domain->keterangan;
        }
    }

};
?>

<div class="container-xl px-4">
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="h5">{{ __('Detail') }}</h5>
        </div>

        <div class="card-body">
            <table class="mt-3">
                <tr>
                    <th class="pe-3">Domain</th>
                    <td> : {{$domain}}</td>
                </tr>
                <tr>
                    <th class="pe-3">Subdomain</th>
                    <td> : {{$subdomain}}</td>
                </tr>

            </table>
        </div>
    </div>

    <!-- Knowledge base article-->
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center">
            <a class="btn btn-transparent-dark btn-icon" href="{{route('paket.materi',['id' => $paket_id])}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-arrow-left">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
            <div class="ms-3"><h2 class="my-3"></h2></div>
        </div>
        <div class="card-body">
            @foreach($contents as $content)
                <h4>{{$content->nama_submateri}}</h4>
                {!! $content->deskripsi !!}
            @endforeach

        </div>
    </div>


</div>

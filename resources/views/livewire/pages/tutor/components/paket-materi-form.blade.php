<?php

use App\Models\PaketList;
use App\Models\TransaksiUser;
use App\Models\PaketMateri;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Component;

new class extends Component {
    public $pakets;

    public $param;

    public function mount()
    {
        $this->param = $this->getParam();
    }

    function getParam()
    {
        return Route::current()->parameters();
    }
};
?>

<main>
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body">

                <ul class="nav nav-pills nav-fill">

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Active</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Much longer nav link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>
                </ul>

                <form method="POST" action="{{route('tutor.paket.materi.store', $this->param) }}" wire:key="$paket->id">

                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <a type="button" class="btn btn-outline-danger" href="{{route('tutor.paket.materi')}}">
                            {{ __('Kembali') }}
                        </a>

                        <button type="submit" class="btn btn-primary">
                            {{ __('Submit') }}
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>


</script>

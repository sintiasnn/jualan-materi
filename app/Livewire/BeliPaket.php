<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\PaketList;

class BeliPaket extends Component
{
    public $kategori = [];
    public $harga = [];

    public function render()
    {
        // Query awal: hanya ambil paket yang aktif
        $pakets = \App\Models\PaketList::where('active_status', true)->get();


        // Filter kategori
        if (!empty($this->kategori)) {
            $pakets->whereIn('tipe', $this->kategori);
        }

        // Filter harga
        if (!empty($this->harga)) {
            if (in_array('berbayar', $this->harga)) {
                $pakets->where('tier', 'paid');
            }
            if (in_array('gratis', $this->harga)) {
                $pakets->where('tier', 'free');
            }
        }

        // Ambil semua data yang sesuai
        // return view('livewire.beli-paket', [
        //     'pakets' => $pakets->get()
        // ]);
        dd($pakets);
        
    }

    
}

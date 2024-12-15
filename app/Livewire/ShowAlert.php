<?php

namespace App\Livewire;

use Livewire\Component;

class ShowAlert extends Component
{
    protected $listeners = ['showAlert'];
    
    public function showAlert($type, $message)
    {
        $this->dispatch('swal:modal', [
            'type' => $type,
            'title' => ucfirst($type) . '!',
            'text' => $message,
        ]);
    }

    public function render()
    {
        return view('livewire.show-alert');
    }
}
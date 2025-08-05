<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Admin Dashboard')]
class Dashboard extends AdminComponent
{
    public function render()
    {
        return view('livewire.dashboard');
    }
}

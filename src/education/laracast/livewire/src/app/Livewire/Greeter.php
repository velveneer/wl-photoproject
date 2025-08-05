<?php

namespace App\Livewire;

use App\Models\Greeting;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Greeter extends Component
{   
    #[Validate('required|min:2', onUpdate:false)]
    public $name = '';
    public $greeting = '';
    public $greetings = [];
    public $greetingMessage = '';

    public function changeGreeting() {
        $this -> reset('greetingMessage');

        $this -> validate();

        $this -> greetingMessage =  "{$this -> greeting}, {$this -> name}!";
    }

    public function mount() {
        $this -> greetings = Greeting::all();
    }

    public function updated($property, $value) {
        if ($property === 'name') {
            $this -> name = strtolower($value);
        }
    }

    public function updatedGreeting($value) {
        $this -> greeting = strtoupper($value);
    }

    public function render()
    {
        return view('livewire.greeter');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;

class TestComponent extends Component
{
    public $foo;

    public function mount($foo = null)
    {
        $this->foo = $foo;
    }

    public function render()
    {
        return view('livewire.test-component');
    }
}

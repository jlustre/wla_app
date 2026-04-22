<?php

namespace App\Livewire;

use Livewire\Component;

class MinimalTest extends Component
{
    public $testValue = 'Hello Livewire!';

    public function render()
    {
        return view('livewire.minimal-test');
    }
}

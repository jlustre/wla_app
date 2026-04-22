<?php

namespace App\Livewire;

use Livewire\Component;

class TestLivewire extends Component
{
    public $test = 'Hello Livewire!';

    public function updateTest()
    {
        $this->test = 'Updated at ' . now();
    }

    public function render()
    {
        return view('livewire.test-livewire');
    }
}

<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;

class TarimaIndex extends Component
{
    public function render()
    {
        $events = Event::where('is_active', true)
            ->where('is_published', true)
            ->latest()
            ->get();

        return view('livewire.admin.tarima-index', compact('events'))
            ->layout('layouts.admin');
    }
}

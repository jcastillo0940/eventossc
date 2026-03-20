<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use App\Services\RankingService;
use App\Events\WinnerRevealed;

class TarimaControl extends Component
{
    public Event $event;
    public $ranking;
    public array $revealed = [];

    public function mount(Event $event, RankingService $service)
    {
        $this->event = $event;
        $this->ranking = $service->getEventRanking($event);
        $this->revealed = $event->getRevealedPositions();
    }

    public function reveal(int $position)
    {
        // Add to DB
        $this->event->revealPosition($position);
        $this->revealed = $this->event->getRevealedPositions();

        // Broadcast to Stage
        broadcast(new WinnerRevealed($this->event, $position))->toOthers();
        
        // Push locally too (optional but helps UI)
        $this->dispatch('toast', ['title' => 'REVELADO', 'message' => "Posición #$position anunciada en pantalla."]);
    }

    public function resetStage()
    {
        $this->event->resetRevealedPositions();
        $this->revealed = [];
        broadcast(new WinnerRevealed($this->event, 0))->toOthers(); // 0 = Reset command
        $this->dispatch('toast', ['title' => 'RESET', 'message' => 'Todas las pantallas han sido reiniciadas.']);
    }

    public function render()
    {
        return view('livewire.admin.tarima-control')
            ->layout('layouts.admin');
    }
}

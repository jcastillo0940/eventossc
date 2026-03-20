<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Event;
use App\Services\RankingService;
use Livewire\Attributes\On;

class PantallaGigante extends Component
{
    public Event $event;
    public $ranking;
    public array $revealed = [];
    public array $visuals = [];

    public function mount(Event $event, RankingService $service)
    {
        $this->event = $event;
        $this->loadData($service);
        $this->loadVisuals();
    }

    protected function loadVisuals()
    {
        $this->visuals = [
            'bg' => $this->event->getSetting('stage_bg_color', '#020617'),
            'accent' => $this->event->getSetting('stage_accent_color', '#38bdf8'),
            'animation' => $this->event->getSetting('stage_reveal_animation', 'flip'),
            'confetti' => $this->event->getSetting('stage_enable_confetti', 'true') === 'true',
            'video_url' => $this->event->getSetting('stage_video_url', ''),
            'background_image' => $this->event->getSetting('stage_background_image', ''),
        ];
    }

    protected function loadData(RankingService $service)
    {
        $this->ranking = $service->getEventRanking($this->event)->take(10);
        $this->revealed = $this->event->getRevealedPositions();
    }

    public function refreshRevealed()
    {
        $old = $this->revealed;
        $new = $this->event->getRevealedPositions();
        
        // Find if anything new was revealed just now via polling
        foreach ($new as $pos) {
            if (!in_array($pos, $old)) {
                $this->dispatch('winner-celebration', $pos);
            }
        }
        
        $this->revealed = $new;
    }

    #[On('echo:event.{event.id},WinnerRevealed')]
    public function handleWinnerRevealed($data)
    {
        $pos = $data['position'];
        
        if ($pos === 0) {
            // Reset command
            $this->revealed = [];
        } else {
            // Add revealed position
            if (!in_array($pos, $this->revealed)) {
                $this->revealed[] = $pos;
                // Dispatch confetti JS event
                $this->dispatch('winner-celebration', $pos);
            }
        }
    }

    public function render()
    {
        return view('livewire.frontend.pantalla-gigante')
            ->layout('layouts.tarima');
    }
}

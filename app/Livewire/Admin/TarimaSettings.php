<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use Livewire\WithFileUploads;

class TarimaSettings extends Component
{
    use WithFileUploads;

    public Event $event;
    public $bg_color;
    public $accent_color;
    public $reveal_animation;
    public $enable_confetti;
    public $video_url;
    public $new_background_image;
    public $current_background_image;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->bg_color = $event->getSetting('stage_bg_color', '#020617');
        $this->accent_color = $event->getSetting('stage_accent_color', '#38bdf8');
        $this->reveal_animation = $event->getSetting('stage_reveal_animation', 'flip');
        $this->enable_confetti = $event->getSetting('stage_enable_confetti', 'true') === 'true';
        $this->video_url = $event->getSetting('stage_video_url', '');
        $this->current_background_image = $event->getSetting('stage_background_image', '');
    }

    public function save()
    {
        $this->event->settings()->updateOrCreate(['key' => 'stage_bg_color'], ['value' => $this->bg_color]);
        $this->event->settings()->updateOrCreate(['key' => 'stage_accent_color'], ['value' => $this->accent_color]);
        $this->event->settings()->updateOrCreate(['key' => 'stage_reveal_animation'], ['value' => $this->reveal_animation]);
        $this->event->settings()->updateOrCreate(['key' => 'stage_enable_confetti'], ['value' => $this->enable_confetti ? 'true' : 'false']);
        $this->event->settings()->updateOrCreate(['key' => 'stage_video_url'], ['value' => $this->video_url]);

        if ($this->new_background_image) {
            $path = $this->new_background_image->store('stage', 'public');
            $url = asset('storage/' . $path);
            $this->event->settings()->updateOrCreate(['key' => 'stage_background_image'], ['value' => $url]);
            $this->current_background_image = $url;
        }

        $this->dispatch('toast', ['title' => 'CAMBIOS GUARDADOS', 'message' => 'La configuración visual de la tarima ha sido actualizada.']);
    }

    public function render()
    {
        return view('livewire.admin.tarima-settings')
            ->layout('layouts.admin');
    }
}

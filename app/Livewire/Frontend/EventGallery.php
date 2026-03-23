<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Event;

class EventGallery extends Component
{
    public Event $event;
    public $limit = 8;
    public $totalPhotos = 0;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->totalPhotos = $this->event->getMedia('gallery_photos')->count();
    }

    public function loadMore()
    {
        $this->limit += 8;
    }

    public function render()
    {
        $photos = $this->event->getMedia('gallery_photos')->take($this->limit);
        $videos = $this->event->getMedia('gallery_videos');

        return view('livewire.frontend.event-gallery', [
            'photos' => $photos,
            'videos' => $videos,
            'hasMore' => $this->totalPhotos > $this->limit
        ]);
    }
}

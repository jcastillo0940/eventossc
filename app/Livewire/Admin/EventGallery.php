<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use Livewire\WithFileUploads;

class EventGallery extends Component
{
    use WithFileUploads;

    public Event $event;
    public $uploads = [];
    public $loading = false;

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function updatedUploads()
    {
        $this->validate([
            'uploads.*' => 'required|file|max:20480', // 20MB limit for photos/videos
        ]);

        foreach ($this->uploads as $file) {
            $isImage = str_starts_with($file->getMimeType(), 'image/');
            $collection = $isImage ? 'gallery_photos' : 'gallery_videos';
            
            $this->event->addMedia($file->getRealPath())
                ->usingFileName($file->getClientOriginalName())
                ->toMediaCollection($collection);
        }

        $this->uploads = [];
        $this->dispatch('toast', ['title' => 'ARCHIVOS CARGADOS', 'message' => 'La galería ha sido actualizada.']);
    }

    public function deleteMedia($mediaId)
    {
        $media = $this->event->media()->find($mediaId);
        if ($media) {
            $media->delete();
            $this->dispatch('toast', ['title' => 'ELIMINADO', 'message' => 'Archivo borrado de la galería.']);
        }
    }

    public function render()
    {
        $photos = $this->event->getMedia('gallery_photos');
        $videos = $this->event->getMedia('gallery_videos');

        return view('livewire.admin.event-gallery', compact('photos', 'videos'))
            ->layout('layouts.admin');
    }
}

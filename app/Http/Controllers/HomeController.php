<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(): View
    {
        $now = Carbon::now();

        $upcomingEvents = Event::where('is_published', true)
            ->where('date', '>=', $now)
            ->orderBy('date', 'asc')
            ->get();

        $pastEvents = Event::where('is_published', true)
            ->where('date', '<', $now)
            ->orderBy('date', 'desc')
            ->get();

        // Todas las marcas asociadas a al menos un evento, sin duplicados
        $brands = Brand::whereHas('events')
            ->orderBy('name')
            ->get();

        return view('home', compact('upcomingEvents', 'pastEvents', 'brands'));
    }
}
<?php

namespace App\Http\Controllers;

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

        return view('home', compact('upcomingEvents', 'pastEvents'));
    }
}

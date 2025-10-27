<?php

namespace App\Http\Controllers;

use App\Models\EventShowcase;
use App\Models\Package;
use Illuminate\Http\Request;

class EventShowcaseController extends Controller
{
    /**
     * Display a listing of event showcases
     */
    public function index(Request $request)
    {
        $query = EventShowcase::where('is_published', true)
            ->orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc');

        // Filter by type if provided
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Get package categories for navigation
        $categories = Package::select('type')
            ->distinct()
            ->pluck('type')
            ->filter()
            ->sort()
            ->values();

        // Paginate results
        $eventShowcases = $query->paginate(12);

        return view('events.index', compact('eventShowcases', 'categories'));
    }

    /**
     * Display the specified event showcase
     */
    public function show(EventShowcase $eventShowcase)
    {
        if (!$eventShowcase->is_published) {
            abort(404);
        }

        // Get related events of the same type
        $relatedEvents = EventShowcase::where('is_published', true)
            ->where('type', $eventShowcase->type)
            ->where('id', '!=', $eventShowcase->id)
            ->orderBy('display_order', 'asc')
            ->limit(3)
            ->get();

        return view('events.show', compact('eventShowcase', 'relatedEvents'));
    }
}

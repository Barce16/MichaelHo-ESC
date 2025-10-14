<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventShowcase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventShowcaseController extends Controller
{
    public function index()
    {
        $showcases = EventShowcase::orderBy('display_order')->paginate(12);
        $publishedCount = EventShowcase::where('is_published', true)->count();

        return view('admin.showcases.index', compact('showcases', 'publishedCount'));
    }

    public function create()
    {
        return view('admin.showcases.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'event_name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'location' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_published' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('showcases', 'public');
            $validated['image_path'] = $imagePath;
        }

        unset($validated['image']);

        EventShowcase::create($validated);

        return redirect()->route('admin.management.showcases.index')
            ->with('success', 'Event showcase created successfully!');
    }

    public function edit(EventShowcase $showcase)
    {
        return view('admin.showcases.edit', compact('showcase'));
    }

    public function update(Request $request, EventShowcase $showcase)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'event_name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_published' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($showcase->image_path && !filter_var($showcase->image_path, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($showcase->image_path);
            }

            $imagePath = $request->file('image')->store('showcases', 'public');
            $validated['image_path'] = $imagePath;
        }

        unset($validated['image']);

        $showcase->update($validated);

        return redirect()->route('admin.management.showcases.index')
            ->with('success', 'Event showcase updated successfully!');
    }

    public function destroy(EventShowcase $showcase)
    {
        // Delete image
        if ($showcase->image_path && !filter_var($showcase->image_path, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($showcase->image_path);
        }

        $showcase->delete();

        return redirect()->route('admin.management.showcases.index')
            ->with('success', 'Event showcase deleted successfully!');
    }

    public function publish(EventShowcase $showcase)
    {
        $showcase->publish();
        return back()->with('success', 'Event showcase published!');
    }

    public function unpublish(EventShowcase $showcase)
    {
        $showcase->unpublish();
        return back()->with('success', 'Event showcase unpublished!');
    }
}

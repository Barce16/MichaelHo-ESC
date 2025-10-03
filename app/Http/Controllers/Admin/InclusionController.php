<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\InclusionCategory;
use App\Models\Inclusion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Storage;

class InclusionController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $inclusions = Inclusion::query()
            ->when($q, fn($qr) => $qr->where('name', 'like', "%$q%")
                ->orWhere('category', 'like', "%$q%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.inclusions.index', compact('inclusions', 'q'));
    }

    public function create()
    {
        return view('admin.inclusions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'category'  => ['required', new Enum(InclusionCategory::class)],
            'price'     => ['required', 'numeric', 'min:0'],
            'image'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // Add this
            'notes'     => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('inclusions', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);
        Inclusion::create($data);

        return redirect()->route('admin.management.inclusions.index')
            ->with('success', 'Inclusion created.');
    }

    public function update(Request $request, Inclusion $inclusion)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'category'  => ['required', new Enum(InclusionCategory::class)],
            'price'     => ['required', 'numeric', 'min:0'],
            'image'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // Add this
            'notes'     => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        if ($request->hasFile('image')) {
            if ($inclusion->image && Storage::disk('public')->exists($inclusion->image)) {
                Storage::disk('public')->delete($inclusion->image);
            }
            $data['image'] = $request->file('image')->store('inclusions', 'public');
        }

        $inclusion->update([
            ...$data,
            'is_active' => $request->boolean('is_active', $inclusion->is_active),
        ]);

        return redirect()->route('admin.management.inclusions.index')
            ->with('success', 'Inclusion updated.');
    }

    public function edit(Inclusion $inclusion)
    {
        return view('admin.inclusions.edit', compact('inclusion'));
    }

    public function show(Inclusion $inclusion)
    {
        return view('admin.inclusions.show', compact('inclusion'));
    }

    public function destroy(Inclusion $inclusion)
    {
        $inclusion->delete();
        return back()->with('success', 'Inclusion deleted.');
    }
}

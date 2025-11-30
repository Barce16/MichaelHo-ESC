<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\InclusionCategory;
use App\Models\Inclusion;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Storage;

class InclusionController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $category = $request->input('category');
        $packageType = $request->input('package_type');

        $inclusions = Inclusion::query()
            // Search filter
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qr) use ($q) {
                    $qr->where('name', 'like', "%$q%")
                        ->orWhere('contact_person', 'like', "%$q%");
                });
            })
            // Category filter
            ->when($category, function ($query) use ($category) {
                $query->where('category', $category);
            })
            // Package type filter
            ->when($packageType, function ($query) use ($packageType) {
                $query->where('package_type', $packageType);
            })
            ->orderBy('name')
            ->paginate(5)
            ->withQueryString();

        return view('admin.inclusions.index', compact('inclusions', 'q', 'category', 'packageType'));
    }

    public function create()
    {
        $packageTypes = Package::getDistinctTypes();

        return view('admin.inclusions.create', compact('packageTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'category'       => ['required', new Enum(InclusionCategory::class)],
            'package_type'   => ['nullable', 'string', 'max:255'],
            'price'          => ['required', 'numeric', 'min:0'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'notes'          => ['nullable', 'string'],
            'is_active'      => ['nullable', 'boolean'],

            // contact fields
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_email'  => ['nullable', 'email', 'max:255'],
            'contact_phone'  => ['nullable', 'string', 'max:50'],
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('inclusions', 'public');
        }

        // Default to active for new inclusions
        $data['is_active'] = $request->boolean('is_active', true);

        Inclusion::create($data);

        return redirect()->route('admin.management.inclusions.index')
            ->with('success', 'Inclusion created.');
    }


    public function update(Request $request, Inclusion $inclusion)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'category'       => ['required', new Enum(InclusionCategory::class)],
            'package_type'   => ['nullable', 'string', 'max:255'],
            'price'          => ['required', 'numeric', 'min:0'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'notes'          => ['nullable', 'string'],
            'is_active'      => ['nullable', 'boolean'],

            // contact fields
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_email'  => ['nullable', 'email', 'max:255'],
            'contact_phone'  => ['nullable', 'string', 'max:50'],
        ]);

        // Handle image upload + delete old
        if ($request->hasFile('image')) {
            if ($inclusion->image && Storage::disk('public')->exists($inclusion->image)) {
                Storage::disk('public')->delete($inclusion->image);
            }
            $data['image'] = $request->file('image')->store('inclusions', 'public');
        }

        // Handle checkbox - when unchecked, it's not sent, so boolean() returns false
        $data['is_active'] = $request->boolean('is_active');

        $inclusion->update($data);

        return redirect()->route('admin.management.inclusions.index')
            ->with('success', 'Inclusion updated.');
    }


    public function edit(Inclusion $inclusion)
    {
        $packageTypes = Package::getDistinctTypes();

        return view('admin.inclusions.edit', compact('inclusion', 'packageTypes'));
    }

    public function show(Inclusion $inclusion)
    {
        return view('admin.inclusions.show', compact('inclusion'));
    }

    public function destroy(Inclusion $inclusion)
    {
        // Check if inclusion is being used by any packages
        if ($inclusion->packages()->count() > 0) {
            return back()->with('error', 'Cannot delete inclusion that is assigned to packages.');
        }

        // Delete image if exists
        if ($inclusion->image) {
            Storage::disk('public')->delete($inclusion->image);
        }

        $inclusion->delete();

        return redirect()
            ->route('admin.inclusions.index')
            ->with('success', 'Inclusion deleted successfully.');
    }

    public function toggleActive(Inclusion $inclusion)
    {
        $inclusion->update(['is_active' => !$inclusion->is_active]);

        return back()->with('success', 'Inclusion status updated.');
    }

    public function getByPackageType(Request $request)
    {
        $packageType = $request->get('package_type');
        $category = $request->get('category');

        $inclusions = Inclusion::where('is_active', true)
            ->when($packageType, function ($q) use ($packageType) {
                $q->where(function ($query) use ($packageType) {
                    $query->where('package_type', $packageType)
                        ->orWhereNull('package_type');
                });
            })
            ->when($category, fn($q) => $q->where('category', $category))
            ->orderBy('category')
            ->orderBy('name')
            ->get(['id', 'name', 'category', 'package_type', 'price']);

        return response()->json($inclusions);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inclusion;
use App\Models\Package;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\PackageImage;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $packages = Package::query()
            ->when(
                $q,
                fn($s) =>
                $s->where('name', 'like', "%{$q}%")
                    ->orWhere('type', 'like', "%{$q}%")
            )
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.packages.index', compact('packages', 'q'));
    }

    public function create()
    {
        $inclusions = Inclusion::where('is_active', true)
            ->orderBy('name')
            ->get();
        return view('admin.packages.create', compact('inclusions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                'unique:packages,name',
                'regex:/^[A-Za-z0-9 \-]+$/',
            ],
            'type' => ['required', 'string'],

            'price' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d+)?$/'],
            'is_active' => ['sometimes', 'boolean'],

            'inclusions'       => ['nullable', 'array'],
            'inclusions.*.id'  => ['nullable', 'integer', 'exists:inclusions,id'],

            'event_styling_text' => ['nullable', 'string', 'max:10000'],
            'coordination'       => ['nullable', 'string', 'max:5000'],
            'coordination_price' => ['nullable', 'numeric', 'min:0'],
            'event_styling_price' => ['nullable', 'numeric', 'min:0'],

            // Banner validation
            'banner' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'images'       => ['required', 'array', 'min:4'],
            'images.*'     => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'images_alt'   => ['array'],
            'images_alt.*' => ['nullable', 'string', 'max:255'],
        ]);

        $eventStylingArray = collect(preg_split('/\r\n|\r|\n/', $data['event_styling_text'] ?? ''))
            ->map(fn($s) => trim($s))
            ->filter()
            ->values()
            ->all();

        $coordinationPrice = $request->input('coordination_price', 25000);
        $stylingPrice      = $request->input('event_styling_price', 55000);

        DB::transaction(function () use ($request, $data, $eventStylingArray, $coordinationPrice, $stylingPrice) {
            // Handle banner upload
            $bannerPath = null;
            if ($request->hasFile('banner')) {
                $bannerPath = $request->file('banner')->store('packages/banners', 'public');
            }

            $package = Package::create([
                'name'                 => $data['name'],
                'slug'                 => Str::slug($data['name']),
                'type'                 => $data['type'],
                'price'                => $data['price'],
                'is_active'            => $request->boolean('is_active', true),
                'event_styling'        => $eventStylingArray,
                'coordination'         => $data['coordination'] ?? null,
                'coordination_price'   => $coordinationPrice,
                'event_styling_price'  => $stylingPrice,
                'banner'               => $bannerPath, // Added banner
            ]);

            $incoming = $request->input('inclusions', []);
            $ids = collect($incoming)
                ->map(function ($row) {
                    if (is_array($row)) {
                        return isset($row['id']) ? (int) $row['id'] : null;
                    }
                    return is_numeric($row) ? (int) $row : null;
                })
                ->filter()
                ->unique()
                ->values()
                ->all();

            $package->inclusions()->sync($ids);

            // Images
            $files = $request->file('images', []);
            foreach ($files as $i => $file) {
                $path = $file->store('packages/' . $package->id, 'public');
                PackageImage::create([
                    'package_id' => $package->id,
                    'path'       => $path,
                    'alt'        => $request->input("images_alt.$i"),
                    'sort'       => $i,
                ]);
            }
        });

        return redirect()
            ->route('admin.management.packages.index')
            ->with('success', 'Package created.');
    }

    public function show(Package $package)
    {
        $eventsUsingPackage = Event::with(['customer'])
            ->where('package_id', $package->id)
            ->orderByDesc('event_date')
            ->paginate(10);

        return view('admin.packages.show', compact('package', 'eventsUsingPackage'));
    }

    public function edit(Package $package)
    {

        $inclusions = Inclusion::where('is_active', true)->orderBy('name')->get();
        return view('admin.packages.edit', compact('package', 'inclusions'));
    }

    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'name'               => ['required', 'string', 'max:150', 'unique:packages,name,' . $package->id],
            'type' => ['required', 'string'],
            'price'              => ['required', 'numeric', 'min:0'],
            'is_active'          => ['sometimes', 'boolean'],

            'inclusions'         => ['nullable', 'array'],
            'inclusions.*.id'    => ['integer', 'exists:inclusions,id'],

            'event_styling_text' => ['nullable', 'string', 'max:10000'],
            'coordination'       => ['nullable', 'string', 'max:5000'],
            'coordination_price' => ['nullable', 'numeric', 'min:0'],
            'event_styling_price' => ['nullable', 'numeric', 'min:0'],

            // Banner validation
            'banner' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_banner' => ['sometimes', 'boolean'],

            'images'             => ['nullable', 'array'],
            'images.*'           => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'existing'           => ['sometimes', 'array'],
            'images_alt'         => ['sometimes', 'array'],
            'images_alt.*'       => ['nullable', 'string', 'max:255'],

            'remove_image_ids'   => ['sometimes', 'array'],
            'remove_image_ids.*' => ['integer', 'exists:package_images,id'],
        ]);


        $eventStylingArray = collect(preg_split('/\r\n|\r|\n/', $data['event_styling_text'] ?? ''))
            ->map(fn($s) => trim($s))
            ->filter()
            ->values()
            ->all();

        // Handle banner update/removal
        $bannerPath = $package->banner;

        // If user wants to remove banner
        if ($request->boolean('remove_banner') && $package->banner) {
            Storage::disk('public')->delete($package->banner);
            $bannerPath = null;
        }

        // If new banner uploaded
        if ($request->hasFile('banner')) {
            // Delete old banner if exists
            if ($package->banner) {
                Storage::disk('public')->delete($package->banner);
            }
            $bannerPath = $request->file('banner')->store('packages/banners', 'public');
        }

        $package->update([
            'name'                 => $data['name'],
            'slug'                 => Str::slug($data['name']),
            'type'                 => $data['type'],
            'price'                => $data['price'],
            'is_active'            => $request->boolean('is_active', $package->is_active),
            'event_styling'        => $eventStylingArray,
            'coordination'         => $data['coordination'] ?? null,
            'coordination_price'   => $request->input('coordination_price', 25000),
            'event_styling_price'  => $request->input('event_styling_price', 55000),
            'banner'               => $bannerPath,
        ]);


        $incoming = $request->input('inclusions', []);
        $sync = [];
        foreach ($incoming as $row) {
            if (!empty($row['id'])) {
                $sync[(int) $row['id']] = [];
            }
        }
        $package->inclusions()->sync($sync);

        // ----- Images handling -----
        $removeIds   = collect($request->input('remove_image_ids', []))->map(fn($id) => (int)$id)->all();
        $newFiles    = $request->file('images', []);
        $existingCnt = $package->images()->count();
        $remaining   = $existingCnt - count($removeIds);
        $totalAfter  = $remaining + count($newFiles);

        if ($totalAfter < 4) {
            return back()
                ->withErrors(['images' => 'Package must have at least 4 images.'])
                ->withInput();
        }


        $existingAlts = $request->input('existing', []);
        if (!empty($existingAlts)) {
            foreach ($existingAlts as $imgId => $alt) {
                $img = $package->images()->whereKey($imgId)->first();
                if ($img && !in_array((int)$imgId, $removeIds, true)) {
                    $img->update(['alt' => $alt]);
                }
            }
        }

        if (!empty($removeIds)) {
            $package->images()
                ->whereIn('id', $removeIds)
                ->get()
                ->each(function ($img) {
                    if ($img->path ?? null) {
                        Storage::disk($img->disk ?? 'public')->delete($img->path);
                    }
                    $img->delete();
                });
        }

        if (!empty($newFiles)) {
            $alts = $request->input('images_alt', []);
            foreach (array_values($newFiles) as $i => $file) {

                $path = $file->store('packages', 'public');
                $alt  = $alts[$i] ?? '';
                $package->images()->create([
                    'disk' => 'public',
                    'path' => $path,
                    'url'  => asset('storage/' . $path),
                    'alt'  => $alt,
                ]);
            }
        }

        return redirect()
            ->route('admin.management.packages.index')
            ->with('success', 'Package updated.');
    }

    public function destroy(Package $package)
    {
        // Delete banner if exists
        if ($package->banner) {
            Storage::disk('public')->delete($package->banner);
        }

        $package->delete();
        return back()->with('success', 'Package deleted.');
    }

    public function toggle(Package $package)
    {
        $package->update(['is_active' => ! $package->is_active]);
        return back()->with('success', 'Package status updated.');
    }
}

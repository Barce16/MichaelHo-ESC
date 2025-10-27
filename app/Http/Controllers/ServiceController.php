<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        // Get all packages query
        $query = Package::query();

        // Filter by budget range if provided
        if ($request->has('min_budget') && $request->min_budget) {
            $query->where('price', '>=', $request->min_budget);
        }
        if ($request->has('max_budget') && $request->max_budget) {
            $query->where('price', '<=', $request->max_budget);
        }

        // Get all packages grouped by type
        $packagesByType = $query->orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        // Get categories for navigation
        $categories = Package::select('type')
            ->distinct()
            ->pluck('type')
            ->filter()
            ->sort()
            ->values();

        return view('services.index', compact('packagesByType', 'categories'));
    }

    public function category($category)
    {
        // Get packages for this category
        $packages = Package::where('type', $category)
            ->orderBy('name')
            ->get();

        // Get all categories for navigation
        $categories = Package::select('type')
            ->distinct()
            ->pluck('type')
            ->filter()
            ->sort()
            ->values();

        return view('services.category', compact('packages', 'category', 'categories'));
    }

    public function show(Package $package)
    {
        // Get categories
        $categories = Package::select('type')
            ->distinct()
            ->pluck('type')
            ->filter()
            ->sort()
            ->values();

        // Get related packages from same type
        $relatedPackages = Package::where('type', $package->type)
            ->where('id', '!=', $package->id)
            ->limit(3)
            ->get();

        // Get only THIS package's inclusions grouped by category
        $packageInclusions = $package->inclusions()
            ->where('is_active', true)
            ->get()
            ->groupBy(function ($inclusion) {
                return $inclusion->category->value ?? $inclusion->category->name ?? 'Other';
            });

        // Get ALL available inclusions for this package type grouped by category
        // Show inclusions where package_type is null/empty OR matches this package's type
        $allInclusions = \App\Models\Inclusion::where('is_active', true)
            ->where(function ($query) use ($package) {
                $query->whereNull('package_type')
                    ->orWhere('package_type', '')
                    ->orWhere('package_type', $package->type);
            })
            ->get()
            ->groupBy(function ($inclusion) {
                return $inclusion->category->value ?? $inclusion->category->name ?? 'Other';
            });

        return view('services.show', compact('package', 'relatedPackages', 'categories', 'packageInclusions', 'allInclusions'));
    }
}

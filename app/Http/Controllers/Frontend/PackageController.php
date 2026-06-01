<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Setting;
use Inertia\Inertia;

class PackageController extends Controller
{
    public function umroh()
    {
        return Inertia::render('frontend/Packages/Index', [
            'title' => 'Paket Umroh',
            'type' => 'umroh',
            'setting' => Setting::query()->first(),

            'packages' => Package::query()
                ->where('type', 'umroh')
                ->where('status', 'published')
                ->latest()
                ->paginate(9),
        ]);
    }

    public function haji()
    {
        return Inertia::render('frontend/Packages/Index', [
            'title' => 'Paket Haji',
            'type' => 'haji',
            'setting' => Setting::query()->first(),

            'packages' => Package::query()
                ->where('type', 'haji')
                ->where('status', 'published')
                ->latest()
                ->paginate(9),
        ]);
    }

    public function show(string $slug)
    {
        $package = Package::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return Inertia::render('frontend/Packages/Show', [
            'setting' => Setting::query()->first(),
            'packageData' => $package,

            'relatedPackages' => Package::query()
                ->where('id', '!=', $package->id)
                ->where('type', $package->type)
                ->where('status', 'published')
                ->latest()
                ->take(3)
                ->get(),
        ]);
    }
}
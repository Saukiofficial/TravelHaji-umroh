<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Setting;
use Inertia\Inertia;

class GalleryController extends Controller
{
    public function index()
    {
        return Inertia::render('frontend/Galleries/Index', [
            'setting' => Setting::query()->first(),

            'galleries' => Gallery::query()
                ->where('is_active', true)
                ->latest()
                ->paginate(12),
        ]);
    }

    public function show(Gallery $gallery)
    {
        abort_if(! $gallery->is_active, 404);

        return Inertia::render('frontend/Galleries/Show', [
            'setting' => Setting::query()->first(),

            'gallery' => $gallery,

            'relatedGalleries' => Gallery::query()
                ->where('id', '!=', $gallery->id)
                ->where('is_active', true)
                ->latest()
                ->take(6)
                ->get(),
        ]);
    }
}
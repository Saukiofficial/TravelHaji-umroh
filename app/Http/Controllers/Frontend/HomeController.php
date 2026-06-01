<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Setting;
use App\Models\Testimonial;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('frontend/Home', [
            'setting' => Setting::query()->first(),

            'featuredPackages' => Package::query()
                ->where('status', 'published')
                ->where('is_featured', true)
                ->latest()
                ->take(6)
                ->get(),

            'upcomingPackages' => Package::query()
                ->where('status', 'published')
                ->whereNotNull('departure_date')
                ->orderBy('departure_date')
                ->take(6)
                ->get(),

            'testimonials' => Testimonial::query()
                ->where('is_active', true)
                ->latest()
                ->take(6)
                ->get(),

            'galleries' => Gallery::query()
                ->where('is_active', true)
                ->latest()
                ->take(6)
                ->get(),

            'articles' => Article::query()
                ->where('status', 'published')
                ->latest()
                ->take(3)
                ->get(),
        ]);
    }
}
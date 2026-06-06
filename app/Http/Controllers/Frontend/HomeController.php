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
        $setting = Setting::query()->first();

        return Inertia::render('frontend/Home', [
            'setting' => [
                'website_name' => $setting?->website_name,
                'logo' => $setting?->logo,
                'hero_image' => $setting?->hero_image,

                'phone' => $setting?->phone,
                'whatsapp' => $setting?->whatsapp,
                'email' => $setting?->email,
                'address' => $setting?->address,
                'google_maps' => $setting?->google_maps,

                'instagram' => $setting?->instagram,
                'facebook' => $setting?->facebook,
                'tiktok' => $setting?->tiktok,
                'youtube' => $setting?->youtube,

                'meta_title' => $setting?->meta_title,
                'meta_description' => $setting?->meta_description,
            ],

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
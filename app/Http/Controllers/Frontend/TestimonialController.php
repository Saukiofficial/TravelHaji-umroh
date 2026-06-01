<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Testimonial;
use Inertia\Inertia;

class TestimonialController extends Controller
{
    public function index()
    {
        return Inertia::render('frontend/Testimonials/Index', [
            'setting' => Setting::query()->first(),

            'testimonials' => Testimonial::query()
                ->where('is_active', true)
                ->latest()
                ->paginate(12),
        ]);
    }
}
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Setting;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function index()
    {
        return Inertia::render('frontend/Articles/Index', [
            'setting' => Setting::query()->first(),

            'articles' => Article::query()
                ->where('status', 'published')
                ->latest()
                ->paginate(9),
        ]);
    }

    public function show(string $slug)
    {
        return Inertia::render('frontend/Articles/Show', [
            'setting' => Setting::query()->first(),

            'article' => Article::query()
                ->where('slug', $slug)
                ->where('status', 'published')
                ->firstOrFail(),
        ]);
    }
}
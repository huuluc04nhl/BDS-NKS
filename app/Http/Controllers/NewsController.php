<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Property;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display the news list.
     */
    public function index(Request $request)
    {
        $category = $request->query('category', 'report');
        $search = $request->query('q');

        // Fetch spotlight: newest post matching filter or globally
        $spotlightQuery = Post::query();
        if ($search) {
            $spotlightQuery->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('summary', 'like', '%' . $search . '%');
            });
        } else {
            $spotlightQuery->where('category', $category);
        }
        $spotlight = $spotlightQuery->orderBy('id', 'desc')->first();

        // Fetch grid posts (excluding spotlight)
        $postsQuery = Post::query();
        if ($search) {
            $postsQuery->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('summary', 'like', '%' . $search . '%');
            });
        } else {
            $postsQuery->where('category', $category);
        }

        if ($spotlight) {
            $postsQuery->where('id', '!=', $spotlight->id);
        }

        $posts = $postsQuery->orderBy('id', 'desc')->paginate(6);

        // Sidebar content: popular posts
        $popular = Post::where('category', 'report')->orderBy('id', 'asc')->take(4)->get();

        return view('news.index', compact('posts', 'spotlight', 'category', 'search', 'popular'));
    }

    /**
     * Display a specific news article.
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();

        // Fallback for numeric IDs
        if (!$post && is_numeric($slug)) {
            $post = Post::find($slug);
        }

        if (!$post) {
            abort(404, 'Không tìm thấy bài viết yêu cầu.');
        }

        // Fetch related posts (same category, excluding current post)
        $related = Post::where('category', $post->category)
            ->where('id', '!=', $post->id)
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        return view('news.show', compact('post', 'related'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->latest('published_at')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function show(Post $post)
    {
        // 404 if someone hits a slug that isn't published yet
        abort_unless($post->published_at && $post->published_at <= now(), 404);

        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}

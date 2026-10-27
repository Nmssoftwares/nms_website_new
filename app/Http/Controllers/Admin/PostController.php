<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        $posts = Post::latest('created_at')->get();
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        unset($data['featured_image']);

        if ($path = $this->replaceUpload($request, 'featured_image', 'posts')) {
            $data['featured_image'] = $path;
        }

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('status', 'Post created.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validateData($request, $post->id);
        unset($data['featured_image']);

        if ($path = $this->replaceUpload($request, 'featured_image', 'posts', $post->featured_image)) {
            $data['featured_image'] = $path;
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('status', 'Post updated.');
    }

    public function destroy(Post $post)
    {
        $this->deleteUpload($post->featured_image);
        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', 'Post deleted.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        // Make the slug FIRST, so the "already used?" check tests the real value.
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: (string) $request->input('title')),
        ]);

        return $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'required|string|max:220|unique:posts,slug' . ($ignoreId ? ",{$ignoreId}" : ''),
            'excerpt' => 'required|string|max:300',
            'body_html' => 'required|string',
            'author' => 'nullable|string|max:120',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|image|max:4096',
        ]);
    }
}
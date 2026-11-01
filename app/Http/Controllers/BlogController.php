<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::live()->paginate(9);

        return view('blog.index', ['posts' => $posts]);
    }

    public function show(Post $post)
    {
        abort_unless($post->isLive(), 404);

        return view('blog.show', [
            'post' => $post,
            'related' => Post::live()->where('id', '!=', $post->id)->limit(3)->get(),
        ]);
    }
}

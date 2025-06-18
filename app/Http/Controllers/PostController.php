<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of posts
     */
    public function index(): View
    {
        $posts = Post::with('user')->latest()->get();
        return view('posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('posts.create');
    }

    /**
     * Store a new post
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'caption' => 'required|string|max:2000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file('image')->store('uploads');
        
        $request->user()->posts()->create([
            'caption' => $data['caption'],
            'image_path' => $imagePath,
        ]);
        

        return redirect('/profile/' . $request->user()->id);
    }

    /**
     * Display a specific post
     */
    public function show(Post $post): View
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Edit a specific post
     */
    public function edit(Post $post): View
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }
    /**
     * Update a post
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'caption' => 'required|string|max:2000',
        ]);

        $post->update($data);

        return redirect('/posts/' . $post->id);
    }

    /**
     * Delete a post
     */
    public function destroy(Post $post): RedirectResponse
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        Storage::disk('s3')->delete($post->image_path);

        $post->delete();

        return redirect('/profile/' . Auth::user()->id);
    }

    
} 
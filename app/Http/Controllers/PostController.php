<?php

namespace App\Http\Controllers;

use App\Models\Post; // Import the Model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Needed for deleting images

class PostController extends Controller
{
    /**
     * Display a listing of the resource (The Feed).
     */
    public function index()
    {
        $posts = Post::with(['user', 'country', 'likes', 'comments'])
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Post::class); // Security Check
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Post::class); // Security Check
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $request->user()->posts()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $imagePath ? 'storage/' . $imagePath : null, 
            'country_id' => \App\Models\Country::inRandomOrder()->first()->id,
        ]);

        return redirect()->route('posts.index')->with('message', 'Post created successfully!');
    }

    /**
     * Display the specified resource (The Details Page).
     */
    public function show(string $id)
    {
        $post = Post::with(['user', 'country', 'comments.user'])->findOrFail($id);
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('update', $post); // Security Check

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('update', $post); // Security Check
        

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete(str_replace('storage/', '', $post->image));
            }
            $imagePath = $request->file('image')->store('posts', 'public');
            $post->image = 'storage/' . $imagePath;
        }

        $post->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('posts.show', $post->id)->with('message', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('delete', $post); // Security Check
        

        if ($post->image) {
            Storage::disk('public')->delete(str_replace('storage/', '', $post->image));
        }

        $post->delete();

        return redirect()->route('posts.index')->with('message', 'Post deleted successfully!');
    }
}
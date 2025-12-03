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
        $posts = Post::with(['user', 'country', 'likes', 'comments', 'media'])->latest()->paginate(10);

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
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,qt|max:10240', // Max 10MB per file
        ]);

        $post = $request->user()->posts()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'country_id' => \App\Models\Country::inRandomOrder()->first()->id, // Temporary fallback
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                
                $path = $file->store('posts', 'public');
                
                $mime = $file->getMimeType();
                $type = str_contains($mime, 'video') ? 'video' : 'image';

                $post->media()->create([
                    'file_path' => 'storage/' . $path,
                    'file_type' => $type,
                ]);
            }
        }

        return redirect()->route('posts.index')->with('message', 'Memory shared successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,qt|max:10240',
        ]);

        $post->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('posts', 'public');
                $mime = $file->getMimeType();
                $type = str_contains($mime, 'video') ? 'video' : 'image';

                $post->media()->create([
                    'file_path' => 'storage/' . $path,
                    'file_type' => $type,
                ]);
            }
        }

        return redirect()->route('posts.show', $post->id)->with('message', 'Post updated successfully!');
    }

    /**
     * Display the specified resource (The Details Page).
     */
    public function show(string $id)
    {
        $post = Post::with(['user', 'country', 'comments.user', 'media'])->findOrFail($id);
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
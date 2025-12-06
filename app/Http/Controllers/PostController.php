<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewPostNotification;
use Illuminate\Support\Facades\Gate;

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
        Gate::authorize('create', Post::class);
        $countries = \App\Models\Country::orderBy('name')->get();
        return view('posts.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Post::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'country_id' => 'required|exists:countries,id', 
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,qt|max:10240',
        ]);

        $post = $request->user()->posts()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'country_id' => $validated['country_id'], 
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

        $followers = $request->user()->followers;
        if ($followers->count() > 0) {
            Notification::send($followers, new NewPostNotification($post, $request->user()));
        }

        return redirect()->route('posts.index')->with('message', 'Memory shared successfully!');
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
        Gate::authorize('update', $post);

        // Fetch countries for the edit dropdown
        $countries = \App\Models\Country::orderBy('name')->get();

        return view('posts.edit', compact('post', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'country_id' => 'required|exists:countries,id',
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,qt|max:10240',
        ]);

        $post->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'country_id' => $validated['country_id'],
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::with('media')->findOrFail($id);
        Gate::authorize('delete', $post);

        foreach ($post->media as $media) {
            $relativePath = str_replace('storage/', '', $media->file_path);
            Storage::disk('public')->delete($relativePath);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('message', 'Post deleted successfully!');
    }
}
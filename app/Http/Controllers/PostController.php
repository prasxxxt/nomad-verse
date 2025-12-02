<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = \App\Models\Post::with(['user', 'country', 'likes', 'comments'])
            ->latest()
            ->paginate(10); // Changed from get() to paginate()

        return view('posts.index', compact('posts'));   
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file
        ]);

        
        $imagePath = null;
        if ($request->hasFile('image')) {
            
            $imagePath = $request->file('image')->store('posts', 'public'); 
        }

    
        $request->user()->posts()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $imagePath ? '/storage/' . $imagePath : null,
            'country_id' => \App\Models\Country::inRandomOrder()->first()->id, // Temporary: Pick random country until dropdown is implemented
        ]);

        
        return redirect()->route('posts.index')->with('message', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-4">Edit Memory</h2>

                <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Required for Update requests --}}

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('title') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                        <textarea name="description" id="description" rows="6" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $post->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>

                    @if($post->image)
                        <div class="mb-4">
                            <p class="text-gray-600 text-sm mb-2">Current Image:</p>
                            <img src="{{ asset($post->image) }}" alt="Current Image" class="w-48 h-32 object-cover rounded border">
                        </div>
                    @endif

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="image">Change Photo (Optional)</label>
                        <input type="file" name="image" id="image" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 text-blue-700
                            hover:file:bg-blue-100">
                        @error('image') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Update Memory
                        </button>
                        <a href="{{ route('posts.show', $post->id) }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
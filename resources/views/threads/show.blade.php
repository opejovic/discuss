@extends('layouts.app')

@section('content')
    <div class="pb-4">
        <div class="text-center text-gray-800 text-xl">
            {{ $thread->title }}

            <div class="text-sm">
                <div>{{ $thread->author->name }}</div>
                <div class="text-gray-600 text-xs">{{ $thread->published_at }}</div>
            </div>
        </div>
        <div class="text-center pt-2 text-gray-800">
          {{ $thread->body }}
        </div>
    </div>

    <div>
        @forelse($replies as $reply)
            @include('threads.reply')
        @empty
            No replies yet.
        @endforelse
    </div>

    @if (Auth::check())
    <div class="w-1/2 pt-2">
        <form action="{{ route('replies.store', $thread) }}" method="POST">
            @csrf
            <div>
                <textarea class="w-full text-gray-700 placeholder-gray-500 text-sm border p-3 rounded-lg focus:outline-none" name="body" id="" rows="4" placeholder="Have something to say?"></textarea>
            </div>

            <button type="submit" class="rounded-lg shadow bg-gray-200 hover:bg-gray-300 block px-4 py-2 uppercase text-gray-700 text-xs">Submit</button>
        </form>
    </div>
    @endif
@endsection

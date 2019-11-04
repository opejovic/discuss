@extends('layouts.app')

@section('content')
    <div class="pb-4">
        <div class="text-center text-gray-800 text-xl">
            {{ $thread->title }}

            <div class="text-sm">
                <a class="text-gray-600 underline" href="#">{{ $thread->author->name }}</a>
                <div class="text-gray-600 text-xs">{{ $thread->published_at }}</div>
            </div>
        </div>
        <div class="text-center pt-2 text-gray-800">
          {{ $thread->body }}
        </div>
    </div>

    <div class="border-b-4 w-1/6 mx-auto mb-6 mt-2 border-red-500"></div>

    @auth
        <like-button
            :item="{{ $thread }}"
            store="{{ route('likes.store', $thread) }}"
            destroy="{{ route('likes.destroy', $thread) }}"
        >
        </like-button>
    @endauth

    <div class="text-gray-600 py-4">
        {{ $thread->replies()->count() }} {{ Str::plural('comment', $thread->replies()->count()) }}
    </div>

    <div>
        @foreach($replies as $reply)
            @include('threads.reply')
        @endforeach

        {{ $replies->links() }}
    </div>

    @if (Auth::check())
        @include('threads.reply-form')
    @else
        <div class="text-sm pt-4"><a class="text-gray-600 hover:text-gray-500 border-b-2 pb-1" href="{{ route('login') }}">Sign in</a> if you want to join the discussion.</div>
    @endif
@endsection

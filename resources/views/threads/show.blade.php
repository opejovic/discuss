@extends('layouts.app')

@section('content')
    <div class="md:w-2/3 w-full mx-auto break-words">
        <div class="pb-4">
            <div class="text-center text-gray-800 text-xl">
                {{ $thread->title }}

                <div class="text-sm">
                    <a class="text-gray-600 underline" href="{{ route('profile', $thread->author) }}">{{ $thread->author->name }}</a>
                    <div class="text-gray-600 text-xs">{{ $thread->published_at }}</div>
                </div>
            </div>
            <div class="text-center pt-2 text-gray-800">
              {{ $thread->body }}
            </div>
        </div>

        <div class="border-b-4 w-1/6 mx-auto mb-6 mt-2 border-red-500"></div>

            <div class="flex justify-center items-center">
            @auth
                <like-button
                    :item="{{ $thread }}"
                    store="{{ route('likes.store', $thread) }}"
                    destroy="{{ route('likes.destroy', $thread) }}"
                >
                </like-button>
            @endauth

            @can('update', $thread)
                <div class="cursor-pointer items-center justify-center rounded-full ml-2 border border-gray-200 inline-block w-12 h-12 flex text-xs text-gray-600 leading-loose hover:text-red-500 hover:border-red-300 focus:outline-none">
                    <form action="{{ route('threads.destroy', $thread) }}" method="POST">
                        @method('DELETE')
                        @csrf

                        <button type="submit" class="focus:outline-none">Delete</button>
                    </form>
                </div>
            @endcan
            </div>

        <div class="text-gray-600 py-4">
            {{ $thread->replies_count }} {{ Str::plural('comment', $thread->replies_count) }}
        </div>

        <div>
            @foreach($replies as $reply)
                @include('threads.reply')
            @endforeach

            {{ $replies->links() }}
        </div>

        @auth
            @include('threads.reply-form')
        @else
            <div class="text-sm pt-4 text-gray-700"><a class="text-gray-600 hover:text-gray-500 border-b-2 pb-1" href="{{ route('login') }}">Sign in</a> if you want to join the discussion.</div>
        @endauth
    </div>
@endsection

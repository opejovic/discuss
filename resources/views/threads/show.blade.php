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
            <div class="text-justify pt-2 text-gray-800">
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

                <subscribe-button
                    :item="{{ $thread }}"
                    store="{{ route('threads.subscribe', $thread) }}"
                    destroy="{{ route('threads.unsubscribe', $thread) }}"
                >
                </subscribe-button>
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

        <replies path="{{ route('replies.index', $thread) }}" :thread="{{ $thread }}"></replies>
    </div>
@endsection

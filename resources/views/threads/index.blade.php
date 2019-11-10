@extends('layouts.app')

@section('content')
    @forelse ($threads as $thread)
        <div class="mx-auto text-center text-gray-800 sm:w-1/3 w-full break-words">
            <a class="text-lg" href="{{ route('threads.show', [$thread->channel, $thread]) }}">
                {{ $thread->title }}
            </a>

            <div class="text-gray-700 text-sm pt-2">
                <div class="uppercase text-xs text-gray-500">Written by</div>
                <a href="{{ route('profile', $thread->author) }}" class="border-b-2">
                    {{ $thread->author->name }}
                </a>
            </div>

            <div class="text-gray-500 text-xs pt-2">
                {{ $thread->published_at }}
            </div>

            @include('components.red-line')
        </div>
    @empty
        This channel looks empty af. Do you want to say something on this topic?
    @endforelse
@endsection

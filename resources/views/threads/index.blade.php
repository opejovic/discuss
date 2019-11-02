@extends('layouts.app')

@section('content')
    @foreach ($threads as $thread)
    <div class="mx-auto text-center text-gray-800">
        <a class="text-lg" href="{{ route('threads.show', [$thread->channel, $thread]) }}">
            {{ $thread->title }}
        </a>

        <div class="text-gray-700 text-sm pt-2">
            <div class="uppercase text-xs text-gray-500">Written by</div>
            <a href="#" class="border-b-2">
                {{ $thread->author->name }}
            </a>
        </div>
        <div class="text-gray-500 text-xs pt-2">
            {{ $thread->published_at }}
        </div>

        <div class="border-b-2 sm:w-1/12 w-2/5 mx-auto mb-6 mt-2 border-red-500 py-2"></div>
    </div>
    @endforeach
@endsection

@extends('layouts.app')

@section('content')
    @foreach ($threads as $thread)
    <div class="mx-auto text-center text-gray-800">
        <a class="text-lg" href="{{ route('threads.show', [$thread->channel, $thread]) }}">
            {{ $thread->title }}
        </a>
        <div class="text-gray-700 text-sm">
            <a href="#" class="border-b-2">
                {{ $thread->author->name }}
            </a>
        </div>
        <div class="text-gray-700 text-xs pt-2">
            {{ $thread->created_at->diffForHumans() }}
        </div>

        <div class="border-b-2 w-1/12 mx-auto mb-6 mt-2 border-red-500 py-2"></div>
    </div>
    @endforeach
@endsection

@extends('layouts.app')

@section('content')
    <div class="pb-4">
        <div class="text-center text-gray-800 text-xl">
            {{ $thread->title }}
        </div>
        <div class="text-center pt-2 text-gray-800">
            {{ $thread->body }}
        </div>
    </div>

    <div>
        @forelse($replies as $reply)
            <div class="text-gray-800 text-sm">
                {{ $reply->author->name }}: {{ $reply->body }} <span class="text-xs">{{ $reply->created_at->diffForHumans() }}</span>
            </div>

        @empty
            No replies yet.
        @endforelse
    </div>
@endsection

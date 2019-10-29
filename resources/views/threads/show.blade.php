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
@endsection

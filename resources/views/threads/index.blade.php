@extends('layouts.app')

@section('content')
    @foreach ($threads as $thread)
    <div class="">
        <a href="{{ route('threads.show', [$thread->channel, $thread]) }}">
            {{ $thread->title }}
        </a>
    </div>
    @endforeach
@endsection

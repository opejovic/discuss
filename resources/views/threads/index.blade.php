@extends('layouts.app')

@section('content')
    @foreach ($threads as $thread)
    <div class="">
        <a href="{{ $thread->path() }}">
            {{ $thread->title }}
        </a>
    </div>
    @endforeach    
@endsection

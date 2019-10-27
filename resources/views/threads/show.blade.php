@extends('layouts.app')

@section('content')
    <div class="">
        {{ $thread->title }}
        {{ $thread->body }}
    </div>
@endsection
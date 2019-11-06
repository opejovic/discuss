@extends('layouts.app')

@section('content')
<div class="xl:w-1/2 md:w-2/3 w-full mx-auto">
    <div class="flex justify-between items-center border-b-2 pb-3 border-gray-100 text-gray-700">
        <div class="flex items-center justify-start">
            <img class="xl:w-24 w-20" src="/images/login.svg" alt="">
            <div class="text-xl pl-4 font-bold">
                {{ $user->name }}
            </div>
        </div>

        <div class="text-xs italic">
            Member since {{ $user->member_since }}
        </div>
    </div>

    @foreach($threads as $thread)
        <div class="text-gray-700 py-4 mx-auto text-center">
            <div class="pb-4 text-center hover:underline inline-block">
                <a href="{{ route('threads.show', [$thread->channel, $thread]) }}">{{ $thread->title }}</a>
            </div>
            <div class="text-sm text-gray-600">
                {{ $thread->body }}
            </div>
        </div>
        @include('components.red-line')
    @endforeach

    {{ $threads->links() }}

</div>
@endsection

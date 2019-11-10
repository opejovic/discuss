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

    @foreach($activities as $date => $activity)
        <div class="text text-gray-600 text-center py-2">
            {{ $date }}
        </div>

        @foreach($activity as $record)
        <div class="text-gray-700 py-4 mx-auto text-center">
            @include("profiles.activities.$record->type", ['activity' => $record])
        </div>
        @endforeach

        @include('components.red-line')
    @endforeach

</div>
@endsection

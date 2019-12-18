@extends('layouts.app')

@section('content')
<div class="xl:w-1/2 md:w-2/3 w-full mx-auto">
    <div class="border-b-2 pb-3 border-gray-100 text-gray-700">
        <div class="flex justify-between items-center pb-2">
            <div class="flex items-center justify-start">
                {{-- <avatar-component /> --}}

                <img class="xl:w-24 w-20 cursor-pointer rounded-full" src="{{ $profileUser->avatar }}"
                    alt="users-avatar" />

                <form action="{{ route('avatars.store', $profileUser) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="avatar" id="avatar" accept="image/*" />

                    <button type="submit">
                        Upload
                    </button>
                </form>

                <div class="text-xl pl-4 font-bold">
                    {{ $profileUser->name }}
                </div>
            </div>

            <div class="text-xs italic">
                Member since {{ $profileUser->member_since }}
            </div>
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
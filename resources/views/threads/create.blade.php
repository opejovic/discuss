@extends('layouts.app')

@section('content')
    <div class="px-1">
        <div class="px-2 py-8 sm:p-8 lg:w-2/5 sm:w-3/5 mx-auto rounded-lg border border-gray-200 text-center">
            <img class="xl:w-48 w-40 px-5 mx-auto pb-6" src="/images/new-thread.svg" alt="">

            <div class="card-body">
                <form method="POST" action="{{ route('threads.store') }}">
                    @csrf

                    <div class="pb-4">
                        <div class="">
                            <input id="title" type="text" class="border text-gray-700 rounded-lg w-full focus:outline-none h-12 text-center placeholder-gray-400 @error('name') is-invalid @enderror" name="title" value="{{ old('title') }}" required autocomplete="title" autofocus placeholder="Title">

                            @error('title')
                                <span class="text-red-500 text-xs font-light" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="pb-4">
                        <div class="">
                            <textarea id="body" class="border text-gray-700 rounded-lg w-full focus:outline-none text-center placeholder-gray-400 place p-2 @error('body') is-invalid @enderror" name="body" value="{{ old('body') }}" required placeholder="Body" rows="8"></textarea>

                            @error('body')
                                <span class="text-red-500 text-xs font-light" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="block py-2 bg-gray-100 shadow rounded-lg sm:w-1/2 w-full mx-auto hover:bg-gray-200 text-sm text-gray-700">
                            {{ __('Publish') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

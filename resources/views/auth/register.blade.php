@extends('layouts.app')

@section('content')
<div class="px-1">
    <div class="px-2 py-8 sm:p-8 lg:w-2/5 sm:w-3/5 mx-auto rounded-lg sm:border border-gray-200 text-center">
        <img class="xl:w-56 w-48 px-5 mx-auto pb-6" src="/images/register.svg" alt="">

        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="pb-4">
                    <div class="">
                        <input id="name" type="text" class="border text-gray-700 rounded-lg w-full focus:outline-none h-12 text-center placeholder-gray-400 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Name">

                        @error('name')
                            <span class="text-red-500 text-xs font-light" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="pb-4">
                    <div class="">
                        <input id="email" type="email" class="border text-gray-700 rounded-lg w-full focus:outline-none h-12 text-center placeholder-gray-400 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email">

                        @error('email')
                            <span class="text-red-500 text-xs font-light" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="pb-4">
                    <div class="">
                        <input id="password" type="password" class="border text-gray-700 rounded-lg w-full focus:outline-none h-12 text-center placeholder-gray-400 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">

                        @error('password')
                            <span class="text-red-500 text-xs font-light" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="pb-4">
                    <div class="">
                        <input id="password-confirm" type="password" class="border text-gray-700 rounded-lg w-full focus:outline-none h-12 text-center placeholder-gray-400" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="block py-2 text-gray-700 text-sm bg-gray-100 shadow rounded-lg sm:w-1/2 w-full mx-auto hover:bg-gray-200">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

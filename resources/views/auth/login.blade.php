@extends('layouts.app')

@section('content')
<div class="px-1">
    <div class="px-2 py-8 sm:p-8 lg:w-2/5 sm:w-3/5 mx-auto rounded-lg border border-gray-200 text-center">
        <div class="">
            <img class="xl:w-40 w-32 px-5 mx-auto pb-6" src="/images/login.svg" alt="">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="pb-4">
                    <div class="">
                        <input id="email" type="email" class="border text-gray-700 rounded-lg w-full focus:outline-none h-12 text-center placeholder-gray-400 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">

                        @error('email')
                            <span class="text-red-500 text-xs pt-1 font-light" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="pb-4">
                    <div class="">
                        <input id="password" type="password" class="border text-gray-700 rounded-lg w-full focus:outline-none h-12 text-center placeholder-gray-400 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                        @error('password')
                            <span class="text-red-500 text-xs pt-1 font-light" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="">
                    <div class="">
                        <div class="">
                            <input class="cursor-pointer" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="font-thin text-xs text-gray-700 cursor-pointer" for="remember">
                                {{ __('Keep me signed in') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-10">
                    <button type="submit" class="block py-2 text-gray-700 bg-gray-100 text-sm shadow rounded-lg sm:w-1/2 w-full mx-auto hover:bg-gray-200">
                        {{ __('Login') }}
                    </button>

                    @if (Route::has('password.request'))
                        <a class="font-thin text-xs text-gray-600 hover:text-gray-500" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

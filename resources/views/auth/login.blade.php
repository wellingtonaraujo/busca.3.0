<x-layouts.auth_login_layout>
    <div class="">
        {{-- <div class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card"> --}}
        <div class="flex items-center justify-center h-screen w-screen bg-gray-100">
            <div class="p-6 bg-indigo-600 shadow-lg rounded-lg">
                <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
                    <h2 class="text-2xl font-semibold text-center mb-6 text-gray-700">
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('assets/images/logos/login-key.svg') }}" width="50"
                                alt="Ícone de login">
                            <span>Login</span>
                        </div>
                    </h2>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="block text-gray-600 text-sm font-medium mb-1">
                                {{ __('Email Address') }}
                            </label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autofocus
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror">

                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-gray-600 text-sm font-medium mb-1">
                                {{ __('Password') }}
                            </label>
                            <input id="password" type="password" name="password" required
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror">

                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4 flex items-center">
                            <input class="mr-2 leading-tight" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="text-sm text-gray-600" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                {{ __('Login') }}
                            </button>

                            @if (Route::has('password.request'))
                                <a class="text-sm text-indigo-600 hover:underline"
                                    href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-layouts.auth_login_layout>


<div class="min-h-screen flex items-center justify-center">

</div>

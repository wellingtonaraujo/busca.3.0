<x-layouts.auth_login_layout>
    <div class="">
        <div class="flex items-center justify-center h-screen w-screen bg-gray-900">
            <div class="p-6 bg-gradient-to-r from-blue-800 via-blue-700 to-blue-500 shadow-lg rounded-lg">
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

                        {{-- ALERTA DE ERRO GLOBAL (login inválido) --}}
                        @if ($errors->any())
                            <div class="mb-4">
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                    <span class="block sm:inline">
                                        {{ $errors->first() }}
                                    </span>
                                </div>
                            </div>
                        @endif


                        {{-- ALERTA DE STATUS (ex: senha redefinida) --}}
                        @if (session('status'))
                            <div class="mb-4">
                                <div
                                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                                    <span class="block sm:inline">
                                        {{ session('status') }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="cpf" class="block text-gray-600 text-sm font-medium mb-1">
                                {{ __('Número do CPF') }} <span class="text-red-800">*</span>
                            </label>
                            <input id="cpf" type="number" name="cpf" value="{{ old('cpf') }}" required
                                autofocus title="Somente números"
                                class="w-full px-4 py-2 border rounded-lg text-black focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('cpf') border-red-500 @enderror">

                            @error('cpf')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-gray-600 text-sm font-medium mb-1">
                                {{ __('Password') }}
                            </label>
                            <input id="password" type="password" name="password" required
                                title="No mínimo 8 caracteres"
                                class="w-full px-4 py-2 border text-black rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror">

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

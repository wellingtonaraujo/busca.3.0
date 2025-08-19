<header>
    <div class="z-40 sticky top-0 py-[15px] px-6 bg-[linear-gradient(90deg,_#0f0533_0%,_#1b0a5c_100%)] w-full">
        <div class="flex items-center gap-4 w-full">
            <!-- Menu e Logo (lado esquerdo) -->
            <div class="flex items-center gap-4">
                <!-- Botão mobile -->
                <span id="btn-main-menu" class="text-white cursor-pointer lg:hidden">
                    <i class="text-4xl ti ti-menu-2"></i>
                </span>

                <!-- Logo + título -->
                <div class="hidden md:flex items-center gap-5">
                    <a href="" class="flex flex-row items-center">
                        <img src="{{ asset('assets/images/logos/policia_penal.png') }}" width="20"
                            alt="logo-wrappixel" />
                        <p class="text-white ms-3">{{ config('app.name') }}</p>
                    </a>
                </div>
            </div>

            <!-- Alpine.js (adicione no <head> se ainda não estiver presente) -->
            <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

            <!-- Usuário (lado direito) -->
            <div x-data="{ open: false }" class="ml-auto relative flex flex-col md:flex-row items-center gap-4">
                @if (Auth::check())
                    <div class="relative text-right">
                        <!-- Nome do usuário (botão que ativa o dropdown) -->
                        <h4 @click="open = !open"
                            class="cursor-pointer text-sm font-normal text-white uppercase font-semibold bg-[linear-gradient(90deg,_#FFFFFF_0%,_#8D70F8_100%)] [-webkit-background-clip:text] [background-clip:text] [-webkit-text-fill-color:transparent]">
                            [{{ Auth::user()->name }}]
                        </h4>

                        <!-- Dropdown (inicialmente oculto) -->
                        <ul x-cloak x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-50 text-sm text-gray-700">
                            <li class="px-4 py-2 hover:bg-gray-100">Email: {{ Auth::user()->email }}</li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                        <div class="flex items-center justify-center gap-2">
                                            <img src="{{ asset('assets/images/logos/logout.svg') }}" width="20px"
                                                alt="">
                                            <span>Sair</span>
                                        </div>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <h4
                        class="text-sm font-normal text-white uppercase font-semibold bg-[linear-gradient(90deg,_#FFFFFF_0%,_#8D70F8_100%)] [-webkit-background-clip:text] [background-clip:text] [-webkit-text-fill-color:transparent]">
                        [USUÁRIO]
                    </h4>
                @endif
            </div>

        </div>
    </div>
</header>

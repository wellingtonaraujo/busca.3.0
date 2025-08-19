    @php
        use App\Models\Menu;
        use App\Models\ProfileMenu;
        $menu_ids = ProfileMenu::select('menu_id')
            ->where('profile_id', Auth::user()->profile_id)
            ->get();
        $menus = Menu::with('children')
            ->whereIn('id', $menu_ids)
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order_no')
            ->get();
    @endphp
    <aside class="bg-white shadow-md w-[300px] hidden lg:block rounded-xl" id="main-menu">
        <div class="p-4">
            <a href="#" class="flex flex-row items-center">
                <img src="{{ asset('assets/images/logos/app_logo.svg') }}" width="20" alt="Logo-Dark" />
                <p class="text-black ms-3">[Título da app][MENU]</p>
            </a>
        </div>
        <div>
            <nav class="w-full flex flex-col sidebar-nav px-4 mt-5">
                <ul id="sidebarnav" class="text-gray-600 text-sm">
                    @foreach ($menus as $menu)
                        <li class="sidebar-item">
                            @php $hasChildren = $menu->children->count() > 0; @endphp

                            @if ($hasChildren)
                                <button type="button"
                                    class="flex items-center justify-between text-base py-2.5 my-1 w-full rounded-md hover:bg-gray-100 transition"
                                    onclick="toggleSubmenu(this)">
                                    <div class="flex items-center gap-2">
                                        <i class="{{ $menu->icon }} text-2xl"></i>
                                        <span>{{ $menu->name }}</span>
                                    </div>
                                    <i class="ti ti-chevron-down transition-transform duration-300"></i>
                                </button>

                                <ul class="ml-6 mt-1 hidden">
                                    @foreach ($menu->children as $child)
                                        @if ($child->existeProfileMenu($child->id))
                                            <li class="sidebar-item">
                                                <a href="{{ $child->route }}"
                                                    class="block text-sm py-1 px-2 text-gray-600 hover:text-blue-600">
                                                    <i class="{{ $child->icon }} text-sm mr-1"></i> {{ $child->name }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <a href="{{ $menu->route }}"
                                    class="flex items-center gap-2 text-base py-2.5 my-1 w-full rounded-md hover:bg-gray-100 transition">
                                    <i class="{{ $menu->icon }} text-2xl"></i>
                                    <span>{{ $menu->name }}</span>
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </aside>

    <script>
        function toggleSubmenu(button) {
            const submenu = button.nextElementSibling;
            const icon = button.querySelector('.ti-chevron-down');

            submenu.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    </script>

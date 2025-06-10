<!-- Navbar principale -->
<nav class="fixed top-0 left-0 right-0 z-50 h-16 bg-gradient-to-r from-[#000000] to-[#33001c] border-b border-[#33001c]">
    <div class="flex items-center justify-between h-full px-4">
        <div class="flex items-center">
            <img src="{{ asset('/assets/img/logo/logo2.png') }}" alt="Logo" class="h-8">
        </div>
        
        <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
            <button class="flex items-center space-x-2 focus:outline-none">
                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(auth()->user()->email)) }}?s=256&d=mp" alt="Avatar" class="w-8 h-8 rounded-full">
                <span class="text-sm font-medium text-gray-100">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
            </button>
            
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="absolute right-0 w-48 mt-2 py-2  bg-gradient-to-r from-[#000000] to-[#33001c] rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-100 hover:bg-gray-100">
                    <i class="fas fa-user w-5 h-5 mr-2"></i>
                    Profil
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-100 hover:bg-gray-100">
                    <i class="fas fa-cog w-5 h-5 mr-2"></i>
                    Paramètres
                </a>
                <div class="border-t border-gray-100"></div>
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-100 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt w-5 h-5 mr-2"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<button data-drawer-target="sidebar-multi-level-sidebar" data-drawer-toggle="sidebar-multi-level-sidebar" aria-controls="sidebar-multi-level-sidebar" type="button" class="fixed top-4 left-4 z-50 inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
    <span class="sr-only">Open sidebar</span>
    <i class="fas fa-bars w-6 h-6"></i>
</button>

<aside id="sidebar-multi-level-sidebar" class="fixed top-16 left-0 z-40 w-64 h-[calc(100vh-4rem)] transition-transform -translate-x-full sm:translate-x-0 bg-gradient-to-b from-[#000000] to-[#33001c]" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto">
       <ul class="space-y-2 font-medium">
        <li>
          <a href="#" class="flex items-center p-2 text-gray-50 rounded-lg border border-transparent hover:bg-[#e6007e]/10 group hover:border-[#e6007e] hover:rounded-xl">
            <i class="fas fa-chart-line text-gray-500 transition duration-75 group-hover:text-gray-50 text-base"></i>
            <span class="ms-3">Dashboard</span>
          </a>
        </li>
        
          <li>
            <button type="button" class="flex items-center w-full p-2 text-base text-gray-50 transition duration-75 rounded-lg border border-transparent hover:bg-[#e6007e]/10 group hover:border-[#e6007e] hover:rounded-xl" aria-controls="dropdown-demarche" data-collapse-toggle="dropdown-demarche">
              <i class="fas fa-tasks shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-50"></i>
              <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Démarche</span>
              <i class="fas fa-chevron-down w-3 h-3"></i>
            </button>
            <ul id="dropdown-demarche" class="hidden py-2 space-y-2">
              <li>
                <a href="/entreprise" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 border border-transparent hover:bg-[#e6007e]/10 group hover:border-[#e6007e] hover:rounded-xl">• Entreprise</a>
              </li>
              <li>
                <a href="/entreprise/groupe" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 border border-transparent hover:bg-[#e6007e]/10 group hover:border-[#e6007e] hover:rounded-xl">• Groupe</a>
              </li>
              <li>
                <a href="#" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 border border-transparent hover:bg-[#e6007e]/10 group hover:border-[#e6007e] hover:rounded-xl">• Email</a>
              </li>
              <li>
                <a href="#" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 border border-transparent hover:bg-[#e6007e]/10 group hover:border-[#e6007e] hover:rounded-xl">• Liste envoie</a>
              </li>
            </ul>
          </li>
          <li>
            <button type="button" class="flex items-center w-full p-2 text-base text-gray-50 transition duration-75 rounded-lg border border-transparent hover:bg-[#e6007e]/10 group hover:border-[#e6007e] hover:rounded-xl" aria-controls="dropdown-license" data-collapse-toggle="dropdown-license">
              <i class="fas fa-tasks shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-50"></i>
              <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">License</span>
              <i class="fas fa-chevron-down w-3 h-3"></i>
            </button>
            <ul id="dropdown-license" class="hidden py-2 space-y-2">
              <li>
                <a href="/licenses" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 border border-transparent hover:bg-[#e6007e]/10 group hover:border-[#e6007e] hover:rounded-xl">• Voir les licences</a>
              </li>
            </ul>
          </li>
          
          <li>
            <a href="/laravel" class="flex items-center p-2 text-gray-50 rounded-lg border border-transparent hover:bg-[#e6007e]/10 group hover:border-[#e6007e] hover:rounded-xl">
              <i class="fas fa-chart-line text-gray-500 transition duration-75 group-hover:text-gray-50 text-base"></i>
              <span class="ms-3">Laravel</span>
            </a>
          </li>

          <!--<li>
             <a href="#" class="flex items-center p-2 text-gray-50 rounded-lg border border-transparent hover:bg-[#e6007e]/10 group hover:border-[#e6007e] hover:rounded-xl">
                <i class="fas fa-columns shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-50"></i>
                <span class="flex-1 ms-3 whitespace-nowrap">Kanban</span>
             </a>
          </li>-->
          
       </ul>
    </div>
 </aside>
 

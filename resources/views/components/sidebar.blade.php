<aside x-data class="z-10 w-[300px] transition-all duration-300 md:relative fixed h-[100vh] bg-gray-100" :class="{'-ml-[300px]': !$store.sidebarOpen.on}" aria-label="Sidebar">
    <div class="py-4 px-3 rounded">
        <ul>
            <li class="text-end">
                <button class="text-2xl bg-slate-400 py-1 px-2 rounded-xl transition-all duration-300" @click="$store.sidebarOpen.toggle()">
                    <i class="bi bi-chevron-left"></i>
                </button>
            </li>
            <li class="text-center mb-10">
                <div class="w-full flex justify-center">
                    <img class="w-1/3" src="{{ Vite::image('logo.png') }}" />
                </div>
                <h4 class="font-bold text-xl mt-3" >{{ config('entity.name') }}</h4>
            </li>
            <li class="my-1">
                <a href="{{ route('home') }}"  class="p-2 text-base font-normal text-gray-900 rounded-lg bg-gray-200 hover:bg-gray-300 dark:hover:bg-gray-700 truncate block">
                    <i class="bi bi-house-fill text-xl"></i>
                    &nbsp;
                    Home
                </a>
            </li>
            @role(\App\Enums\UserRole::RECEPTIONIST)
                <li class="my-1">
                    <a href="{{ route('reception.prohibited') }}"  class="p-2 text-base font-normal text-gray-900 rounded-lg bg-gray-200 hover:bg-gray-300 dark:hover:bg-gray-700 truncate block">
                        <i class="bi bi-house-up-fill text-xl"></i>
                        &nbsp;
                        Entrada
                    </a>
                </li>
                <li class="my-1">
                    <a href="{{ route('reception.attended') }}"  class="p-2 text-base font-normal text-gray-900 rounded-lg bg-gray-200 hover:bg-gray-300 dark:hover:bg-gray-700 truncate block">
                        <i class="bi bi-collection-fill text-xl"></i>
                        &nbsp;
                        Atendimentos
                    </a>
                </li>
            @endrole
            @role(\App\Enums\UserRole::NURSE)
                <li class="my-1">
                    <a href="{{ route('screening.attended') }}"  class="p-2 text-base font-normal text-gray-900 rounded-lg bg-gray-200 hover:bg-gray-300 dark:hover:bg-gray-700 truncate block">
                        <i class="bi bi-collection-fill text-xl"></i>
                        &nbsp;
                        Atendimentos
                    </a>
                </li>
            @endrole
            @role(\App\Enums\UserRole::DOCTOR)
                <li class="my-1">
                    <a href="{{ route('appointment.attended') }}"  class="p-2 text-base font-normal text-gray-900 rounded-lg bg-gray-200 hover:bg-gray-300 dark:hover:bg-gray-700 truncate block">
                        <i class="bi bi-collection-fill text-xl"></i>
                        &nbsp;
                        Atendimentos
                    </a>
                </li>
            @endrole
            @role(\App\Enums\UserRole::NURSING_TECHNICIAN)
                <li class="my-1">
                    <a href="{{ route('ambulatory.prescriptions.attended') }}"  class="p-2 text-base font-normal text-gray-900 rounded-lg bg-gray-200 hover:bg-gray-300 dark:hover:bg-gray-700 truncate block">
                        <i class="bi bi-file-earmark-medical-fill text-xl"></i>
                        &nbsp;
                        Prescrições
                    </a>
                </li>
                <li class="my-1">
                    <a href="{{ route('ambulatory.exams.attended') }}"  class="p-2 text-base font-normal text-gray-900 rounded-lg bg-gray-200 hover:bg-gray-300 dark:hover:bg-gray-700 truncate block">
                        <i class="bi bi-clipboard2-pulse-fill text-xl"></i>
                        &nbsp;
                        Exames
                    </a>
                </li>
            @endrole
        </ul>
    </div>
    <div class="absolute bottom-10 w-full flex justify-center">
        <div @click.away="open = false" class="relative w-60 flex justify-center" x-data="{ open: false }">
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute bottom-12 w-full mt-2 origin-top-right rounded-md shadow-lg md:w-48">
                <div class="p-1  bg-gray-400 rounded-md shadow">
                    <a href="{{ route('profile') }}" class="block px-4 py-2 mb-2 text-sm font-semibold bg-gray-500 hover:bg-gray-600 w-full rounded text-center">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}" class="block w-full">
                        @csrf
                        <button type="submit" class="block px-4 py-2 text-sm font-semibold bg-gray-500 hover:bg-gray-600 w-full rounded">
                            Sair
                        </button>
                    </form>
                </div>
            </div>
            <button @click="open = !open" class="block w-full bg-gray-400 hover:bg-gray-500 py-2 rounded-xl">
                <span class="uppercase">{{ auth()->user()->firstName }}</span>
                <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}" class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
    </div>
    <button x-show="!$store.sidebarOpen.on" class="text-2xl top-3 bg-slate-400 py-1 px-2 fixed rounded-xl transition-all duration-300 left-0" @click="$store.sidebarOpen.toggle()">
        <i class="bi bi-chevron-right"></i>
    </button>
    <button class="hidden text-2xl top-3 bg-slate-400 py-1 px-2 fixed rounded-xl transition-all duration-300" :class="{'left-0': !$store.sidebarOpen.on, 'left-64': $store.sidebarOpen.on}" @click="$store.sidebarOpen.toggle()"><i :class="{'bi bi-chevron-right': !$store.sidebarOpen.on, 'bi bi-chevron-left': $store.sidebarOpen.on}"></i></button>
</aside>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('sidebarOpen', {
            on:  Alpine.$persist(false).as('sidebarOpen'),
            toggle () {
                this.on = !this.on
            }
        });

        Alpine.store('sectionsSidebarOpen', {
            on:  Alpine.$persist([]).as('sectionsSidebarOpen'),
            toggle (key) {
                this.on[key] = !this.on[key]
            }
        });
    })
</script>
<aside x-data class="fixed z-10 h-[100vh] w-[300px] bg-gray-100 transition-all duration-300 md:relative"
    :class="{ '-ml-[300px]': !$store.sidebarOpen.on }" aria-label="Sidebar">
    <div class="rounded px-3 py-4">
        <ul>
            <li class="text-end">
                <button class="rounded-xl bg-slate-400 px-2 py-1 text-2xl transition-all duration-300"
                    @click="$store.sidebarOpen.toggle()">
                    <i class="bi bi-chevron-left"></i>
                </button>
            </li>
            <li class="mb-10 text-center">
                <div class="flex w-full justify-center">
                    <img class="w-1/3" src="{{ Vite::image('logo.png') }}" />
                </div>
                <h4 class="mt-3 text-xl font-bold">{{ config('entity.name') }}</h4>
            </li>
            <li class="my-1">
                <a href="{{ route('home') }}"
                    class="block truncate rounded-lg bg-gray-200 p-2 text-base font-normal text-gray-900 hover:bg-gray-300 dark:hover:bg-gray-700">
                    <i class="bi bi-house-fill text-xl"></i>
                    &nbsp;
                    Home
                </a>
            </li>
            @role(\App\Enums\UserRole::RECEPTIONIST)
                <li class="my-1">
                    <a href="{{ route('reception.prohibited') }}"
                        class="block truncate rounded-lg bg-gray-200 p-2 text-base font-normal text-gray-900 hover:bg-gray-300 dark:hover:bg-gray-700">
                        <i class="bi bi-house-up-fill text-xl"></i>
                        &nbsp;
                        Entrada
                    </a>
                </li>
                <li class="my-1">
                    <a href="{{ route('reception.attended') }}"
                        class="block truncate rounded-lg bg-gray-200 p-2 text-base font-normal text-gray-900 hover:bg-gray-300 dark:hover:bg-gray-700">
                        <i class="bi bi-collection-fill text-xl"></i>
                        &nbsp;
                        Atendidos
                    </a>
                </li>
            @endrole
            @role(\App\Enums\UserRole::NURSE)
                <li class="my-1">
                    <a href="{{ route('screening.attended', 'entrada') }}"
                        class="block truncate rounded-lg bg-gray-200 p-2 text-base font-normal text-gray-900 hover:bg-gray-300 dark:hover:bg-gray-700">
                        <i class="bi bi-collection-fill text-xl"></i>
                        &nbsp;
                        Entrada
                    </a>
                </li>
                <li class="my-1">
                    <a href="{{ route('screening.attended', 'atendidos') }}"
                        class="block truncate rounded-lg bg-gray-200 p-2 text-base font-normal text-gray-900 hover:bg-gray-300 dark:hover:bg-gray-700">
                        <i class="bi bi-collection-fill text-xl"></i>
                        &nbsp;
                        Atendidos
                    </a>
                </li>
            @endrole
            @role(\App\Enums\UserRole::DOCTOR)
                <li class="my-1">
                    <a href="{{ route('appointment.attended', 'entrada') }}"
                        class="block truncate rounded-lg bg-gray-200 p-2 text-base font-normal text-gray-900 hover:bg-gray-300 dark:hover:bg-gray-700">
                        <i class="bi bi-collection-fill text-xl"></i>
                        &nbsp;
                        Entrada
                    </a>
                </li>
                <li class="my-1">
                    <a href="{{ route('appointment.attended', 'atendidos') }}"
                        class="block truncate rounded-lg bg-gray-200 p-2 text-base font-normal text-gray-900 hover:bg-gray-300 dark:hover:bg-gray-700">
                        <i class="bi bi-collection-fill text-xl"></i>
                        &nbsp;
                        Atendidos
                    </a>
                </li>
            @endrole
            @role(\App\Enums\UserRole::NURSING_TECHNICIAN)
                <li class="my-1">
                    <a href="{{ route('ambulatory.prescriptions.attended') }}"
                        class="block truncate rounded-lg bg-gray-200 p-2 text-base font-normal text-gray-900 hover:bg-gray-300 dark:hover:bg-gray-700">
                        <i class="bi bi-file-earmark-medical-fill text-xl"></i>
                        &nbsp;
                        Prescrições
                    </a>
                </li>
                <li class="my-1">
                    <a href="{{ route('ambulatory.exams.attended') }}"
                        class="block truncate rounded-lg bg-gray-200 p-2 text-base font-normal text-gray-900 hover:bg-gray-300 dark:hover:bg-gray-700">
                        <i class="bi bi-clipboard2-pulse-fill text-xl"></i>
                        &nbsp;
                        Exames
                    </a>
                </li>
            @endrole
        </ul>
    </div>
    <div class="absolute bottom-10 flex w-full justify-center">
        <div @click.away="open = false" class="relative flex w-60 justify-center" x-data="{ open: false }">
            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute bottom-12 mt-2 w-full origin-top-right rounded-md shadow-lg md:w-48">
                <div class="rounded-md bg-gray-400 p-1 shadow">
                    <a href="{{ route('profile') }}"
                        class="mb-2 block w-full rounded bg-gray-500 px-4 py-2 text-center text-sm font-semibold hover:bg-gray-600">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}" class="block w-full">
                        @csrf
                        <button type="submit"
                            class="block w-full rounded bg-gray-500 px-4 py-2 text-sm font-semibold hover:bg-gray-600">
                            Sair
                        </button>
                    </form>
                </div>
            </div>
            <button @click="open = !open" class="block w-full rounded-xl bg-gray-400 py-2 hover:bg-gray-500">
                <span class="uppercase">{{ auth()->user()->firstName }}</span>
                <svg fill="currentColor" viewBox="0 0 20 20" :class="{ 'rotate-180': open, 'rotate-0': !open }"
                    class="ml-1 mt-1 inline h-4 w-4 transform transition-transform duration-200 md:-mt-1">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
    <button x-show="!$store.sidebarOpen.on"
        class="fixed left-0 top-3 rounded-xl bg-slate-400 px-2 py-1 text-2xl transition-all duration-300"
        @click="$store.sidebarOpen.toggle()">
        <i class="bi bi-chevron-right"></i>
    </button>
    <button class="fixed top-3 hidden rounded-xl bg-slate-400 px-2 py-1 text-2xl transition-all duration-300"
        :class="{ 'left-0': !$store.sidebarOpen.on, 'left-64': $store.sidebarOpen.on }"
        @click="$store.sidebarOpen.toggle()"><i
            :class="{ 'bi bi-chevron-right': !$store.sidebarOpen.on, 'bi bi-chevron-left': $store.sidebarOpen.on }"></i></button>
</aside>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('sidebarOpen', {
            on: Alpine.$persist(false).as('sidebarOpen'),
            toggle() {
                this.on = !this.on
            }
        });

        Alpine.store('sectionsSidebarOpen', {
            on: Alpine.$persist([]).as('sectionsSidebarOpen'),
            toggle(key) {
                this.on[key] = !this.on[key]
            }
        });
    })
</script>

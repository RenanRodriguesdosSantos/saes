<div>
    <div class="mx-4 mb-5">
        <h3 class="mb-4 font-bold">Identificação do Paciente</h3>
        <div>{{ $this->patientInfolist }}</div>
    </div>
    <div>
        {{ $this->showScreeningAction }}
    </div>
    <div>
        <button class="rounded-t-xl p-2 px-8 text-white hover:bg-gray-700 {{ $tab == 1 ? 'bg-gray-500' : 'bg-gray-900' }}"
            wire:click="setTab(1)">Atendimento</button>
        <button class="rounded-t-xl p-2 px-8 text-white hover:bg-gray-700 {{ $tab == 2 ? 'bg-gray-500' : 'bg-gray-900' }}"
            wire:click="setTab(2)">Receitas</button>
        <button class="rounded-t-xl p-2 px-8 text-white hover:bg-gray-700 {{ $tab == 3 ? 'bg-gray-500' : 'bg-gray-900' }}"
            wire:click="setTab(3)">Atestados</button>
        <button class="rounded-t-xl p-2 px-8 text-white hover:bg-gray-700 {{ $tab == 4 ? 'bg-gray-500' : 'bg-gray-900' }}"
            wire:click="setTab(4)">Encaminhamentos</button>
        <button class="rounded-t-xl p-2 px-8 text-white hover:bg-gray-700 {{ $tab == 5 ? 'bg-gray-500' : 'bg-gray-900' }}"
            wire:click="setTab(5)">Exames</button>
        <button class="rounded-t-xl p-2 px-8 text-white hover:bg-gray-700 {{ $tab == 6 ? 'bg-gray-500' : 'bg-gray-900' }}"
            wire:click="setTab(6)">Prescrição</button>
    </div>
    @switch($tab)
        @case(1)
            <div>
                <form wire:submit="submit" class="flex w-full flex-wrap">
                    <div class="w-full">
                        {{ $this->form }}
                    </div>
                    <div class="flex w-full justify-end">
                        <button type="submit"
                            class="m-2 mt-3 rounded-full bg-gray-900 p-2 px-16 text-white hover:bg-gray-700">Salvar</button>
                    </div>
                </form>
            </div>
        @break

        @case(2)
            @livewire('appointment.recipes', ['appointment' => $this->appointment])
        @break

        @case(3)
            @livewire('appointment.certificates', ['appointment' => $this->appointment])
        @break

        @case(4)
            @livewire('appointment.forwardings', ['appointment' => $this->appointment])
        @break

        @case(5)
            @livewire('appointment.exams', ['appointment' => $this->appointment])
        @break

        @case(6)
            @livewire('appointment.prescriptions', ['appointment' => $this->appointment])
        @break
        @default
    @endswitch
    <x-filament-actions::modals />
</div>

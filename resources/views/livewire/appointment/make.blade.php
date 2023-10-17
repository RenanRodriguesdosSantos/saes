<div>
    <div class="mx-4 mb-5">
        <h3 class="mb-4 font-bold">Identificação do Paciente</h3>
        <div>{{ $this->patientInfolist }}</div>
    </div>
    <div>
        {{ $this->showScreeningAction }}
    </div>
    <div>
        <button class="m-2 mt-3 rounded-full bg-gray-900 p-2 px-16 text-white hover:bg-gray-700"
            wire:click="setTab(1)">Atendimento</button>
        <button class="m-2 mt-3 rounded-full bg-gray-900 p-2 px-16 text-white hover:bg-gray-700"
            wire:click="setTab(2)">Receitas</button>
        <button class="m-2 mt-3 rounded-full bg-gray-900 p-2 px-16 text-white hover:bg-gray-700"
            wire:click="setTab(3)">Atestados</button>
        <button class="m-2 mt-3 rounded-full bg-gray-900 p-2 px-16 text-white hover:bg-gray-700"
            wire:click="setTab(4)">Encaminhamentos</button>
    </div>
    @switch($tab)
        @case(2)
            @livewire('appointment.recipes', ['appointment' => $this->appointment])
        @break

        @case(3)
            @livewire('appointment.certificates', ['appointment' => $this->appointment])
        @break

        @case(4)
            @livewire('appointment.forwardings', ['appointment' => $this->appointment])
        @break

        @default
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
    @endswitch
    <x-filament-actions::modals />
</div>

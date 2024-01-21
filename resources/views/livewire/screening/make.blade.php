<div>
    @if (!$onlyView)
        <x-page-title>Atendimento</x-page-title>
    @endif
    <div class="flex justify-center">
        <div class="container">
            <div class="flex w-full flex-wrap">
                @if (!$onlyView)
                    <div class="md:w-1/2 md:pr-5">
                        <div class="mb-10">
                            <h3 class="mb-4 font-bold">Identificação do Paciente</h3>
                            <div>{{ $this->patientInfolist }}</div>
                        </div>
                        @livewire('vital-signs', ['service' => $this->service])
                    </div>
                @endif
                <div class="{{ $onlyView ? 'w-full' : 'md:w-1/2' }}">
                    <form wire:submit="submit" class="flex w-full flex-wrap">
                        <div class="w-full">
                            {{ $this->form }}
                        </div>
                        @if (!$onlyView)
                            <div class="flex w-full justify-end">
                                <button type="submit"
                                    class="m-2 mt-3 rounded-full bg-gray-900 p-2 px-16 text-white hover:bg-gray-700">Salvar</button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

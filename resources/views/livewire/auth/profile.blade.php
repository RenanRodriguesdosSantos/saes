<div class="flex flex-wrap p-5">
    <h2 class="w-full text-3xl">Dados Pessoais</h2>
    <div class="w-full border border-gray-900 p-3 rounded-lg">
        {{ $this->infolist }}
    </div>
    <h2 class="w-full text-3xl mt-5">Atualizar senha</h2>
    <div class="w-full md:w-1/2 border border-gray-900 p-3 rounded-lg">
        @livewire('auth.update-password')
    </div>
</div>

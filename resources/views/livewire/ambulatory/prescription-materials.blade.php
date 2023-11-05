<div>
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
</div>

<div class="flex h-[75vh] items-center justify-center">
    <form class="m-5 w-full rounded-xl bg-blue-200 p-5 text-center md:w-2/3 lg:w-1/4" wire:submit.prevent="authenticate">
        <h2 class="mb-5 text-center text-2xl font-bold">SAES</h2>
        {{ $this->form }}
        <button class="m-2 mt-3 rounded-full bg-gray-900 p-2 px-16 text-white hover:bg-gray-700">Login</button><br />
        <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('password.forgot') }}">Esqueceu sua
            senha?</a>
        @if (session()->has('status'))
            <div class="my-4 rounded border border-black bg-green-300 p-2 text-sm font-medium">
                {!! session('status') !!}
            </div>
        @endif
    </form>
</div>

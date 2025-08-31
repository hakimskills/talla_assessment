<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Search --}}
        <input type="text" wire:model.debounce.500ms="search"
            placeholder="Search favorites..."
            class="w-full rounded-xl border-gray-300 shadow-sm"/>

        {{-- Favorites Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($this->favorites as $fav)
                <div class="bg-white rounded-xl shadow overflow-hidden relative">
                    <img src="{{ $fav->image_url }}" class="w-full h-48 object-cover"/>
                    <div class="p-3">
                        <h3 class="font-semibold text-gray-700">{{ $fav->title }}</h3>
                    </div>
                    <div class="absolute top-2 right-2">
                        <button wire:click="unfavorite({{ $fav->id }})"
                            class="p-2 rounded-full bg-white shadow hover:bg-red-50">
                            <x-heroicon-s-heart class="w-5 h-5 text-red-500"/>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $this->favorites->links() }}
        </div>
    </div>
</x-filament-panels::page>

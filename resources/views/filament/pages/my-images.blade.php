<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Upload new image --}}
        <form wire:submit.prevent="addImage" class="p-4 bg-white rounded-xl shadow space-y-4">
            <h2 class="text-lg font-semibold text-gray-700">Add New Image</h2>

            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium text-gray-600">Title</label>
                <input type="text" wire:model="newTitle" placeholder="Enter title"
                    class="w-full rounded-xl border-gray-300 shadow-sm"/>
                @error('newTitle') 
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-600">Description</label>
                <textarea wire:model="newDescription" placeholder="Enter description"
                    class="w-full rounded-xl border-gray-300 shadow-sm"></textarea>
                @error('newDescription') 
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            {{-- File --}}
            <div>
                <label class="block text-sm font-medium text-gray-600">Select Image (max 6MB)</label>
                <input type="file" wire:model="newFile" class="w-full"/>
                @error('newFile') 
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Submit button --}}
            <div class="flex justify-end">
                <x-filament::button type="submit" color="primary">
                    <x-heroicon-o-arrow-up-tray class="w-5 h-5 mr-1"/>
                    Upload Image
                </x-filament::button>
            </div>
        </form>

        {{-- Search --}}
        <div>
            <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search your images..."
                class="w-full rounded-xl border-gray-300 shadow-sm"/>
        </div>

        {{-- Gallery --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($images as $image)
                <div class="bg-white rounded-xl shadow overflow-hidden relative">
                    <img src="{{ asset('storage/' . $image['path']) }}" class="w-full h-48 object-cover"/>

                    <div class="p-3">
                        <h3 class="font-semibold text-gray-700">{{ $image['title'] }}</h3>
                        <p class="text-sm text-gray-500">{{ $image['description'] }}</p>
                    </div>

                    <div class="absolute top-2 right-2 flex gap-2">
                        {{-- Favorite --}}
                        <button wire:click="toggleFavorite('{{ $image['id'] }}')"
                            class="p-2 rounded-full bg-white shadow">
                            @if(isset($favorites[$image['id']]))
                                <x-heroicon-s-heart class="w-5 h-5 text-red-500"/>
                            @else
                                <x-heroicon-o-heart class="w-5 h-5 text-gray-600"/>
                            @endif
                        </button>

                        {{-- Download --}}
                        <a href="{{ asset('storage/' . $image['path']) }}" download
                            class="p-2 rounded-full bg-white shadow">
                            <x-heroicon-o-arrow-down-tray class="w-5 h-5 text-gray-600"/>
                        </a>

                        {{-- Delete --}}
                        <button wire:click="deleteImage('{{ $image['id'] }}')"
                            class="p-2 rounded-full bg-white shadow hover:bg-red-50">
                            <x-heroicon-o-trash class="w-5 h-5 text-red-500"/>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @php $lastPage = ceil($totalResults / $perPage); @endphp
        @if ($lastPage > 1)
            <div class="flex justify-center items-center gap-4 mt-6">
                <x-filament::button wire:click="prevPage" :disabled="$page <= 1">Previous</x-filament::button>
                <span>Page {{ $page }} of {{ $lastPage }}</span>
                <x-filament::button wire:click="nextPage" :disabled="$page >= $lastPage">Next</x-filament::button>
            </div>
        @endif
    </div>
</x-filament-panels::page>

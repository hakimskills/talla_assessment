<x-filament-panels::page>
    <div class="space-y-6" x-data="{ showForm: false }">

        {{-- Add Image Button --}}
        <div class="flex justify-end">
            <x-filament::button @click="showForm = !showForm" color="primary">
                <x-heroicon-o-plus class="w-5 h-5 mr-1"/>
                {{ __('messages.add_new_image') }}
            </x-filament::button>
        </div>

        {{-- Upload new image (toggleable) --}}
        <form 
            wire:submit.prevent="addImage" 
            class="p-4 bg-white rounded-xl shadow space-y-4"
            x-show="showForm"
            x-transition
        >
            <h2 class="text-lg font-semibold text-gray-700">{{ __('messages.add_new_image') }}</h2>

            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium text-gray-600">{{ __('messages.title') }}</label>
                <input type="text" wire:model="newTitle" placeholder="{{ __('messages.enter_title') }}"
                    class="w-full rounded-xl border-gray-300 shadow-sm"/>
                @error('newTitle') 
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-600">{{ __('messages.description') }}</label>
                <textarea wire:model="newDescription" placeholder="{{ __('messages.enter_description') }}"
                    class="w-full rounded-xl border-gray-300 shadow-sm"></textarea>
                @error('newDescription') 
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            {{-- File --}}
            {{-- File --}}
<div>
    <label class="block text-sm font-medium text-gray-600">{{ __('messages.select_image') }}</label>

    <div class="flex items-center gap-3">
        <input type="file" wire:model="newFile" id="fileInput" class="hidden" />
        
        {{-- Custom Button --}}
        <x-filament::button type="button" color="primary" onclick="document.getElementById('fileInput').click()">
            <x-heroicon-o-photo class="w-5 h-5 mr-1"/>
            {{ __('messages.choose_image') }}
        </x-filament::button>

        {{-- Show file name --}}
        <span class="text-sm text-gray-600" x-text="$wire.newFile?.name"></span>
    </div>

    @error('newFile') 
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
    @enderror
</div>


            {{-- Submit button --}}
            <div class="flex justify-end gap-2">
                <x-filament::button type="submit" color="primary">
                    <x-heroicon-o-arrow-up-tray class="w-5 h-5 mr-1"/>
                    {{ __('messages.upload_image') }}
                </x-filament::button>

                {{-- Cancel --}}
                <x-filament::button type="button" color="secondary" @click="showForm = false">
                    {{ __('messages.cancel') }}
                </x-filament::button>
            </div>
        </form>

        {{-- Search --}}
        <div>
            <input type="text" wire:model.live.debounce.500ms="search" placeholder="{{ __('messages.search_my_images') }}"
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
                <x-filament::button wire:click="prevPage" :disabled="$page <= 1">{{ __('messages.previous') }}</x-filament::button>
                <span>{{ __('messages.page') }} {{ $page }} {{ __('messages.of') }} {{ $lastPage }}</span>
                <x-filament::button wire:click="nextPage" :disabled="$page >= $lastPage">{{ __('messages.next') }}</x-filament::button>
            </div>
        @endif
    </div>
</x-filament-panels::page>

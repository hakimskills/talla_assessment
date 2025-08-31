<x-filament-panels::page>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @foreach ($this->images as $image)
            <div class="bg-white rounded-xl shadow p-3 flex flex-col items-center">
                {{-- Image Thumbnail --}}
                <img src="{{ $image['image_url'] }}" 
                     alt="{{ $image['title'] }}" 
                     class="rounded-lg mb-3 object-cover w-40 h-40" />
                
                {{-- Title --}}
                <h3 class="text-center text-xs font-semibold mb-2 truncate w-full">
                    {{ $image['title'] }}
                </h3>
                
                {{-- Actions --}}
                <div class="flex space-x-3">
                    {{-- Favorite Button (dummy for now) --}}
                    <button class="text-red-500 hover:text-red-700">
                        <x-heroicon-o-heart class="w-5 h-5" />
                    </button>

                    {{-- Download Button --}}
                    <a href="{{ $image['image_url'] }}" download class="text-blue-500 hover:text-blue-700">
                        <x-heroicon-o-arrow-down-tray class="w-5 h-5" />
                    </a>
                </div>
            </div>
        @endforeach
    </div>
    
</x-filament-panels::page>

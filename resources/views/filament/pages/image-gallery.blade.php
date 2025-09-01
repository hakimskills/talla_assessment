<x-filament-panels::page>
    <div class="space-y-6" x-data="{ modalOpen: null }">

        {{-- Search box --}}
        <div class="flex items-center gap-3">
            <input
                type="text"
                wire:model.live.debounce.500ms="search"
                placeholder="🔍 Search artworks..."
                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 px-4 py-2"
            />
        </div>

        {{-- Gallery grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($images as $image)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    <div class="relative group">
                        {{-- Clickable image --}}
                        @if($image['image_url'])
                            <img
                                src="{{ $image['image_url'] }}"
                                alt="{{ $image['title'] }}"
                                class="w-full h-60 object-cover cursor-pointer transform transition duration-300 group-hover:scale-105"
                                @click="modalOpen = {{ $image['id'] }}"
                            />
                        @else
                            <div class="w-full h-60 flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        @endif

                        {{-- Actions (favorite + download) --}}
                        <div class="absolute top-2 right-2 flex gap-2 opacity-0 group-hover:opacity-100 transition">
                            <button
                                wire:click="toggleFavorite('{{ $image['id'] }}', '{{ $image['title'] }}', '{{ $image['image_url'] }}')"
                                class="p-2 rounded-full bg-white/90 hover:bg-white shadow"
                            >
                                @if(isset($favorites[$image['id']]))
                                    <x-heroicon-s-heart class="w-5 h-5 text-red-500"/>
                                @else
                                    <x-heroicon-o-heart class="w-5 h-5 text-gray-600"/>
                                @endif
                            </button>

                            @if($image['image_url'])
                                <a href="{{ $image['image_url'] }}" download
                                   class="p-2 rounded-full bg-white/90 hover:bg-white shadow">
                                    <x-heroicon-o-arrow-down-tray class="w-5 h-5 text-gray-600"/>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="p-3 text-sm font-medium text-gray-700 truncate">
                        {{ $image['title'] ?: 'Untitled' }}
                    </div>
                </div>

                {{-- Modal --}}
                <div
                    x-show="modalOpen === {{ $image['id'] }}"
                    class="fixed inset-0 bg-black/70 flex items-center justify-center z-50"
                    x-transition
                    @click.self="modalOpen = null"
                >
                    <div class="bg-white rounded-xl shadow-lg max-w-4xl p-4 relative">
                        <img src="{{ $image['image_url'] }}" alt="{{ $image['title'] }}"
                             class="mx-auto max-h-[80vh] rounded-lg"/>
                        <div class="text-center mt-3 font-semibold text-gray-700">
                            {{ $image['title'] }}
                        </div>
                        <button @click="modalOpen = null"
                                class="absolute top-4 right-6 text-white text-2xl">&times;</button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @php
            $lastPage = ceil($totalResults / $perPage);
        @endphp
        @if ($lastPage > 1)
            <div class="flex justify-center items-center gap-4 mt-6">
                <x-filament::button wire:click="prevPage" :disabled="$page <= 1">
                    Previous
                </x-filament::button>

                <span>Page {{ $page }} of {{ $lastPage }}</span>

                <x-filament::button wire:click="nextPage" :disabled="$page >= $lastPage">
                    Next
                </x-filament::button>
            </div>
        @endif
    </div>
</x-filament-panels::page>

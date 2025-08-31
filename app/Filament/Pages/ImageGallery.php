<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;
use App\Models\Favorite;

class ImageGallery extends Page
{
    protected string $view = 'filament.pages.image-gallery';

    public $images = [];
    public $page = 1;
    public $perPage = 12;
    public $search = '';
    public $totalResults = 0;
    public $favorites = []; // artwork_id => true

    public function mount()
    {
        $this->loadFavorites();
        $this->loadImages();
    }

    public function updatedSearch($value)
    {
        $this->page = 1;
        $this->loadImages();
    }

    public function updatedPage()
    {
        $this->loadImages();
    }

    public function loadFavorites()
    {
        $userId = auth()->id();

        $this->favorites = $userId
            ? Favorite::where('user_id', $userId)
                ->pluck('artwork_id')
                ->mapWithKeys(fn ($id) => [$id => true])
                ->toArray()
            : [];
    }

    protected function iiifUrl(?string $base, ?string $imageId, int $size = 600): ?string
    {
        if (! $imageId) return null;
        return "{$base}/{$imageId}/full/{$size},/0/default.jpg";
    }

    public function loadImages()
    {
        $perPage = (int) $this->perPage;
        $page    = (int) $this->page;

        // --- SEARCH ---
        if (trim($this->search) !== '') {
        $from = ($page - 1) * $perPage;

        $response = Http::get('https://api.artic.edu/api/v1/artworks/search', [
            'q'     => $this->search,
            'fields'=> 'id,title,image_id,artist_display,date_display', // Add more fields for better matching
            'size'  => $perPage,
            'from'  => $from,
            'query' => [
                'bool' => [
                    'should' => [
                        ['match' => ['title' => ['query' => $this->search, 'boost' => 3]]],
                        ['match' => ['artist_display' => ['query' => $this->search, 'boost' => 2]]],
                        ['match' => ['_all' => $this->search]]
                    ]
                ]
            ]
        ]);

            if (! $response->successful()) {
                $this->images = [];
                $this->totalResults = 0;
                return;
            }

            $json = $response->json();
            $this->totalResults = data_get($json, 'info.total', count($json['data'] ?? []));
            $iiifBase = data_get($json, 'config.iiif_url', 'https://www.artic.edu/iiif/2');

            $this->images = collect($json['data'] ?? [])->map(fn ($item) => [
                'id'        => $item['id'],
                'title'     => $item['title'] ?? '',
                'image_id'  => $item['image_id'] ?? null,
                'image_url' => $this->iiifUrl($iiifBase, $item['image_id'] ?? null, 800),
            ])->toArray();

            return;
        }

        // --- DEFAULT LISTING ---
        $response = Http::get('https://api.artic.edu/api/v1/artworks', [
            'page'   => $page,
            'limit'  => $perPage,
            'fields' => 'id,title,image_id',
        ]);

        if (! $response->successful()) {
            $this->images = [];
            $this->totalResults = 0;
            return;
        }

        $json = $response->json();
        $this->totalResults = data_get($json, 'pagination.total', count($json['data'] ?? []));
        $iiifBase = data_get($json, 'config.iiif_url', 'https://www.artic.edu/iiif/2');

        $this->images = collect($json['data'] ?? [])->map(fn ($item) => [
            'id'        => $item['id'],
            'title'     => $item['title'] ?? '',
            'image_id'  => $item['image_id'] ?? null,
            'image_url' => $this->iiifUrl($iiifBase, $item['image_id'] ?? null, 1200),
        ])->toArray();
    }

    public function prevPage()
    {
        if ($this->page > 1) {
            $this->page--;
            $this->loadImages();
        }
    }

    public function nextPage()
    {
        $last = (int) ceil($this->totalResults / $this->perPage);
        if ($this->page < $last) {
            $this->page++;
            $this->loadImages();
        }
    }

    public function toggleFavorite($artworkId, $title = null, $imageUrl = null)
    {
        $userId = auth()->id();
        if (! $userId) return;

        $existing = Favorite::where('user_id', $userId)
            ->where('artwork_id', $artworkId)
            ->first();

        if ($existing) {
            $existing->delete();
            unset($this->favorites[$artworkId]);
        } else {
            Favorite::create([
                'user_id'    => $userId,
                'artwork_id' => $artworkId,
                'title'      => $title,
                'image_url'  => $imageUrl,
            ]);
            $this->favorites[$artworkId] = true;
        }

        $this->loadFavorites();
    }

    public function getLastPageProperty()
    {
        return max(1, (int) ceil($this->totalResults / $this->perPage));
    }
}

<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class ImageGallery extends Page
{
    protected string $view = 'filament.pages.image-gallery';

    public $images = [];

    public function mount()
    {
        // Fetch artworks from API
        $response = Http::get('https://api.artic.edu/api/v1/artworks', [
            'page' => 1,
            'limit' => 20, // limit number of results
            'fields' => 'id,title,image_id',
        ]);

        if ($response->successful()) {
            $data = $response->json();

            $this->images = collect($data['data'])->map(function ($item) {
                // Build IIIF Image URL
                $imageUrl = "https://www.artic.edu/iiif/2/{$item['image_id']}/full/843,/0/default.jpg";

                return [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'image_url' => $imageUrl,
                ];
            });
        }
    }
}

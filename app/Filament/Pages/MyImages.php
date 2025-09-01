<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\UserImage;
use App\Models\Favorite;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class MyImages extends Page
{
    use WithFileUploads;

    protected string $view = 'filament.pages.my-images';

    public $images = [];
    public $favorites = [];
    public $search = '';
    public $page = 1;
    public $perPage = 5;
    public $totalResults = 0;

    public $newTitle = '';
    public $newDescription = '';
    public $newFile;

    /*
    |--------------------------------------------------------------------------
    | Filament v4 Overrides (translated)
    |--------------------------------------------------------------------------
    */

    // Page title (top bar)
    public function getTitle(): string
    {
        return __('messages.my_images_title');
    }

    // Sidebar navigation label (must stay static)
    public static function getNavigationLabel(): string
    {
        return __('messages.my_images_nav');
    }

    // Breadcrumb label (optional, non-static)
    public function getBreadcrumb(): string
    {
        return __('messages.my_images_nav');
    }

    /*
    |--------------------------------------------------------------------------
    | Lifecycle
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $this->loadFavorites();
        $this->loadImages();
    }

    public function updatedSearch()
    {
        $this->page = 1;
        $this->loadImages();
    }

    public function updatedPage()
    {
        $this->loadImages();
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

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

    public function loadImages()
    {
        $query = UserImage::where('user_id', auth()->id());

        if (trim($this->search) !== '') {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        $this->totalResults = $query->count();

        $this->images = $query
            ->latest()
            ->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get()
            ->toArray();
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

    public function addImage()
    {
        $this->validate([
            'newTitle'       => 'required|string|max:255',
            'newDescription' => 'required|string',
            'newFile'        => 'required|file|max:6144|mimes:jpg,jpeg,png,gif,webp',
        ]);

        $path = $this->newFile->store('user-images', 'public');

        UserImage::create([
            'user_id'     => auth()->id(),
            'title'       => $this->newTitle,
            'description' => $this->newDescription,
            'path'        => $path,
        ]);

        $this->reset(['newTitle', 'newDescription', 'newFile']);
        $this->loadImages();
    }

    public function deleteImage($id)
    {
        $image = UserImage::where('user_id', auth()->id())->findOrFail($id);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        $this->loadImages();
    }

    public function toggleFavorite($id)
    {
        $userId = auth()->id();
        if (! $userId) return;

        $existing = Favorite::where('user_id', $userId)
            ->where('artwork_id', $id)
            ->first();

        if ($existing) {
            $existing->delete();
            unset($this->favorites[$id]);
        } else {
            $image = UserImage::find($id);

            if ($image) {
                Favorite::create([
                    'user_id'    => $userId,
                    'artwork_id' => $id,
                    'title'      => $image->title,
                    'image_url'  => asset('storage/' . $image->path),
                ]);

                $this->favorites[$id] = true;
            }
        }

        $this->loadFavorites();
    }
}

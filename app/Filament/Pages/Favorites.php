<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class Favorites extends Page
{
    public $search = '';
    public $page = 1;
    public $perPage = 12;

    protected string $view = 'filament.pages.favorites';

    public function getTotalResultsProperty()
    {
        return Favorite::where('user_id', Auth::id())
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->count();
    }

    public function getFavoritesProperty()
    {
        return Favorite::where('user_id', Auth::id())
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
    }

    public function prevPage()
    {
        if ($this->page > 1) {
            $this->page--;
        }
    }

    public function nextPage()
    {
        $lastPage = ceil($this->totalResults / $this->perPage);
        if ($this->page < $lastPage) {
            $this->page++;
        }
    }

    public function unfavorite($id)
    {
        Favorite::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();

        // Go back a page if this one becomes empty
        if ($this->page > 1 && $this->favorites->isEmpty()) {
            $this->page--;
        }
    }
}

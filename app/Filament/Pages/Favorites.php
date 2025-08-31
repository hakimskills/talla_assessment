<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Favorite;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Favorites extends Page
{
    use WithPagination;

    public $search = '';

    protected string $view = 'filament.pages.favorites';

    public function getFavoritesProperty()
    {
        return Favorite::where('user_id', Auth::id())
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->paginate(12);
    }

    public function unfavorite($id)
    {
        Favorite::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();
    }
}

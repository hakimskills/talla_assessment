<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
     return redirect('/auth');
});
Route::get('/set-locale/{locale}', function ($locale) {
        session(['locale' => $locale]);
        app()->setLocale($locale);

        return redirect()->back();
    })->name('set-locale');
Route::get('/download-image', function (\Illuminate\Http\Request $request) {
    $url = $request->query('url');
    $title = $request->query('title', 'image');

    if (!$url) {
        abort(404);
    }

    $response = Http::get($url);

    if (!$response->ok()) {
        abort(404);
    }

    $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
    $filename = str_replace(' ', '_', $title) . '.' . $extension;

    return Response::make($response->body(), 200, [
        'Content-Type' => $response->header('Content-Type', 'image/jpeg'),
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ]);
})->name('download.image');
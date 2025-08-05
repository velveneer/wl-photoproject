<?php

use App\Livewire\Search;
use App\Livewire\Dashboard;
use App\Livewire\ArticleList;
use App\Livewire\EditArticle;
use App\Livewire\ShowArticle;
use App\Livewire\ArticleIndex;
use App\Livewire\CreateArticle;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;

Route::get('/', ArticleIndex::class);

Route::get('/articles/{article}', ShowArticle::class);

Route::get('/dashboard', Dashboard::class);
Route::get('/dashboard/articles', ArticleList::class);
Route::get('/dashboard/articles/create', CreateArticle::class);
Route::get('/dashboard/articles/{article}/edit', EditArticle::class);

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';

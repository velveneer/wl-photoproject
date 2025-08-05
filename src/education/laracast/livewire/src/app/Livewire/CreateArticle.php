<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Attributes\Validate;
use App\Livewire\Forms\ArticleForm;

class CreateArticle extends AdminComponent
{
    public ArticleForm $form;

    public function save() {
        $this -> form -> store();

        $this->redirect('/dashboard/articles', navigate: true);
    }

    public function render()
    {
        return view('livewire.create-article');
    }
}

<?php

namespace App\Livewire;

use App\Models\Article;
use App\Livewire\Forms\ArticleForm;

class EditArticle extends AdminComponent
{
    public ArticleForm $form;

    public function mount(Article $article) {
        $this -> form -> setArticle($article); 
    }

    public function save()
    {
        $this -> form -> update();

        $this->redirect('/dashboard/articles', navigate: true);
    }

    public function render()
    {
        return view('livewire.edit-article');
    }
}

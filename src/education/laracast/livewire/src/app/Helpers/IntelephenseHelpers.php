<?php

namespace Illuminate\Contracts\View;

use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;

interface View extends Renderable
{
    /** @return static */
    public function layout();
   
}

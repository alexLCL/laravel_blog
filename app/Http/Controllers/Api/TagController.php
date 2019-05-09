<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use App\Models\Tag;

class TagController extends Controller
{
    use Helpers;

    public function show($id){
        $tag = Tag::findOrFail($id);
        return $this->response->array($tag->toArray());
    }
}

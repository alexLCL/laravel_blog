<?php

namespace App\Transformers;

use App\Models\Tag;
use App\User;
use League\Fractal\TransformerAbstract;

class TagTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user'];

    public function transform(Tag $task)
    {
        return [
            'id' => $task->id,
            'title' => $task->title,
            'completed' => $task->is_completed ? 'yes' : 'no',
            'link'=>route('tag.show',['id'=>$task->id])
        ];
    }

    public function includeUser(Tag $tag)
    {
        $user=User::findOrFail($tag->user_id);
        return $this->item($user, new UserTransformer());
    }
}
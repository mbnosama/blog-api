<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'comment'=>$this->comment,
            'author_id'=>$this->author_id,
            'article_id'=>$this->article_id,
            //'author'=>$this->author->name,
            //'article'=>$this->article->id
        ];
    }
}

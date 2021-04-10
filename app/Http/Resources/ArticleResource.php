<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'image'=>asset('ImagesForArticle/'.$this->image),
            'title'=>$this->title,
            'body'=>$this->body,
            'links'=>$this->links,
            'author_id'=>$this->author_id,
            //'author'=>$this->author->name
        ];
    }
}

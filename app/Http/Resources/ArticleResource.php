<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            // 'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}


// ------- FILE NON USATO -------
// esempio per capire com'è strutturato un Resource, non è usato nel progetto poichè fatto con blade
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
            'body' => $this->body,
            'image' => asset($this->image),
            'authors' => $this->relationLoaded('authors') ? $this->authors->implode('name', ', ') : null,
             //'authors' => optional($this->whenLoaded('authors'))->implode('name', ', '),
            'published_at' => $this->published_at->diffForHumans(),

        ];
    }
}

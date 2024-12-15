<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'source' => $this->source,
            'apiSource' => $this->api_source,
            'title' => $this->title,
            'datePublished' => $this->date_published,
            'category' => $this->category,
            'details' => $this->details
        ];
    }
}

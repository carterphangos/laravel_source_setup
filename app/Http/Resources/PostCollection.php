<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($item) {
                return new PostResource($item);
            }),
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}

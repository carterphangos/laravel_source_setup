<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($item) {
                return new CommentResource($item);
            }),
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}

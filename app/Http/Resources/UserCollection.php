<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class userCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($item) {
                return new UserResource($item);
            }),
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}

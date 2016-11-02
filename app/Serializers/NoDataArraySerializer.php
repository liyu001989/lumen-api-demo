<?php

namespace App\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class NoDataArraySerializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     */
    public function collection($resourceKey, array $data)
    {
        return [$resourceKey ?: 'result' => $data];
    }

    /**
     * Serialize an item.
     */
    public function item($resourceKey, array $data)
    {
        return ($resourceKey) ? [$resourceKey => $data] : $data;
    }
}

<?php
namespace App\Domains\Transformers;

use App\Domains\Models\Translation;
use League\Fractal\TransformerAbstract;

class TranslationTransfomer extends TransformerAbstract {

    /**
     * Turn this item object into a generic array
     *
     * @param Translation $resource
     * @return array
     */
    public function transform(Translation $resource)
    {
        return [
            'id' => $resource->id,
            'origin' => $resource->origin,
            'language' => $resource->language,
            'context' => $resource->context,
            'key' => $resource->key,
            'target' => $resource->target,
        ];
    }

}
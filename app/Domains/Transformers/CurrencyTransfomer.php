<?php
namespace App\Domains\Transformers;

use App\Domains\Models\Currency;
use League\Fractal\TransformerAbstract;

class CurrencyTransfomer extends TransformerAbstract {

    /**
     * Turn this item object into a generic array
     *
     * @param Currency $resource
     * @return array
     */
    public function transform(Currency $resource)
    {
        return [
            'id' => $resource->id,
            'format' => $resource->format,
            'default' => (bool) $resource->default,
        ];
    }

}
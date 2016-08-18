<?php
namespace App\Domains\Transformers;

use App\Domains\Models\Exchange;
use League\Fractal\TransformerAbstract;

class ExchangeTransfomer extends TransformerAbstract {

    protected $availableIncludes = [
        'exchange_historic'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Exchange $resource
     * @return array
     */
    public function transform(Exchange $resource)
    {
        return [
            'id' => implode(':', (array) $resource->id),
            'buy_value' => (float) $resource->buy_value,
            'sale_value' => (float) $resource->sale_value,
        ];
    }

    public function includeExchangeHistoric(Exchange $resource) {
        $exchange_historic = $resource->exchange_historic;

        return $this->item($exchange_historic, new ExchangeHistoricTransfomer);
    }
}
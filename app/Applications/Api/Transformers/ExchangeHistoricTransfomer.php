<?php
namespace App\Applications\Api\Transformers;

use App\Domains\Models\ExchangeHistoric;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use League\Fractal\TransformerAbstract;

class ExchangeHistoricTransfomer extends TransformerAbstract {

    protected $availableIncludes = [
        'exchange'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param ExchangeHistoric $resource
     * @return array
     */
    public function transform(ExchangeHistoric $resource)
    {
        return [
            'id' => $resource->id,
            'pair' => implode(':', (array) $resource->pair),
            'buy_value' => (float) $resource->buy_value,
            'sale_value' => (float) $resource->sale_value,
            'created_at' => $resource->created_at->format($resource->getDateFormat())
        ];
    }

    public function includeExchange(ExchangeHistoric $resource) {
        $exchange = $resource->exchange;

        return $this->item($exchange, new ExchangeTransfomer);
    }
}
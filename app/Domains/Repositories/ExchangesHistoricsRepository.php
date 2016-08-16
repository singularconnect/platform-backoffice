<?php

namespace App\Domains\Repositories;

use App\Domains\Models\ExchangeHistoric;
use brunojk\LaravelRethinkdb\Eloquent\Model;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class ExchangesHistoricsRepository extends CommonRepository {
    protected $modelClass = ExchangeHistoric::class;

    /** @return ExchangesRepository */
    protected function getExchangesRepository() {
        return app()->make(ExchangesRepository::class);
    }

    /** @return CurrenciesRepository */
    protected function getCurrenciesRepository() {
        return app()->make(CurrenciesRepository::class);
    }

    /**
     * @param array $pair
     * @param array $data
     * @return bool
     */
    public function createOrUpdateExchange(array $pair, array $data, $key) {

        $exc = $this->getExchangesRepository()->findByID($pair);
        $oper = !empty($exc) ? 1 : 0;

        $tofill = [
            'buy_value' => $data['buy_value'],
            'sale_value' => $data['sale_value'],
            'exchange_historic_id' => $key
        ];

        if( !$oper ) {
            $tofill['id'] = $data['pair'];

            $this->getExchangesRepository()->create($tofill);
        }else
            $this->getExchangesRepository()->update($exc, $tofill);

        return true;
    }

    /**
     * @param array $data
     * @return Model[]
     * @throws \App\Domains\Exceptions\DuplicateKeyException
     */
    public function create(array $data = []){
        if( is_array($data['pair'][0]) && is_array($data['pair'][0])){
            $res = $this->getCurrenciesRepository()->find($data['pair'][0]);
            if( !count($res) )
                $this->getCurrenciesRepository()->create($data['pair'][0]);

            $res = $this->getCurrenciesRepository()->find($data['pair'][1]);
            if( !count($res) )
                $this->getCurrenciesRepository()->create($data['pair'][1]);

            $data['pair'][0] = $data['pair'][0]['id'];
            $data['pair'][1] = $data['pair'][1]['id'];
        }

        $exh1 = parent::create($data);

        $this->createOrUpdateExchange($data['pair'], $data, $exh1->getKey());

        $data['pair'] = [$data['pair'][1], $data['pair'][0]];

        $s = $data['sale_value']; $b = $data['buy_value'];
        $data['sale_value'] = 1/$b;
        $data['buy_value'] = 1/$s;

        $exh2 = parent::create($data);

        $this->createOrUpdateExchange($data['pair'], $data, $exh2->getKey());

        return [$exh1, $exh2];
    }

    public function updateGetIds($model, array $data = []){
        $data['pair'] = [$model->pair[0], $model->pair[1]];

        $exhpair = $this->create($data);

        return [$exhpair[0]->getKey(), $exhpair[1]->getKey()];
    }

    public function update($model, array $data = []){
        $res = $this->updateGetIds($model, $data);

        return $res[0] && $res[1];
    }

    public function truncate() {
        $this->getCurrenciesRepository()->truncate();
        $this->getExchangesRepository()->truncate();
        parent::truncate();
    }


}
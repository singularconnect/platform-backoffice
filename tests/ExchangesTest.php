<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use \App\Domains\Repositories\CurrenciesRepository;
use \App\Domains\Repositories\ExchangesRepository;
use \App\Domains\Repositories\ExchangesHistoricsRepository;

class ExchangesTest extends TestCase
{
    /** @var CurrenciesRepository */
    protected $currenciesRepository;

    /** @var ExchangesHistoricsRepository */
    protected $exHistRepositories;

    /** @var ExchangesRepository */
    protected $exRepositories;

    public function createApplication() {
        $app = parent::createApplication();

        if (!Schema::hasTable('exchanges_historics'))
            Schema::create('exchanges_historics');

        if (!Schema::hasTable('exchanges'))
            Schema::create('exchanges');

        if (!Schema::hasTable('currencies'))
            Schema::create('currencies');

        $this->currenciesRepository = $app->make(CurrenciesRepository::class);
        $this->exHistRepositories = $app->make(ExchangesHistoricsRepository::class);
        $this->exRepositories = $app->make(ExchangesRepository::class);

        $this->beforeApplicationDestroyed(function(){
//            Schema::drop('exchanges_historics');
//            Schema::drop('currencies');
//            Schema::drop('exchanges');
        });

        return $app;
    }

    protected function setUp() {
        parent::setUp();
    }

    protected function tearDown() {
        $this->exHistRepositories->truncate();

        parent::tearDown();
    }

    public function testExchangesCreateUpdate() {
        $exchangeHistoricPair = $this->exHistRepositories->create([
            'pair' => [
                ['id' => 'USD', 'default' => true, 'format' => 'U$'],
                ['id' => 'BRL', 'default' => false, 'format' => 'R$']
            ],
            'buy_value' => 2.90,
            'sale_value' => 3.05
        ]);
        $this->assertNotNull($exchangeHistoricPair);
        $this->assertCount(2, $exchangeHistoricPair);

        $this->assertEquals(2, $this->exHistRepositories->count());
        $this->assertEquals(2, $this->exRepositories->count());

        $exchangeHistoric = $this->exHistRepositories->findById($exchangeHistoricPair[0]->getKey());
        $this->assertNotNull($exchangeHistoric);

        $this->assertNotNull($this->exRepositories->any()->exchange_historic);
        $this->assertNotNull($this->exHistRepositories->any()->exchange);

        $this->assertEquals(2.90, $exchangeHistoric->buy_value);
        $this->assertEquals(3.05, $exchangeHistoric->sale_value);

        sleep(2);
        $res = $this->exHistRepositories->update($exchangeHistoric, ['buy_value' => 3.33, 'sale_value' => 3.4]);

        $exchange = $this->exRepositories->findById($exchangeHistoric->pair);

        $this->assertNotNull($exchange);
        $this->assertEquals(3.33, $exchange->buy_value);
        $this->assertEquals(3.4, $exchange->sale_value);

        $this->assertTrue($res);

        sleep(2);
        $res = $this->exHistRepositories->update($exchangeHistoric, ['buy_value' => 3.65, 'sale_value' => 3.78]);

        $this->assertTrue($res);

        $exchange = $this->exRepositories->findById($exchangeHistoric->pair);

        $this->assertNotNull($exchange);
        $this->assertEquals(3.65, $exchange->buy_value);
        $this->assertEquals(3.78, $exchange->sale_value);

        $res = $this->exHistRepositories->r()->getAll($exchangeHistoric->pair, ['index' => 'pair'])->orderBy(r\desc('updated_at'))->limit(1)->run();

        //refetch
        $exchange = $this->exRepositories->findById($exchangeHistoric->pair);

        $this->assertEquals($res[0]['id'], $exchange->exchange_historic_id);
    }

    public function testCurrenciesNotEqualsCode() {
        $this->exHistRepositories->create([
            'pair' => [
                ['id' => 'USD', 'default' => true, 'format' => 'U$'],
                ['id' => 'BRL', 'default' => false, 'format' => 'R$']
            ],
            'buy_value' => 2.90,
            'sale_value' => 3.05
        ]);

        $currency = $this->currenciesRepository->any();
        $currencies = $this->currenciesRepository->where('id', '!=', $currency->id)->get();

        $this->assertEquals(1, count($currencies));

        foreach ($currencies as $curr)
            $this->assertTrue($curr != $currency->id);
    }

    public function testOneCurrencyDefaultOnly() {
        $this->exHistRepositories->create([
            'pair' => [
                ['id' => 'USD', 'default' => true, 'format' => 'U$'],
                ['id' => 'BRL', 'default' => false, 'format' => 'R$']
            ],
            'buy_value' => 2.90,
            'sale_value' => 3.05
        ]);

        $currencies = $this->currenciesRepository->where('default', true)->get();

        $this->assertEquals(1, count($currencies));
    }

    public function testCreateCurrencies() {
        $this->exHistRepositories->create([
            'pair' => [
                ['id' => 'USD', 'default' => true, 'format' => 'U$'],
                ['id' => 'BRL', 'default' => false, 'format' => 'R$']
            ],
            'buy_value' => 2.90,
            'sale_value' => 3.05
        ]);

        $this->assertEquals(2, $this->currenciesRepository->count());

        $currencies = DB::table('currencies')->get();
        $this->assertEquals(2, count($currencies));
    }
}

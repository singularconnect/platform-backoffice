<?php

class CurrenciesSeeder extends BaseSeeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var \App\Domains\Repositories\ExchangesHistoricsRepository $excHistRepository */
        $excHistRepository = app()->make('\App\Domains\Repositories\ExchangesHistoricsRepository');

        $excHistRepository->create([
            'pair' => [
                ['id' => 'USD', 'default' => true, 'format' => 'U$', 'fraction_size' => 2],
                ['id' => 'BRL', 'default' => false, 'format' => 'R$', 'fraction_size' => 2]
            ],
            'buy_value' => 2.90,
            'sale_value' => 3.05
        ]);

        // BRL - USD
        $excHistRepository->create([
            'pair' => [
                ['id' => 'BRL', 'default' => false, 'format' => 'U$', 'fraction_size' => 2],
                ['id' => 'PYG', 'default' => false, 'format' => 'G$', 'fraction_size' => 0]
            ],
            'buy_value' => 1600,
            'sale_value' => 1660
        ]);
        $excHistRepository->create([
            'pair' => [
                ['id' => 'USD', 'default' => true, 'format' => 'U$', 'fraction_size' => 2],
                ['id' => 'PYG', 'default' => false, 'format' => 'G$', 'fraction_size' => 0]
            ],
            'buy_value' => 5520,
            'sale_value' => 5555
        ]);

        // BRL - USD - PYG
        $excHistRepository->create([
            'pair' => [
                ['id' => 'USD', 'default' => true, 'format' => 'U$', 'fraction_size' => 2],
                ['id' => 'ARS', 'default' => false, 'format' => 'P$', 'fraction_size' => 0]
            ],
            'buy_value' => 16.20,
            'sale_value' => 17.20
        ]);
        $excHistRepository->create([
            'pair' => [
                ['id' => 'BRL', 'default' => false, 'format' => 'U$', 'fraction_size' => 2],
                ['id' => 'ARS', 'default' => false, 'format' => 'P$', 'fraction_size' => 0]
            ],
            'buy_value' => 4.74,
            'sale_value' => 5.13
        ]);
        $excHistRepository->create([
            'pair' => [
                ['id' => 'ARS', 'default' => false, 'format' => 'P$', 'fraction_size' => 0],
                ['id' => 'PYG', 'default' => false, 'format' => 'G$', 'fraction_size' => 0]
            ],
            'buy_value' => 320,
            'sale_value' => 345
        ]);
    }
}
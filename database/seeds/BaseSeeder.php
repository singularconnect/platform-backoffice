<?php
use Illuminate\Database\Seeder;

abstract class BaseSeeder extends Seeder
{
    /** @var \App\Domains\Repositories\I18nRepository */
    protected $i18n;

    public function __construct() {
        $this->i18n = app()->make('\App\Domains\Repositories\I18nRepository');
    }
}
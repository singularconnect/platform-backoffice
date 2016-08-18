<?php
namespace App\Domains\Repositories\Traits;

trait ChangeDBRepositoryTrait
{
    public function inDB($dbname) {
        \DB::setDatabaseName($dbname);
    }

    public function backTo($dbname) {
        \DB::setDatabaseName($dbname);
    }

    public function backToDefault() {
        $this->backTo(env('DB_DATABASE', 'homestead'));
    }
}
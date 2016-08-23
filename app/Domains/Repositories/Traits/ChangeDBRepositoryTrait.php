<?php
namespace App\Domains\Repositories\Traits;

trait ChangeDBRepositoryTrait
{
    public function inDB($dbname) {
        \DB::setDatabaseName($dbname);
    }

    public function backToDB($dbname) {
        \DB::setDatabaseName($dbname);
    }

    public function backToDefaultDB() {
        $this->backToDB(env('DB_DATABASE', 'homestead'));
    }
}
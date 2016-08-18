<?php
namespace App\Domains\Repositories\Contracts;

interface ChangeDBRepository
{
    /**
     * @param string $dbname
     * @return void
     */
    public function inDB($dbname);

    /**
     * @param string $dbname
     * @return void
     */
    public function backTo($dbname);

    /**
     * @return void
     */
    public function backToDefault();
}
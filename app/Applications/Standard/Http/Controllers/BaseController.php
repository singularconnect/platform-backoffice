<?php

namespace App\Applications\Standard\Http\Controllers;

use App\Core\Http\Controllers\CoreController;

abstract class BaseController extends CoreController
{
    protected $view_namespace = 'standard::';

    protected $defData = [
        'appTitle' => "Backoffice SingularBet",
        'language' => 'pt-BR',

        'pageTitle' => '',
        'page' => '',
        'subPage' => '',
    ];
}
<?php

namespace App\Domains\Models;

use \brunojk\LaravelRethinkdb\Eloquent\Model;

class GenericModel extends Model {
    public $timestamps = false;
    protected static $unguarded = true;
    protected $keyType = 'string';

    // performance issues for the repositories read the table name
    public static $tablename = null;

    public static $field_orderby_default = 'id';
    public static $field_search_default = null;

    public static $rules = [];

    public static $transformers = [
        'default' => null
    ];

    public function getDateFormat()  {
        return parent::getDateFormat();
    }
}

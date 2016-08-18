<?php

namespace App\Domains\Repositories;

use App\Domains\Exceptions\DuplicateKeyException;
use App\Domains\Repositories\Contracts\ChangeDBRepository;
use App\Domains\Repositories\Traits\ChangeDBRepositoryTrait;
use Artesaos\Warehouse\AbstractCrudRepository;
use Artesaos\Warehouse\Contracts\Segregated\CrudRepository;
use brunojk\LaravelRethinkdb\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use r;

class CommonRepository extends AbstractCrudRepository implements CrudRepository, ChangeDBRepository {
    use ChangeDBRepositoryTrait;

    protected $rulesPagination = [
        'page' => 'numeric',
        'take' => 'numeric|min:1',
        'orderby' => 'regex:/^[a-zA-Z]{2,15}$/',
        'direction' => 'string|in:asc,desc,ASC,DESC',
        'q' => 'string|between:3,15',
        'fields' => 'regex:/^[a-zA-Z]{2,15}(,[a-zA-Z]{2,15})*$/',
        'reqall' => 'boolean'
    ];

    protected $i18n = null;

    /**
     * @return I18nRepository
     */
    protected function i18n() {
        $this->i18n = $this->i18n ?: app()->make('\App\Domains\Repositories\I18nRepository');
        return $this->i18n;
    }

    protected function hydrate( $result ) {
        if( $result instanceof \ArrayObject)
            $result = [(array) $result];

        else if( is_object($result) )
            $result = $result->toArray();

        return $result ? $this->construct()->hydrate($result) : null;
    }

    /**
     * @param $q
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function run($q) {
        $res = $q->run();

        return $this->hydrate($res);
    }

    public function getTransformer(string $type = null) {
        try{
            return (new \ReflectionClass($this->modelClass))->getStaticPropertyValue('transformers')[$type?: 'default'];
        } catch(\Exception $ex) {
            return null;
        }
    }

    public function getNewTransformer(string $type = null) {
        $cl = $this->getTransformer($type);
        return $cl ? new $cl : null;
    }

    /**
     * @return array|null
     */
    public function toValidatePagination() {
        return $this->rulesPagination;
    }

    public function getFieldSearchDefault() {
        try{
            return (new \ReflectionClass($this->modelClass))->getStaticPropertyValue('field_search_default');
        } catch(\Exception $ex) {
            return null;
        }
    }

    public function getOrderByDefault() {
        try{
            return (new \ReflectionClass($this->modelClass))->getStaticPropertyValue('field_orderby_default');
        } catch(\Exception $ex) {
            return null;
        }
    }

    public function getPerPageDefault() {
        return $this->construct()->getPerPage();
    }

    /**
     * @param string $type
     * @return array|null
     */
    public function toValidateInput($type = 'default') {
        try{
            return (new \ReflectionClass($this->modelClass))->getStaticPropertyValue('rules')[$type];
        } catch(\Exception $ex) {
            return null;
        }
    }

    /**
     * @param array $inputs
     * @param string $rulesType
     * @return array|null
     */
    public function validator(array $inputs, $rulesType = 'default') {
        $tovalidate = $this->toValidateInput($rulesType);
        $inputs = array_intersect_key($inputs, array_flip(array_keys($tovalidate)));

        return Validator::make($inputs, $tovalidate);
    }

    /**
     * @return array|null
     */
    public function searchFields() {
        return $this->toValidateInput('search_fields');
    }

    /**
     * @param array $data
     * @return Model
     */
    public function construct(array $data = []) {
        return new $this->modelClass($data);
    }

    /**
     * @return string
     */
    public function getTableName() {
        try{
            return (new \ReflectionClass($this->modelClass))->getStaticPropertyValue('tablename');
        } catch(\Exception $ex) {
            return $this->construct()->getTable();
        }
    }

    /**
     * @param array $data
     * @param array $options
     * @return Model
     * @throws DuplicateKeyException
     */
    public function create(array $data = [], array $options = []) {

        if( count($options) )
            return $this->insert($data, $options);

        $res = parent::create($data);

        if( $res->id == null ) {
            throw new DuplicateKeyException;
        }

        return $res;
    }

    /**
     * @return \brunojk\LaravelRethinkdb\Query
     */
    public function r() {
        return parent::newQuery()->getQuery()->r();
    }

    /**
     * @param array $data
     * @param array $options
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function insert(array $data = [], array $options = []) {
        $res = $this->r()->insert($data, $options);

        if( isset($res['generated_keys']) )
            $data['id'] = $res['generated_keys'][0];

        if( !$res['errors'] )
            $res = $this->hydrate([$data])->first();
        else
            $res = false;

        return $res;
    }

    /**
     * @param $column
     * @param null $operator
     * @param null $value
     * @param string $boolean
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and') {
        return $this->newQuery()->where($column, $operator, $value, $boolean);
    }

    /**
     * @param $column
     * @param $values
     * @param string $boolean
     * @param bool $not
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false) {
        return $this->newQuery()->whereIn($column, $values, $boolean, $not);
    }

    /**
     * @param $values
     * @param array $indexes
     * @param bool $is_compounded
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function find($values, array $indexes = ['index' => 'id'], $is_compounded = false) {
        $operation = is_array($values) && !$is_compounded ? 'getMultiple' : 'getAll';

        $res = $this->run($this->r()->$operation($values, $indexes));
        return $res;
    }

    public function remove($values, array $indexes = ['index' => 'id'], $is_compounded = false) {
        $operation = is_array($values) && !$is_compounded ? 'getMultiple' : 'getAll';

        $res = $this->r()->$operation($values, $indexes)->delete()->run();

        return $res['deleted'];
    }

    /**
     * @param int $qt
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function sample($qt = 1) {
        $res = $this->r()->sample($qt)->run();
        return $this->construct()->hydrate($res);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function any() {
        return $this->sample()->first();
    }

    /**
     * @param int $take
     * @param bool $paginate
     * @return \Illuminate\Pagination\Paginator
     */
    public function getAll($take = 15, $paginate = true) {
        return parent::getAll($take, $paginate);
    }

    /**
     * @param int $take
     * @param string $orderby
     * @param string $direction
     * @return \Illuminate\Pagination\Paginator
     */
    public function paginate($take = null, $orderby = 'id', $direction = 'desc') {
        $direction = strtolower($direction);
        $take = $take ? intval($take) : $take;

        /** @var Paginator $res */
        $res = $this->newQuery()->orderBy($orderby, $direction)->paginate($take);

        $res->addQuery('take', $take)
            ->addQuery('orderby', $orderby)
            ->addQuery('direction', $direction);

        return $res;
    }

    /**
     * @param $q
     * @param $fields
     * @param bool $reqAll
     * @param int $perPage
     * @param string $orderBy
     * @param string $direction
     * @return \Illuminate\Pagination\Paginator
     */
    public function searchPaginate($q, $fields, $reqAll = false, $perPage = null, $orderBy = 'id', $direction = 'desc') {
        $direction = strtolower($direction);
        $perPage = $perPage ? intval($perPage) : $perPage;
        $reqAll = boolval($reqAll);

        if( !is_array($fields) )
            $fields = explode(',', $fields);

        $query = $this->newQuery();
        $tomatch = '(?i)' . $q;
        $operator = 'regexp';
        $type = $reqAll ? 'where' : 'orWhere';

        foreach($fields as $field)
            $query = $query->$type($field, $operator, $tomatch);

        /** @var Paginator $res */
        $res = $query->orderBy($orderBy, $direction)->paginate($perPage);

        $res->addQuery('q', $q)
            ->addQuery('fields', implode(',', $fields));

        return $res;
    }

    /**
     * @return int
     */
    public function count() {
        return $this->r()->count();
    }

    /**
     * @param int|array  $id
     * @param bool $iscompound
     * @param bool $fail
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findByID($id, $iscompound = false, $fail = false) {
        if( $iscompound )
            return $this->where('id', '=', $id)->first();

        return parent::findByID($id, $fail);
    }

    /**
     * @param int|array $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function get($id) {
        $res = $this->run( $this->r()->get($id) );
        return $res ? $res->first() : null;
    }

    /**
     * @return void
     */
    public function truncate() {
        if( app()->environment() != 'production' )
            DB::table($this->getTableName())->truncate();
    }
}
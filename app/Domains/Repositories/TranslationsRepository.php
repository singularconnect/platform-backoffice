<?php
namespace App\Domains\Repositories;

use App\Domains\Models\Translation;

class TranslationsRepository extends CommonRepository {
    protected $modelClass = Translation::class;

    public function create(array $data = [], array $options = []) {
        if( !isset($data['id']) )
            $data['id'] = "${data['origin']}.${data['lang']}.${data['context']}.${data['key']}";

        return parent::create($data, $options);
    }

    public function getReducedByLanguageFullKey($lang) {
        return $this->r()->getAll($lang, ['index' => 'language'])//->filter(['context' => 'default'])
        ->map(function($doc){
            return [ [$doc('id'), $doc('target')] ];
        })
            ->reduce(function($left, $right){
                return $left->add($right);
            })
            ->rDefault([])
            ->coerceTo('object')
            ->run();
    }

    public function getReducedByLanguage($lang) {
        return $this->r()->getAll($lang, ['index' => 'language'])//->filter(['context' => 'default'])
            ->map(function($doc){
                return [ [$doc('context')->add('.')->add($doc('key')), $doc('target')] ];
            })
            ->reduce(function($left, $right){
                return $left->add($right);
            })
            ->rDefault([])
            ->coerceTo('object')
            ->run();
    }
}
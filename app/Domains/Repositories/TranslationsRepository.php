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

    public function getByLanguage($lang) {
        return $this->where('language', $lang)->get();
    }
}
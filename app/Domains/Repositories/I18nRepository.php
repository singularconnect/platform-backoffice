<?php
namespace App\Domains\Repositories;

use Illuminate\Redis\Database;

class I18nRepository {

    /**
     * @var Database
     */
    protected $redis = null;

    /**
     * @return Database
     */
    public function redis() {
        $this->redis = $this->redis ?: app()->make('redis')->connection('translations');
        return $this->redis;
    }

    /**
     * @return TranslationsRepository
     */
    public function translationRepository() {
        return app()->make('App\Domains\Repositories\TranslationsRepository');
    }

    /**
     * @param null $lang
     * @return string
     */
    protected function resolveLang($lang = null) {
        return $lang ?: app()->getLocale();
    }

    protected function resolvedKeys(&$id, &$context = null, &$lang = null) {
        $keys = explode('.', $id);
        $id = array_pop($keys); //last element, the key
        $context = $context ?: (count($keys) ? array_pop($keys) : 'default');
        $lang = count($keys) ? array_pop($keys) : $this->resolveLang($lang);

        $keyapp = "app.$lang.$context.$id";
        $keyplt = "plt.$lang.$context.$id";

        return [$keyapp, $keyplt];
    }

    public function del($id, $context = null, $lang = null) {
        $key = $this->resolvedKeys($id, $context, $lang)[0];

        $this->redis()->del($key);

        return $this->translationRepository()->remove($key);
    }

    public function get($id, $parameters = [], $context = null, $lang = null) {
        return trans($id, $parameters, $context, $lang);
    }

    public function choice($id, $number, $parameters = [], $context = null, $lang = null) {
        return trans_choice($id, $number, $parameters, $context, $lang);
    }

    public function set($id, $target, $context = null, $lang = null) {
        $key = $this->resolvedKeys($id, $context, $lang)[0];

        $this->redis()->set($key, $target);

        return $this->translationRepository()->create([
            'id' => $key,
            'origin' => 'app',
            'context' => $context,
            'key' => $id,
            'target' => $target,
            'language' => $lang
        ], ['conflict' => 'update']);
    }

    public function resolveLocale(array $values) {
        $res = null;

        foreach( $values as $value )
            if( $res = explode(',', explode(';', $value)[0])[0] )
                break;

        return strtolower($res);
    }

    public function truncate() {
        if( app()->environment() != 'production' )
            $this->redis()->flushDB();

        $this->translationRepository()->truncate();
    }
}
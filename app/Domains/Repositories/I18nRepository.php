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
        if( strpos($id, '.') === false )
            $id = ($context ?: 'default') . '.' . $id;

        $segments = explode('.', $id);
        $group = $segments[0];
        $item = implode('.', array_slice($segments, 1));

        $id = $item; //last element, the key
        $context = $context ?: $group;
        $lang = $this->resolveLang($lang);

        $keyapp = "app.$lang.$context.$id";
        $keyplt = "plt.$lang.$context.$id";

        return [$keyapp, $keyplt];
    }

    public function del($id, $context = null, $lang = null) {
        $key = $this->resolvedKeys($id, $context, $lang)[1];

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
        $key = $this->resolvedKeys($id, $context, $lang)[1];

        $this->redis()->set($key, $target);

        return $this->translationRepository()->create([
            'id' => $key,
            'origin' => explode('.', $key)[0],
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
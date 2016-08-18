<?php
namespace App\Domains\Repositories;

use Illuminate\Redis\Database;
use Cache;

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

        Cache::forget('translations.' . $lang);

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

        Cache::forget('translations.' . $lang);

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

    public function getLikeI18nFile($id) {
        $cached = Cache::rememberForever('translations.' . $id, function () use ($id) {
            $res = $this->translationRepository()->getByLanguage($id);
            return $this->formatLikeI18nFile($res);
        });

        return $cached;
    }

    protected function formatLikeI18nFile($res) {
        $aux = [];

        function fnit($val) {
            $k = $val[0];
            $r = array_slice($val, 1);

            if( count($r) == 1 )
                return [$k => $r[0]];

            else
                return [$k => fnit($r)];
        }

        foreach((object) $res as $t) {
            $aux[$t->context] = isset($aux[$t->context]) ? $aux[$t->context] : [];

            $segs = explode('.', $t->key);
            $segs[] = $t->target;

            $aux[$t->context] = array_merge( $aux[$t->context], fnit($segs) );
        }

        return $aux;
    }
}
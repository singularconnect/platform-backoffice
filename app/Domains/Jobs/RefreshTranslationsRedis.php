<?php

namespace App\Domains\Jobs;

use App\Domains\Repositories\TranslationsRepository;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RefreshTranslationsRedis extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $lang = [];

    /**
     * Create a new job instance.
     * @param string $lang
     */
    public function __construct($lang = 'en')
    {
        $this->lang = $lang;
    }

    /**
     * Execute the job.
     *
     * @param TranslationsRepository $repository
     */
    public function handle(TranslationsRepository $repository)
    {
        $res = $repository->getReducedByLanguageFullKey($this->lang);

        $redis = app('redis')->connection('translations');

        foreach( $res as $k => $t )
            $redis->set($k, $t);
    }
}

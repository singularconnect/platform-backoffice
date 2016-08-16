<?php
namespace App\Applications\Api\Http\Middleware;

use App\Domains\Repositories\I18nRepository;
use Closure;

class LanguageResolve
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = app()->make('\App\Domains\Repositories\I18nRepository')->resolveLocale([
            $request->query->get('lang'),
            $request->header('accept-language'),
        ]);

        if( $locale )
            app()->setLocale($locale);

        return $next($request);
    }
}

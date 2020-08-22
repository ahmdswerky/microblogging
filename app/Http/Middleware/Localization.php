<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

class Localization
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
        if ( request()->route()->getName() !== 'lang' ) {
            $locale = $request->expectsJson() ?
                        $this->getLocaleForApi() :
                        $this->getLocale();

            App::setLocale($locale);
        }

        return $next($request);
    }

    protected function getLocaleForApi()
    {
        $locale = request()->header('X-locale');

        if ( $locale && in_array($locale, config('app.locales')) ) {
            return $locale;
        }

        return config('app.fallback_locale');
    }

    protected function getLocale()
    {
        $locale = Cookie::get('lang');

        if ( $locale && in_array($locale, config('app.locales')) ) {
            return $locale;
        }

        return config('app.fallback_locale');
    }
}

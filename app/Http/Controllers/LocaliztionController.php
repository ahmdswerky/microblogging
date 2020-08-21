<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LocaliztionController extends Controller
{
    public function __invoke(Request $request, $locale)
    {
        if ( ! $request->expectsJson() ) {
            Cookie::queue('lang', $locale, 9999999, '/');
            return back();
        }
    }
}

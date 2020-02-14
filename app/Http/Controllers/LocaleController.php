<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LocaleController extends Controller
{
    public function setLocale($locale)
    {
        if (in_array($locale, \Config::get('app.locales'))) {
            session(['locale' => $locale]);
            if (Auth::check()) {
                $user = Auth::user();
                $user->locale = $locale;
                $user->update();
            }
        }
        return redirect()->back();
    }
}

<?php
namespace App\Helpers\Ang;

use Illuminate\Support\Facades\Request;

class Blade {
    /*
    |--------------------------------------------------------------------------
    | isActiveURL
    |--------------------------------------------------------------------------
    |
    | Check if current url is the one passed in, if so, then it's active.
    | For site navigation marking link with a class of "active".
    |
    */
    public static function isActiveURL($url, $class = 'active')
    {
        if (Request::path() == $url) {
             return $class;
        }
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: MohammadMasarra
 * Date: 05/02/2020
 * Time: 12:30 PM
 */

namespace App\Http\Middleware;
use Closure;
use Lcobucci\JWT\Parser as Parser;
use Carbon\Carbon as Carbon;
use Mockery\Exception;


class Auth
{
    public function handle($request, Closure $next)
    {
        $token=session()->get('token');
        $is_login=(session()->has('is_login') ? session()->has('is_login'):false);
        $datetime=session()->get('time');
        $user=session()->get('user');
        if(!$is_login){
            return redirect()->route('auth.login');
        }
        else{
            return $next($request);
        }
     }
}
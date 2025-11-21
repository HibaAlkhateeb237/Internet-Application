<?php
//
//namespace App\Http\Middleware;
//
//use Closure;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//
//class CheckOwner
//{
//    public function handle(Request $request, Closure $next)
//    {
//        // استخدمي guard الخاص بـ User
//        $user = Auth::guard('user-api')->user();
//
//        if (!$user) {
//            return response()->json(['message' => 'Unauthorized'], 403);
//        }
//
//        return $next($request);
//    }
//}


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckOwner
{

    public function handle(Request $request, Closure $next)
    {


        if (!empty(Auth::user()) && Auth()->user()->role != 0) {
            return response()->json([
                'status' => false,
            ], 401);
        } else {
            return $next($request);
        }


    }


}

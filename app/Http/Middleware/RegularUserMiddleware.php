<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class RegularUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // تحقق مما إذا كان المستخدم موثقًا
        if (Auth::check()) {
            // تحقق من نوع المستخدم (يمكنك تعديل الشرط حسب هيكل بيانات المستخدم لديك)
            $user = Auth::user();
            if ($user->role === '2') { // تأكد من أن لديك حقل للدور في نموذج المستخدم
                return $next($request);
            }
        }

        return response()->json(['message' => 'Unauthorized: Only regular users are allowed.'], 403);
    }
}

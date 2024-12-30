<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = Auth::guard('admin')->user();

        if ($user && $user->can($permission)) {
            return $next($request);
        }
        
        // Redirect back with a success message
        return redirect()->route('admin.dashboard')->with([
            'message' => 'This user do not have the permission', // Success message
            'alert-type' => 'error',  // Type of alert (success)
        ]);
    }
}

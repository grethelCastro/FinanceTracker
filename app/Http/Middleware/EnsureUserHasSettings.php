<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use App\Models\User;

class EnsureUserHasSettings
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var User|null $user */
        $user = $this->auth->user();

        if (!$user || !$user->hasSettings()) {
            return redirect()->route('perfil');
        }

        return $next($request);
    }
}
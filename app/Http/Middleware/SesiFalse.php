<?php

namespace App\Http\Middleware;

use App\Models\ModelToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class SesiFalse
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
        if (Session::has('data') && !empty(Session::get('data')->token)) {
            $sesitoken = Cache::remember('sesifalse', 10, function () {
                return Http::asForm()->post(config('app.data').'/api/session.php', [
                    'token' => Session::get('data')->token
                ])->body();
            });

            if (json_decode($sesitoken)->status == 'success') {
                if (ModelToken::where('token', Session::get('data')->token)->exists()) {
                    Alert::error('Gagal', 'Anda tidak dapat mengakses halaman ini');
                    return redirect(route('dashboard'));
                }
            }
        }

        return $next($request);
    }

}

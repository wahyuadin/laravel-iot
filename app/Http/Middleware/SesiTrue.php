<?php

namespace App\Http\Middleware;

use App\Models\ModelToken;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class SesiTrue
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
{
    if (!Session::has('data') || empty(Session::get('data')->token)) {
        return $this->redirectToLogin('Anda tidak dapat mengakses halaman ini');
    }

    $sesitoken = Cache::remember('sesitrue', 10, function() {
        return Http::asForm()->post(config('app.data').'/api/session.php', ['token' => Session::get('data')->token])->body();
    });

    if ($sesitoken) {
        $decodedToken = json_decode($sesitoken);
        if ($decodedToken && isset($decodedToken->status) && $decodedToken->status != 'success') {
            $this->clearSessionAndToken();
            return $this->redirectToLogin($decodedToken->failed() ? 'Gagal menghubungkan ke API!' : 'Token has been expired!');
        }
    } else {
        $this->clearSessionAndToken();
        return $this->redirectToLogin('Gagal menghubungkan ke API!');
    }

    return $next($request);
}

    private function redirectToLogin($message)
    {
        Cache::flush();
        Session::flush();
        Alert::error('Gagal', $message);
        return redirect(route('login'));
    }

    private function clearSessionAndToken()
    {
        ModelToken::where('token', '=', Session::get('data')->token)->delete();
        Session::forget('data');
        Cache::flush();
    }
}


<?php

namespace App\Http\Middleware;

use App\Models\ClassContent;
use App\Models\PaketContent;
use Closure;
use Illuminate\Http\Request;
use App\Models\TransaksiUser;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PaketAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $paketId = $request->route('paket_id') ?? $request->route('id');
        $materi_code = $request->route('code');

        if (!$paketId) {
            return response()->json([
                'success' => false,
                'message' => 'ID Paket tidak ditemukan'
            ], 400);
        }

        if(!is_null($materi_code) && !$materi_code){
            return response()->json([
                'success' => false,
                'message' => 'Materi tidak ditemukan'
            ], 400);
        }

        $user = Auth::user();

        // Check if user has active transaction for this paket
        $hasAccess = TransaksiUser::where('user_id', $user->id)
            ->where('paket_id', $paketId)
            ->where('status', 'success')
            ->where(function($query) {
                $query->whereNull('waktu_expired')
                      ->orWhere('waktu_expired', '>', now());
            })
            ->exists();

        $hasAccessContent = true;
        if(!is_null($materi_code)){
            $paketContent = PaketContent::where('paket_id', $paketId)->with('content')->get();
            foreach ($paketContent as $content) {
                $content->kode_materi = ClassContent::find($content->content_id)->kode_materi;
            }
            $hasAccessContent = $paketContent->contains('kode_materi', $materi_code);
        }

        // Allow access for free pakets or if user has valid transaction
        $isFree = \App\Models\PaketList::where('id', $paketId)
            ->where('tier', 'free')
            ->exists();

        if ((!$hasAccess && !$isFree) || !$hasAccessContent) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke paket ini',
                'error_code' => 'NO_ACCESS'
            ], 403);
        }

        return $next($request);
    }
}

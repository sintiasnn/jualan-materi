<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Models\Materi;
use App\Models\PaketList;
use App\Models\TransaksiUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaketController extends Controller
{
    /**
     * Display a list of available packages.
     */

    public function materi(Request $request){
        $id =  $request->route('id');
        return view('livewire.pages.user.materi',[
            'id' => $id,
            'paket' => PaketList::find($id),
        ]);
    }

    public function content(Request $request){
        $code = $request->route('code');
        $id =  $request->route('id');
        $materi = Materi::where('kode_materi',$code)
            ->select('kode_materi','nama_materi')->distinct()->first();
        return view('livewire.pages.user.content',[
            'id' => $id,
            'code' => $code,
            'materi' =>$materi
        ]);
    }

    public function index()
    {
        return view('livewire.pages.tutor.paket');
    }

    /**
     * Show details for a specific package.
     */
    public function show($id)
    {
        try {
            $paket = PaketList::findOrFail($id);

            $contentDetails = [
                'has_classes' => $paket->classes->isNotEmpty(),
                'has_tryouts' => $paket->tryouts->isNotEmpty(),
                'class_count' => $paket->classes->count(),
                'tryout_count' => $paket->tryouts->count(),
                'content' => [
                    'classes' => $paket->classes,
                    'tryouts' => $paket->tryouts
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'paket' => $paket,
                    'content_details' => $contentDetails
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching paket details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Paket tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Store data for a package.
     */
    public function store(Request $request){
        $request->validate([
            'input-paket-image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('input-paket-image')){
            $paketImage = $request->file('input-paket-image');
            $filename = time() . '.' . $paketImage->getClientOriginalName();
        }
        $paketItem = [
            'image' => $filename,
            'nama_paket' => $request->nama_paket,
            'audience' => $request->audience,
            'tipe' => $request->tipe,
            'harga' => $request->harga,
            'discount' => $request->discount,
            'tier' => $request->tier,
            'deskripsi' => $request->deskripsi,
            'active_status' => 1
        ];

        try {

            DB::beginTransaction();
            $paket = PaketList::create($paketItem);

            if($paket->saveOrFail()){
                DB::commit();
                if($request->hasFile('input-paket-image')){
                    Storage::put('/public' . Constants::PAKET_IMG_DIR . $filename, file_get_contents($paketImage->getRealPath()));
                }
                return redirect()->route('admin.paket')->with('message', 'Paket berhasil ditambahkan');
            }
            else {
                DB::rollBack();
                return redirect()->route('admin.paket.create')->with('error', true);
            }
        } catch (\Exception $e){
            DB::rollBack();
            return redirect()->route('admin.paket.create')->with('error-message', $e->getMessage());
        }
    }

    public function update(Request $request, $id){

        if($request->hasFile('input-paket-image')){
            $request->validate([
                'input-paket-image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $paketImage = $request->file('input-paket-image');
            $filename = time() . '.' . $paketImage->getClientOriginalName();
            $paketItem = [
                'image' => $filename,
                'nama_paket' => $request->nama_paket,
                'audience' => $request->audience,
                'tipe' => $request->tipe,
                'harga' => $request->harga,
                'discount' => $request->discount,
                'tier' => $request->tier,
                'deskripsi' => $request->deskripsi,
                'active_status' => 1
            ];
        } else {
            $paketItem = [
                'nama_paket' => $request->nama_paket,
                'audience' => $request->audience,
                'tipe' => $request->tipe,
                'harga' => $request->harga,
                'discount' => $request->discount,
                'tier' => $request->tier,
                'deskripsi' => $request->deskripsi,
                'active_status' => 1
            ];
        }

        try {

            DB::beginTransaction();
            $paketList = PaketList::find($id);
            if($request->hasFile('input-paket-image')){
                $prev_filename = $paketList->image;
                unlink(public_path() . '/storage' . Constants::PAKET_IMG_DIR . $prev_filename);
                Storage::put('/public' . Constants::PAKET_IMG_DIR . $filename, file_get_contents($paketImage->getRealPath()));
            }

            $paketList->update($paketItem);
            DB::commit();

            return redirect()->route('admin.paket')->with('message', 'Paket berhasil ditambahkan');
        } catch (\Exception $e){
            DB::rollBack();
            return redirect()->route('admin.paket.edit', $id)->with('error-message', $e->getMessage());
        }
    }


    /**
     * Check if the current user owns the package and what content they can access.
     */
    public function checkOwnership($id)
    {
        try {
            $user = Auth::user();

            $transaction = TransaksiUser::where('user_id', $user->id)
                ->where('paket_id', $id)
                ->where('status', 'success')
                ->where(function($query) {
                    $query->whereNull('waktu_expired')
                          ->orWhere('waktu_expired', '>', now());
                })
                ->latest()
                ->first();

            $paket = PaketList::with(['classes', 'tryouts'])->find($id);

            $accessDetails = null;
            if ($transaction || ($paket && $paket->tier === 'free')) {
                $accessDetails = [
                    'can_access_classes' => $paket->classes->isNotEmpty(),
                    'can_access_tryouts' => $paket->tryouts->isNotEmpty(),
                    'valid_until' => $transaction ? $transaction->waktu_expired : null,
                    'purchase_date' => $transaction ? $transaction->tanggal_pembelian : null
                ];
            }

            return response()->json([
                'success' => true,
                'owns_package' => (bool)$transaction || ($paket && $paket->tier === 'free'),
                'access_details' => $accessDetails
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking paket ownership: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memeriksa kepemilikan paket'
            ], 500);
        }
    }

    /**
     * Initiate a package purchase transaction.
     */
    public function purchase(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            if ($user->role !== 'user') {
                throw new \Exception('Hanya pengguna biasa yang dapat membeli paket.');
            }

            $paket = PaketList::findOrFail($id);

            // Check if user already has an active subscription
            $hasActiveSubscription = TransaksiUser::where('user_id', $user->id)
                ->where('paket_id', $id)
                ->where('status', 'success')
                ->where(function($query) {
                    $query->whereNull('waktu_expired')
                          ->orWhere('waktu_expired', '>', now());
                })
                ->exists();

            if ($hasActiveSubscription) {
                throw new \Exception('Anda sudah memiliki paket ini');
            }

            // Check if package is free
            if ($paket->tier === 'free') {
                throw new \Exception('Paket ini gratis dan tidak perlu dibeli');
            }

            $transaction = TransaksiUser::create([
                'kode_transaksi' => 'TRX-' . time() . '-' . $user->id,
                'user_id' => $user->id,
                'paket_id' => $paket->id,
                'total_amount' => $paket->harga,
                'status' => 'pending',
                'tanggal_pembelian' => now(),
                'waktu_expired' => $paket->validity_period ? now()->addDays($paket->validity_period) : null
            ]);

            // MidTrans integration
            $midtransPayload = [
                'transaction_details' => [
                    'order_id' => $transaction->kode_transaksi,
                    'gross_amount' => (int)$transaction->total_amount,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
                'enabled_payments' => [
                    'echannel',
                    'permata_va',
                    'bca_va',
                    'bni_va',
                    'bri_va',
                    'cimb_va',
                    'gopay',
                    'shopeepay',
                    'other_qris',
                    'bank_transfer'
                ],
            ];

            $snapToken = $this->createMidTransTransaction($midtransPayload);

            $transaction->update([
                'snap_token' => $snapToken,
                'redirect_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
                'redirect_url' => $transaction->redirect_url
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing paket purchase: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Create MidTrans transaction and get Snap token.
     */
    private function createMidTransTransaction($payload)
    {
        try {
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = (bool)env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            return \Midtrans\Snap::getSnapToken($payload);
        } catch (\Exception $e) {
            Log::error('Midtrans error: ' . $e->getMessage());
            throw new \Exception('Terjadi kesalahan saat memproses pembayaran');
        }
    }
}

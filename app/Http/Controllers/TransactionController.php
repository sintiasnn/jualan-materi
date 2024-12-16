<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiUser;
use App\Models\RedeemCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Midtrans\Notification;
use Midtrans\Config;
use Midtrans\Transaction;

class TransactionController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function bayarSekarang()
    {
        try {
            DB::beginTransaction();

            $transactionId = 'TRX-' . time();

            $transaksi = TransaksiUser::create([
                'user_id' => auth()->id(),
                'paket_id' => $this->paket->id,
                'kode_transaksi' => $transactionId,
                'total_amount' => $this->total,
                'status' => 'pending',
                'tanggal_pembelian' => now(),
                'waktu_expired' => now()->addDays(1)
            ]);

            $response = $this->createPayment($transactionId);

            DB::commit();

            if ($response['success']) {
                return redirect()->to($response['redirect_url']);
            } else {
                $this->promoMessage = $response['message'];
                $this->promoMessageClass = 'text-danger';
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment creation error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createPayment(Request $request, $transactionId)
    {
        DB::beginTransaction();
        try {
            $transaction = TransaksiUser::lockForUpdate()->findOrFail($transactionId);

            if ($transaction->status !== 'pending') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction already processed.'
                ], 400);
            }

            // Check and lock redeem code if exists
            if ($transaction->redeem_code_id) {
                $redeemCode = RedeemCode::lockForUpdate()->find($transaction->redeem_code_id);
                
                if (!$redeemCode || !$redeemCode->hasAvailableQuota()) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Redeem code no longer available.'
                    ], 400);
                }
                
                $redeemCode->increment('used_quota');
            }

            $user = Auth::user();

            $payload = [
                'transaction_details' => [
                    'order_id' => $transaction->kode_transaksi,
                    'gross_amount' => $transaction->total_amount,
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
                    'bank_transfer',
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($payload);

            $transaction->update([
                'snap_token' => $snapToken,
                'redirect_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}",
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'redirect_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment creation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function notificationHandler(Request $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $notification = $request->all();

                if (!isset($notification['order_id']) || !isset($notification['transaction_status'])) {
                    throw new \Exception('Invalid notification payload');
                }

                $transaction = TransaksiUser::lockForUpdate()
                    ->where('kode_transaksi', $notification['order_id'])
                    ->first();

                if (!$transaction) {
                    throw new \Exception('Transaction not found');
                }

                $transactionStatus = $notification['transaction_status'];
                
                // Handle redeem code quota if transaction fails/expires/cancels
                if ($transaction->redeem_code_id && 
                    in_array($transactionStatus, ['cancel', 'expire', 'failure']) && 
                    $transaction->status === 'pending') {
                    
                    $redeemCode = RedeemCode::lockForUpdate()
                        ->find($transaction->redeem_code_id);
                    
                    if ($redeemCode) {
                        $redeemCode->decrement('used_quota');
                    }
                }

                // Update transaction status
                $updateData = [
                    'status' => $transactionStatus === 'settlement' ? 'success' : 
                              ($transactionStatus === 'pending' ? 'pending' : 'failed'),
                    'gateway_waktu_pembayaran' => now(),
                    'gateway_fraud_status' => $notification['fraud_status'],
                    'gateway_payment_method' => $notification['payment_type'],
                    'gateway_transaction_id' => $notification['transaction_id'],
                    'gateway_status_message' => $notification['status_message'],
                ];

                $transaction->update($updateData);

                return response()->json([
                    'success' => true,
                    'message' => 'Transaction status updated successfully',
                    'fraud_status' => $notification['fraud_status'],
                    'gateway_waktu_pembayaran' => now(),
                    'gateway_transaction_id' => $notification['transaction_id'],
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Notification handling error: ' . $e->getMessage());
                
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        });
    }

    public function verifyPayment($orderId)
    {
        try {
            $status = Transaction::status($orderId);

            if ($status->transaction_status == 'settlement') {
                // Payment successful
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
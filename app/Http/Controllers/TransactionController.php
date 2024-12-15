<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiUser; // Import your models here
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;
use Midtrans\Config;
use Midtrans\Transaction;

class TransactionController extends Controller
{
    /**
     * Initialize MidTrans Config.
     */
    public function __construct()
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    /**
     * Generate a MidTrans Snap payment URL.
     */
    public function createPayment(Request $request, $transactionId)
    {
        $transaction = TransaksiUser::findOrFail($transactionId);

        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Transaction already processed.'
            ], 400);
        }

        $user = Auth::user();

        // MidTrans payment payload
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
                // Virtual Accounts
                'echannel',       // Mandiri Bill Payment
                'permata_va',
                'bca_va',
                'bni_va',
                'bri_va',
                'cimb_va',

                // E-Wallets
                'gopay',
                'shopeepay',

                // Other QRIS
                'other_qris',

                // Bank Transfers
                'bank_transfer',  // Bank Transfer payment method
            ],

        ];
        
        try {
            // Generate Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($payload);


            $transaction->update([
                'snap_token' => $snapToken,
                'redirect_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}",
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'redirect_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}",
            ]);

    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle MidTrans Payment Notification Callback.
     */
    public function notificationHandler(Request $request)
    {
        // Validate required fields
        $notification = $request->all();

        if (!isset($notification['order_id']) || !isset($notification['transaction_status'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid notification payload'
            ], 400);
        }

        $orderId = $notification['order_id'];
        $transactionStatus = $notification['transaction_status'];
        $transactionId = $notification['transaction_id'];
        $paymentMethod = $notification['payment_type'];
        $fraudStatus = $notification['fraud_status'];
        $statusMessage = $notification['status_message'];

        // Fetch the transaction from the database
        $transaction = TransaksiUser::where('kode_transaksi', $orderId)->first();

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found'
            ], 404);
        }

        // Update transaction status based on MidTrans notification
        if ($transactionStatus === 'settlement') {
            $transaction->update([
                'status' => 'success',
                'gateway_waktu_pembayaran' => now(),
                'gateway_fraud_status' => $fraudStatus,
                'gateway_payment_method' => $paymentMethod,
                'gateway_transaction_id'=> $transactionId,
                'gateway_status_message' => $statusMessage,
            ]);
            // $transaction->update(['status' => 'success']);
        } elseif ($transactionStatus === 'pending') {
            $transaction->update(['status' => 'pending']);
        } elseif (in_array($transactionStatus, ['cancel', 'expire', 'failure'])) {
            $transaction->update(['status' => 'failed']);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Transaction status updated successfully',
            'fraud_status' => $fraudStatus,
            'gateway_waktu_pembayaran' => now(),
            'gateway_transaction_id' => $transactionId,
        ]);
    }

    public function verifyPayment($orderId)
    {
        try {
            $status = Transaction::status($orderId);  // Get the transaction status

            // Process the response and check the status
            if ($status->transaction_status == 'settlement') {
                // Payment successful
            }
        } catch (\Exception $e) {
            // Handle error if something goes wrong
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}

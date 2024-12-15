<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiUser extends Model
{
    protected $table = 'transaksi_user';
    
    protected $fillable = [
        'kode_transaksi', 
        'user_id', 
        'paket_id', 
        'tanggal_pembelian', 
        'snap_token',
        'total_amount', 
        'redirect_url', 
        'gateway_waktu_pembayaran', 
        'gateway_fraud_status', 
        'gateway_payment_method',
        'gateway_transaction_id',
        'gateway_status_message',
        'redeem_code_id',
        'status',
        'created_at',  
        'updated_at',
    ];
    

    protected $casts = [
        'tanggal_pembelian' => 'datetime',
        'waktu_pembayaran' => 'datetime',
        'waktu_expired' => 'datetime',
        'status' => 'string'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function redeemCode(): BelongsTo
    {
        return $this->belongsTo(RedeemCode::class);
    }

    public function paket()
    {
        return $this->belongsTo(PaketList::class, 'paket_id', 'id');
    }

    // Status Helpers
    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    // Accessors for formatted dates
    public function getFormattedTanggalPembelianAttribute()
    {
        return $this->tanggal_pembelian->format('d M Y H:i');
    }

    public function getFormattedWaktuPembayaranAttribute()
    {
        return $this->waktu_pembayaran 
            ? $this->waktu_pembayaran->format('d M Y H:i') 
            : null;
    }
}
